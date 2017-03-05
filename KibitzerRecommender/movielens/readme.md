# compile using:

```
$ sbt assembly
```


# download [ml-100k](https://grouplens.org/datasets/movielens/100k/) & [ml-large](https://github.com/databricks/spark-training/tree/master/data/movielens/large) datasets and put them on hdfs

```
$ start-hadoop
$ hadoop fs -mkdir /ml-100k; hadoop fs -put path/to/ml-100k/* /ml-100k
$ hadoop fs -mkdir /ml-large; hadoop fs -put path/to/ml-large/* /ml-large
```


# run

````
$ spark-submit --driver-memory 8g --class ItemSimilarity target/scala-2.10/kibitzer-assembly-0.1.jar /movielens/ml-100k/ ua.base u.item "Star Wars (1977)"

$ spark-submit --driver-memory 8g --class ItemALS target/scala-2.10/kibitzer-assembly-0.1.jar /movielens/large/ ratings.dat movies.dat personalRatings.txt
```
