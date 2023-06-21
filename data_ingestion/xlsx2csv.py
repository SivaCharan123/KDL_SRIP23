#!/usr/bin/python3
# This script converts an XLS/XLSX file to a CSV file.

import os
import pandas
import sys
import logger

# Takes an XLSX file
# Returns the names of converted CSVs
def convert2CSV(xlsx_dir, xlsx_name):
    try:
        xlsx_file = pandas.ExcelFile(xlsx_dir + "/" + xlsx_name)
        xlsx_sheets = pandas.read_excel(xlsx_file, None)
        xlsx_name_without_ext = os.path.splitext(xlsx_name)[0]
        sheet_files = []

        for sheet in xlsx_sheets.keys():
            sheet_out_file = xlsx_name_without_ext + "_" + sheet + ".csv"
            xlsx_sheets[sheet].to_csv(xlsx_dir + "/" + sheet_out_file, encoding='utf-8', index=False)
            logger.Log(f"Converted sheet {xlsx_name_without_ext}.{sheet} to {sheet_out_file}")
            sheet_files.append(sheet_out_file)
        
        return sheet_files
    except:
        logger.LogException()

if __name__ == "__main__":
    if(len(sys.argv) != 3):
        logger.Log("Usage: xlsx2csv.py [XLSX NAME] [OUTPUT CSV NAME]")
        exit(-1)

    try:
        xlsx = pandas.read_excel(sys.argv[1])
        xlsx.to_csv(sys.argv[2], encoding='utf-8', index=False)
        logger.Log(f"Converted {sys.argv[1]} to {sys.argv[2]}")
    except:
        logger.LogException()
