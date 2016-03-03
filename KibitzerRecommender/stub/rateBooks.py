#!/usr/bin/env python

import sys
from os import remove, removedirs
from os.path import dirname, join, isfile
from time import time

topMovies = ("261,book-http://dbpedia.org/resource/Harry_Potter\n"
             "262,book-http://dbpedia.org/resource/Twilight_(series)\n"
             "263,book-http://dbpedia.org/resource/Bible\n"
             "264,book-http://dbpedia.org/resource/Manga\n"
             "265,book-http://dbpedia.org/resource/The_Lord_of_the_Rings\n"
             "266,book-http://dbpedia.org/resource/Pride_and_Prejudice\n"
             "267,book-http://dbpedia.org/resource/The_Catcher_in_the_Rye\n"
             "269,book-http://dbpedia.org/resource/To_Kill_a_Mockingbird\n"
             "2612,book-http://dbpedia.org/resource/The_Hobbit\n"
             "2622,book-http://dbpedia.org/resource/The_Chronicles_of_Narnia\n"
             "2651,book-http://dbpedia.org/resource/Nineteen_Eighty-Four")

timeStamp = int(time())
parentDir = dirname(dirname(__file__))
ratingsFile = join(parentDir, "personalRatingsBooks-" + str(timeStamp) + ".txt")

if isfile(ratingsFile):
  r = raw_input("Looks like you've already rated the books. Overwrite ratings (y/N)? ")
  if r and r[0].lower() == "y":
    remove(ratingsFile)
  else:
    sys.exit()

prompt = "Please rate the following books (1-5 (best), or 0 if not read): "
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

