#!/usr/bin/python3
# Usage: csv-writer 'Name' 'Year' 'Description' 'SDG Flags' 'File Name'
from csv import writer
import sys
import settings
import logger

if(len(sys.argv) != 6):
    logger.Log("Invalid usage of csv_writer. Usage: csv_writer 'Name' 'Year' 'Description' 'SDG Flags' 'File Name'")
    exit(-1)

try:
    new_row = [sys.argv[1], int(sys.argv[2]), sys.argv[3], int(sys.argv[4]), sys.argv[5]]
    logger.Log(f"Writing metadata for {sys.argv[1]}")
    with open(settings.METADATA_CSV, 'a') as catalog:
        writer_object = writer(catalog)
        writer_object.writerow(new_row)
        catalog.close()
except:
    logger.LogException()
    

