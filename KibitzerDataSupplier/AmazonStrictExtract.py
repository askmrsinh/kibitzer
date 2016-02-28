#!/usr/bin/env python2

import json
import gzip

def parse(path):
  g = gzip.open(path, 'r')
  for l in g:
    yield json.dumps(eval(l))
    
f = open("../data/Amazon/meta_Amazon_Instant_Video.json", 'w')
for l in parse("../data/Amazon/meta_Amazon_Instant_Video.json.gz"):
  f.write(l + '\n')
  
f = open("../data/Amazon/meta_Books.json", 'w')
for l in parse("../data/Amazon/meta_Books.json.gz"):
  f.write(l + '\n')
  
f = open("../data/Amazon/meta_CDs_and_Vinyl.json", 'w')
for l in parse("../data/Amazon/meta_CDs_and_Vinyl.json.gz"):
  f.write(l + '\n')
  
f = open("../data/Amazon/metadata.json", 'w')
for l in parse("../data/Amazon/metadata.json.gz"):
  f.write(l + '\n')
  
f = open("../data/Amazon/meta_Digital_Music.json", 'w')
for l in parse("../data/Amazon/meta_Digital_Music.json.gz"):
  f.write(l + '\n')
  
f = open("../data/Amazon/meta_Movies_and_TV.json", 'w')
for l in parse("../data/Amazon/meta_Movies_and_TV.json.gz"):
  f.write(l + '\n')
  
f = open("../data/Amazon/reviews_Amazon_Instant_Video.json", 'w')
for l in parse("../data/Amazon/reviews_Amazon_Instant_Video.json.gz"):
  f.write(l + '\n')
  
f = open("../data/Amazon/reviews_Books.json", 'w')
for l in parse("../data/Amazon/reviews_Books.json.gz"):
  f.write(l + '\n')
  
f = open("../data/Amazon/reviews_CDs_and_Vinyl.json", 'w')
for l in parse("../data/Amazon/reviews_CDs_and_Vinyl.json.gz"):
  f.write(l + '\n')
  
f = open("../data/Amazon/reviews_Digital_Music.json", 'w')
for l in parse("../data/Amazon/reviews_Digital_Music.json.gz"):
  f.write(l + '\n')
  
f = open("../data/Amazon/reviews_Movies_and_TV.json", 'w')
for l in parse("../data/Amazon/reviews_Movies_and_TV.json.gz"):
  f.write(l + '\n')
