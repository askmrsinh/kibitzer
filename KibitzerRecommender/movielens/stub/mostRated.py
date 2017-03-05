#!/usr/bin/env python

import sys
import csv
import StringIO
from collections import Counter

trainingFile = open(str(sys.argv[1]), 'r')
itemsFile = open(str(sys.argv[2]), 'r')

training_stream = StringIO.StringIO(trainingFile.read())
items_stream = StringIO.StringIO(itemsFile.read())

training_reader = csv.reader(training_stream, delimiter='\t')
items_reader = csv.reader(items_stream, delimiter='\t')

itemID = [row[1] for row in training_reader]

itemID_to_count = (word for word in itemID)
c = Counter(itemID_to_count).most_common(int(sys.argv[3]))

top_itemIDs = [x[0] for x in c]

for feild in items_reader:
  if feild[0] in top_itemIDs:
    print (feild[0] + "," + feild[1])

