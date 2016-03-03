#!/usr/bin/env python

import sys
from os import remove, removedirs
from os.path import dirname, join, isfile
from time import time

topMovies = ("661,movie-http://dbpedia.org/resource/Twilight_(2008_film)\n"
             "662,movie_actor-http://dbpedia.org/resource/Megan_Fox\n"
             "663,movie-http://dbpedia.org/resource/The_Hangover\n"
             "664,movie-http://dbpedia.org/resource/Harry_Potter_(film_series)\n"
             "665,movie-http://dbpedia.org/resource/Toy_Story\n"
             "666,movie_actor-http://dbpedia.org/resource/Will_Smith\n"
             "667,movie_actor-http://dbpedia.org/resource/Vin_Diesel\n"
             "668,movie-http://dbpedia.org/resource/Elf_(film)\n"
             "669,movie-http://dbpedia.org/resource/Alice_in_Wonderland_(2010_film)\n"
             "6610,movie_genre-http://dbpedia.org/resource/Children's_film\n"
             "6611,movie-http://dbpedia.org/resource/Finding_Nemo")

timeStamp = int(time())
parentDir = dirname(dirname(__file__))
ratingsFile = join(parentDir, "personalRatingsMovies-" + str(timeStamp) + ".txt")

if isfile(ratingsFile):
  r = raw_input("Looks like you've already rated the movies. Overwrite ratings (y/N)? ")
  if r and r[0].lower() == "y":
    remove(ratingsFile)
  else:
    sys.exit()

prompt = "Please rate the following movie (1-5 (best), or 0 if not seen): "
print prompt

n = 0

f = open(ratingsFile, 'w')
for line in topMovies.split("\n"):
  ls = line.strip().split(",")
  valid = False
  while not valid:
    rStr = raw_input(ls[1] + ": ")
    r = int(rStr) if rStr.isdigit() else -1
    if r < 0 or r > 5:
      print prompt
    else:
      valid = True
      if r > 0:
        f.write("0::%s::%d::%d\n" % (ls[0], r, timeStamp))
        n += 1
f.close()

if n == 0:
  print "No rating provided!"

