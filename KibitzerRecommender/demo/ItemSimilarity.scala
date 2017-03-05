import java.io.File

import org.apache.log4j.Logger
import org.apache.log4j.Level

import org.apache.spark.SparkConf
import org.apache.spark.SparkContext
import org.apache.spark.SparkContext._


object ItemSimilarity {

  def main(args: Array[String]) {

    Logger.getLogger("org").setLevel(Level.WARN)
    Logger.getLogger("akka").setLevel(Level.WARN)
    Logger.getLogger("Remoting").setLevel(Level.WARN)

    if (args.length < 4) {
      println("Usage: /path/to/spark/bin/spark-submit" + 
              " --driver-memory 2g --class ItemSimilarity" +
              " target/scala-*/kibitzer-assembly*.jar" + 
              " /hdfs/path/to/DatasetDir TRAIN_FILENAME ITEMS_FILENAME \"item1,item2\"")
      sys.exit(1)
    }
    
    // load items and ratings
    val DatasetDir = args(0)
    val TRAIN_FILENAME = args(1)
    val ITEMS_FILENAME = args(2)

    // set up environment
    val conf = new SparkConf()
      .setAppName("ItemSimilarity")
      .set("spark.executor.memory", "2g")
    val sc = new SparkContext(conf)

    val items = sc.textFile(new File(DatasetDir,ITEMS_FILENAME).toString)
      .map(line => {
        val fields = line.split("\t")
        // format: (itemId, itemName)
        (fields(0).toInt, fields(1))
    })

    val ratings = sc.textFile(new File(DatasetDir,TRAIN_FILENAME).toString)
      .map(line => {
        val fields = line.split("\t")
        // format: (userId, itemId, rating))
        (fields(0).toInt, fields(1).toInt, fields(2).toInt)
    })

    val numRatings = ratings.count()
    println("Got " + numRatings + " ratings.")

    val itemNames = items.collectAsMap()    // for local use to map id <-> item name for pretty-printing

    // get num raters per item, keyed on item id
    val numRatersPerItem = ratings
      .groupBy(tup => tup._2)
      .map(grouped => (grouped._1, grouped._2.size))

    // join ratings with num raters on item id
    val ratingsWithSize = ratings
      .groupBy(tup => tup._2)
      .join(numRatersPerItem)
      .flatMap(joined => {
        joined._2._1.map(f => (f._1, f._2, f._3, joined._2._2))
    })

    // ratingsWithSize now contains the following fields: (user, item, rating, numRaters).

    // dummy copy of ratings for self join
    val ratings2 = ratingsWithSize.keyBy(tup => tup._1)

    // join on userid and filter item pairs such that we don't double-count and exclude self-pairs
    val ratingPairs =
      ratingsWithSize
      .keyBy(tup => tup._1)
      .join(ratings2)
      .filter(f => f._2._1._2 < f._2._2._2)

    // compute raw inputs to similarity metrics for each item pair
    val vectorCalcs =
      ratingPairs
      .map(data => {
        val key = (data._2._1._2, data._2._2._2)
        val stats =
          (data._2._1._3 * data._2._2._3, // rating 1 * rating 2
            data._2._1._3,                // rating item 1
            data._2._2._3,                // rating item 2
            math.pow(data._2._1._3, 2),   // square of rating item 1
            math.pow(data._2._2._3, 2),   // square of rating item 2
            data._2._1._4,                // number of raters item 1
            data._2._2._4)                // number of raters item 2
        (key, stats)
      })
      .groupByKey()
      .map(data => {
        val key = data._1
        val vals = data._2
        val size = vals.size
        val dotProduct = vals.map(f => f._1).sum
        val ratingSum = vals.map(f => f._2).sum
        val rating2Sum = vals.map(f => f._3).sum
        val ratingSq = vals.map(f => f._4).sum
        val rating2Sq = vals.map(f => f._5).sum
        val numRaters = vals.map(f => f._6).max
        val numRaters2 = vals.map(f => f._7).max
        (key, (size, dotProduct, ratingSum, rating2Sum, ratingSq, rating2Sq, numRaters, numRaters2))
      })

    // compute similarity metrics for each item pair
    val similarities =
      vectorCalcs
      .map(fields => {
        val key = fields._1
        val (size, dotProduct, ratingSum, rating2Sum, ratingNormSq, rating2NormSq, numRaters, numRaters2) = fields._2
        val corr = correlation(size, dotProduct, ratingSum, rating2Sum, ratingNormSq, rating2NormSq)
        val regCorr = regularizedCorrelation(size, dotProduct, ratingSum, rating2Sum,
          ratingNormSq, rating2NormSq, 10, 0)
        val cosSim = cosineSimilarity(dotProduct, scala.math.sqrt(ratingNormSq), scala.math.sqrt(rating2NormSq))
        val jaccard = jaccardSimilarity(size, numRaters, numRaters2)

        (key, (corr, regCorr, cosSim, jaccard))
      })

    var myList = (args(3).split(","))

    for ( x <- myList ) {
    
      // test a few items out (substitute the contains call with the relevant item name
      val sample = similarities.filter(m => {
        val items = m._1
        itemNames(items._1).contains(x)
      })

      // collect results, excluding NaNs if applicable
      val resultSame = sample.map(v => {
        val m1 = v._1._1
        val m2 = v._1._2
        val corr = v._2._1
        val rcorr = v._2._2
        val cos = v._2._3
        val j = v._2._4
        (itemNames(m1), itemNames(m2), corr, rcorr, cos, j)
      }).collect().filter(e => !(e._4 equals Double.NaN))    // test for NaNs must use equals rather than ==
      .sortBy(elem => (-elem._4, -elem._5, -elem._6, -elem._3)).take(10)
      
      val resultDifferent = sample.map(v => {
        val m1 = v._1._1
        val m2 = v._1._2
        val corr = v._2._1
        val rcorr = v._2._2
        val cos = v._2._3
        val j = v._2._4
        (itemNames(m1), itemNames(m2), corr, rcorr, cos, j)
      }).collect().filter(e => !(e._4 equals Double.NaN))    // test for NaNs must use equals rather than ==
      .sortBy(elem => (elem._4, elem._5, elem._6, elem._3)).take(10)

      // print the output
      var i = 1
      println("Top 10 Items similar to " + x)
      println("result is empty? " + resultSame.isEmpty)
      resultSame .foreach { r =>
        if(args.length < 4) {
          println("%2d".format(i) + ": " + r._2)
        } else {
          println("%2d".format(i) + ": " + "%2.4f,%2.4f,%2.4f,%2.4f %s".format(r._3, r._4, r._5, r._6, r._2))
        }
        i += 1
      }
      
      var j = 1
      println("Top 10 Items not similar to " + x)
      println("result is empty? " + resultDifferent.isEmpty)
      resultDifferent .foreach { r =>
        if(args.length < 4) {
          println("%2d".format(j) + ": " + r._2)
        } else {
          println("%2d".format(j) + ": " + "%2.4f,%2.4f,%2.4f,%2.4f %s".format(r._3, r._4, r._5, r._6, r._2))
        }
        j += 1
      }
    }
    
    // clean up
    sc.stop()
  }

  // *************************
  // * SIMILARITY MEASURES
  // *************************

  /**
   * The correlation between two vectors A, B is
   *   cov(A, B) / (stdDev(A) * stdDev(B))
   *
   * This is equivalent to
   *   [n * dotProduct(A, B) - sum(A) * sum(B)] /
   *     sqrt{ [n * norm(A)^2 - sum(A)^2] [n * norm(B)^2 - sum(B)^2] }
   */
  def correlation(size : Double, dotProduct : Double, ratingSum : Double,
                  rating2Sum : Double, ratingNormSq : Double, rating2NormSq : Double) = {
    val numerator = size * dotProduct - ratingSum * rating2Sum
    val denominator = scala.math.sqrt(size * ratingNormSq - ratingSum * ratingSum) *
      scala.math.sqrt(size * rating2NormSq - rating2Sum * rating2Sum)
      
    numerator / denominator
  }

  /**
   * Regularize correlation by adding virtual pseudocounts over a prior:
   *   RegularizedCorrelation = w * ActualCorrelation + (1 - w) * PriorCorrelation
   * where w = # actualPairs / (# actualPairs + # virtualPairs).
   */
  def regularizedCorrelation(size : Double, dotProduct : Double, ratingSum : Double,
                             rating2Sum : Double, ratingNormSq : Double, rating2NormSq : Double,
                             virtualCount : Double, priorCorrelation : Double) = {
    val unregularizedCorrelation = correlation(size, dotProduct, ratingSum, rating2Sum, ratingNormSq, rating2NormSq)
    val w = size / (size + virtualCount)
    
    w * unregularizedCorrelation + (1 - w) * priorCorrelation
  }

  /**
   * The cosine similarity between two vectors A, B is
   *   dotProduct(A, B) / (norm(A) * norm(B))
   */
  def cosineSimilarity(dotProduct : Double, ratingNorm : Double, rating2Norm : Double) = {
  
    dotProduct / (ratingNorm * rating2Norm)
  }

  /**
   * The Jaccard Similarity between two sets A, B is
   *   |Intersection(A, B)| / |Union(A, B)|
   */
  def jaccardSimilarity(usersInCommon : Double, totalUsers1 : Double, totalUsers2 : Double) = {
    val union = totalUsers1 + totalUsers2 - usersInCommon
    
    usersInCommon / union
  }

}
