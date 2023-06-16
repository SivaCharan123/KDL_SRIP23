#!/usr/bin/python3
# This script converts an XLS/XLSX file to a CSV file.

import pandas
import sys
import traceback
import logger

if(len(sys.argv) != 3):
    print("Usage: xlsx2csv.py [XLSX NAME] [OUTPUT CSV NAME]")
    exit(-1)

try:
    xlsx = pandas.read_excel(sys.argv[1])
    xlsx.to_csv(sys.argv[2], encoding='utf-8', index=False)
    logger.Log(f"Converted {sys.argv[1]} to {sys.argv[2]}")
except:
    logger.LogException()
