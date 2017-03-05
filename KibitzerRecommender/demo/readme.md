# compile using:

```
$ sbt assembly
```


# generate and put the demo dataset on hdfs

```
$ start-hadoop
$ ../../data/tmp/generate_demo_dataset.sh
$ hadoop fs -mkdir /demo; hadoop fs -put ../../data/tmp/*.dat /demo
```


# run

``` 
$ spark-submit --driver-memory 8g --class ItemSimilarity target/scala-2.10/kibitzer-assembly-0.1.jar /demo/ demo_training_likes_bo_mo_mu.dat demo_items_bo_mo_mu.dat "book-http://dbpedia.org/resource/Harry_Potter"

$ spark-submit --driver-memory 8g --class ItemALS target/scala-2.10/kibitzer-assembly-0.1.jar /demo/ demo_training_likes_bo_mo_mu.dat demo_items_bo_mo_mu.dat personalRatings.txt
```
