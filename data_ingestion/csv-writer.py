#!/usr/bin/python3
# Usage: csv-writer 'Name' 'Year' 'Description' 'SDG Flags' 'File Name'
from csv import writer
import sys

if(len(sys.argv) != 6):
    print("Usage: csv-writer 'Name' 'Year' 'Description' 'SDG Flags' 'File Name'")
    exit(-1)

new_row = [sys.argv[1], int(sys.argv[2]), sys.argv[3], int(sys.argv[4]), sys.argv[5]]

with open('dataset-catalog.csv', 'a') as catalog:
    writer_object = writer(catalog)
    writer_object.writerow(new_row)
    catalog.close()

