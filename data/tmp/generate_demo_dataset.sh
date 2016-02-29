#!/usr/bin/env bash

curl "http://sisinflab.poliba.it/events/lod-recsys-challenge-2015/eswc2015-lod-recsys-challenge-v1.0-TRAINING.zip" -o demo_dataset.zip

unzip demo_dataset.zip; rm -rf demo_dataset.zip
cd eswc2015-lod-recsys-challenge-v1.0-TRAINING; mv items* training* ../;  cd ../; rm -rf eswc2015-lod-recsys-challenge-v1.0-TRAINING

sed -i '1d' items_*.dat
gawk -i inplace -F "\t" '{print $1"\t"$2"-"$3}' items_*.dat
gawk -i inplace -F "\t" '{gsub("bo","26",$1)}1' items_books.dat
gawk -i inplace -F "\t" '{gsub("mo","66",$1)}1' items_movies.dat
gawk -i inplace -F "\t" '{gsub("mu","68",$1)}1' items_music.dat
gawk -i inplace -F "\t" '{gsub("u","",$1);gsub("bo","26",$2)}1' training_likes_books.dat
gawk -i inplace -F "\t" '{gsub("u","",$1);gsub("mo","66",$2)}1' training_likes_movies.dat
gawk -i inplace -F "\t" '{gsub("u","",$1);gsub("mu","68",$2)}1' training_likes_music.dat

give_random_ratings () {
  while read line
  do
    RAND_RATING=$(shuf -i 1-5 -n 1)
    RAND_TIMESTAMP=$(shuf -i 789652009-1231131736 -n 1)
    printf "%s\t%s\t%s\n" "$line" "$RAND_RATING" "$RAND_TIMESTAMP"
  done < "$1" >> demo_$1
}

give_random_ratings training_likes_books.dat &
give_random_ratings training_likes_movies.dat &
give_random_ratings training_likes_music.dat &
wait

cat items_* >> demo_items_bo_mo_mu.dat
cat demo_training_likes_* >> demo_training_likes_bo_mo_mu.dat
cat items_books.dat items_movies.dat >> demo_items_bo_mo.dat
cat demo_training_likes_books.dat demo_training_likes_movies.dat >> demo_training_likes_bo_mo.dat
cat items_books.dat items_music.dat >> demo_items_bo_mu.dat
cat demo_training_likes_books.dat demo_training_likes_music.dat >> demo_training_likes_bo_mu.dat
cat items_movies.dat items_music.dat >> demo_items_mo_mu.dat
cat demo_training_likes_movies.dat demo_training_likes_music.dat >> demo_training_likes_mo_mu.dat

#cleanup
rm -rf training_*
mv items_books.dat demo_items_books.dat
mv items_movies.dat demo_items_movies.dat
mv items_music.dat demo_items_music.dat
sed -i -e 's/ /\t/g' *.dat
