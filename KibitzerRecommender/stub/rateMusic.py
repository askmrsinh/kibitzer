#!/usr/bin/env python

import sys
from os import remove, removedirs
from os.path import dirname, join, isfile
from time import time

topMovies = ("681,music_artist-http://dbpedia.org/resource/Eminem\n"
             "682,music_artist-http://dbpedia.org/resource/Lady_Gaga\n"
             "683,music_band-http://dbpedia.org/resource/Linkin_Park\n"
             "684,music_artist-http://dbpedia.org/resource/Michael_Jackson\n"
             "685,music_artist-http://dbpedia.org/resource/Bob_Marley\n"
             "686,music_artist-http://dbpedia.org/resource/The_Beatles\n"
             "687,music_artist-http://dbpedia.org/resource/Katy_Perry\n"
             "688,music_band-http://dbpedia.org/resource/Paramore\n"
             "689,music_artist-http://dbpedia.org/resource/Lil_Wayne\n"
             "6810,music_artist-http://dbpedia.org/resource/Rihanna\n"
             "6812,music_band-http://dbpedia.org/resource/Metallica")

timeStamp = int(time())
parentDir = dirname(dirname(__file__))
ratingsFile = join(parentDir, "personalRatingsMusic-" + str(timeStamp) + ".txt")

if isfile(ratingsFile):
  r = raw_input("Looks like you've already rated the music. Overwrite ratings (y/N)? ")
  if r and r[0].lower() == "y":
    remove(ratingsFile)
  else:
    sys.exit()

prompt = "Please rate the following music (1-5 (best), or 0 if not heard): "
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

