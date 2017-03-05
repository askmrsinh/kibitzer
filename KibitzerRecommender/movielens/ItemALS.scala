import java.io.File

import scala.io.Source

import org.apache.log4j.Logger
import org.apache.log4j.Level

import org.apache.spark.SparkConf
import org.apache.spark.SparkContext
import org.apache.spark.SparkContext._
import org.apache.spark.rdd._
import org.apache.spark.mllib.recommendation.{ALS, Rating, MatrixFactorizationModel}

object ItemALS {

  def main(args: Array[String]) {

    Logger.getLogger("org").setLevel(Level.WARN)
    Logger.getLogger("akka").setLevel(Level.WARN)
    Logger.getLogger("Remoting").setLevel(Level.WARN)

    if (args.length < 4) {
      println("Usage: /path/to/spark/bin/spark-submit" +
              " --driver-memory 2g --class ItemALS " +
              " target/scala-*/kibitzer-assembly*.jar" +
              " /hdfs/path/to/DatasetDir TRAIN_FILENAME ITEMS_FILENAME /path/to/ratingsfile")
      sys.exit(1)
    }
    
    // load items and ratings
    val DatasetDir = args(0)
    val TRAIN_FILENAME = args(1)
    val ITEMS_FILENAME = args(2)

    // set up environment
    val conf = new SparkConf()
      .setAppName("ItemALS")
      .set("spark.executor.memory", "2g")
    val sc = new SparkContext(conf)

    // load personal ratings
    val myRatings = loadRatings(args(3))
    val myRatingsRDD = sc.parallelize(myRatings, 1)

    val items = sc.textFile(new File(DatasetDir, ITEMS_FILENAME).toString)
      .map { line =>
        val fields = line.split("::")
        // format: (itemId, itemName)
        (fields(0).toInt, fields(1))
    }.collect().toMap
    
    val ratings = sc.textFile(new File(DatasetDir, TRAIN_FILENAME).toString)
      .map { line =>
        val fields = line.split("::")
        // format: (timestamp % 10, Rating(userId, itemId, rating))
        (fields(3).toLong % 10, Rating(fields(0).toInt, fields(1).toInt, fields(2).toDouble))
    }

    val numRatings = ratings.count()
    val numUsers = ratings.map(_._2.user).distinct().count()
    val numItems = ratings.map(_._2.product).distinct().count()

    println("Got " + numRatings + " ratings from "
      + numUsers + " users on " + numItems + " items.")

    // split ratings into train (60%), validation (20%), and test (20%) based on the 
    // last digit of the timestamp, add myRatings to train, and cache them
    val numPartitions = 4
    val training = ratings.filter(x => x._1 < 6)
      .values
      .union(myRatingsRDD)
      .repartition(numPartitions)
      .cache()
    val validation = ratings.filter(x => x._1 >= 6 && x._1 < 8)
      .values
      .repartition(numPartitions)
      .cache()
    val test = ratings.filter(x => x._1 >= 8).values.cache()

    val numTraining = training.count()
    val numValidation = validation.count()
    val numTest = test.count()

    if(args.length > 4) {
      println("Training: " + numTraining + ", validation: " + numValidation + ", test: " + numTest)
    }

    // train models and evaluate them on the validation set
    val ranks = List(8, 12)
    val lambdas = List(0.1, 10.0)
    val numIters = List(10, 20)
    var bestModel: Option[MatrixFactorizationModel] = None
    var bestValidationRmse = Double.MaxValue
    var bestRank = 0
    var bestLambda = -1.0
    var bestNumIter = -1
    for (rank <- ranks; lambda <- lambdas; numIter <- numIters) {
      val model = ALS.train(training, rank, numIter, lambda)
      val validationRmse = computeRmse(model, validation, numValidation)
      if(args.length > 4) {
        println("RMSE (validation) = " + validationRmse + " for the model trained with rank = " 
          + rank + ", lambda = " + lambda + ", and numIter = " + numIter + ".")
      }
      if (validationRmse < bestValidationRmse) {
        bestModel = Some(model)
        bestValidationRmse = validationRmse
        bestRank = rank
        bestLambda = lambda
        bestNumIter = numIter
      }
    }

    // evaluate the best model on the test set
    val testRmse = computeRmse(bestModel.get, test, numTest)

    if(args.length > 4) {
      println("The best model was trained with rank = " + bestRank + " and lambda = " + bestLambda
        + ", and numIter = " + bestNumIter + ", and its RMSE on the test set is " + testRmse + ".")
    }

    // create a naive baseline and compare it with the best model
    val meanRating = training.union(validation).map(_.rating).mean
    val baselineRmse = 
      math.sqrt(test.map(x => (meanRating - x.rating) * (meanRating - x.rating)).mean)
    val improvement = (baselineRmse - testRmse) / baselineRmse * 100
    if(args.length > 4) {
      println("The best model improves the baseline by " + "%1.2f".format(improvement) + "%.")
    }

    // make personalized recommendations
    val myRatedItemIds = myRatings.map(_.product).toSet
    val candidates = sc.parallelize(items.keys.filter(!myRatedItemIds.contains(_)).toSeq)
    val recommendations = bestModel.get
      .predict(candidates.map((0, _)))
      .collect()
      .sortBy(- _.rating)
      .take(50)

    // print the output
    var i = 1
    println("result is empty? " + recommendations.isEmpty)
    println("Top 50 Items recommended for you:")
    recommendations.foreach { r =>
      println("%2d".format(i) + ": " + items(r.product))
      i += 1
    }

    // clean up
    sc.stop()
  }

  /**
   * Compute RMSE (Root Mean Squared Error).
   */
  def computeRmse(model: MatrixFactorizationModel, data: RDD[Rating], n: Long): Double = {
    val predictions: RDD[Rating] = model.predict(data.map(x => (x.user, x.product)))
    val predictionsAndRatings = predictions.map(x => ((x.user, x.product), x.rating))
      .join(data.map(x => ((x.user, x.product), x.rating)))
      .values
    math.sqrt(predictionsAndRatings.map(x => (x._1 - x._2) * (x._1 - x._2)).reduce(_ + _) / n)
  }

  /**
   * Load ratings from file.
   */
  def loadRatings(path: String): Seq[Rating] = {
    val lines = Source.fromFile(path).getLines()
    val ratings = lines.map { line =>
      val fields = line.split("::")
      Rating(fields(0).toInt, fields(1).toInt, fields(2).toDouble)
    }.filter(_.rating > 0.0)
    if (ratings.isEmpty) {
      sys.error("No ratings provided.")
    } else {
      ratings.toSeq
    }
  }
}
