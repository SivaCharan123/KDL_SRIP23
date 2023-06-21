#!/usr/bin/python3
import os
import sys
import settings
import logger
from druid_lib import *
from xlsx2csv import convert2CSV
from metadata_writer import WriteMetaData
try:
    dir_path = sys.argv[1]
    dir_names = os.listdir(settings.DATASETS_DIR + "/" + dir_path)

    # Array to store the following data: name of CSV file, base directory, and location of JSON
    upload_data = []

    # Loop through the directory to find CSV and XLSX files
    # Then append them to upload_data
    for filename in dir_names:
        full_file_path = dir_path + "/" + filename
        if(filename.endswith(".csv")):
            # If file is csv, directly upload to Druid.
            upload_data.append([filename,  os.getcwd() + "/" + settings.DATASETS_DIR + "/" + dir_path + "/", settings.DATASETS_DIR + "/" + dir_path + "/" + filename + ".json"])
        elif(filename.endswith(".xlsx") or filename.endswith(".xls")):
            #B If file is xlsx or xls, convert to csv to upload to Druid
            #converted_csv_path = full_file_path.replace(".xlsx", ".csv").replace(".xls", ".csv")
            #filename_csv = filename.replace(".xlsx", ".csv").replace(".xls", ".csv")
            #os.system("/usr/bin/python3 xlsx2csv.py \"" + settings.DATASETS_DIR + "/" + full_file_path + "\" \"" + settings.DATASETS_DIR + "/" + converted_csv_path + "\"")
            logger.Log(f"Converting {filename} to CSV....")
            converted_filenames = convert2CSV(settings.DATASETS_DIR + "/" + dir_path, filename)
            for filename_csv in converted_filenames:
                upload_data.append([filename_csv, os.getcwd() + "/" + settings.DATASETS_DIR + "/" + dir_path + "/", settings.DATASETS_DIR + "/" + dir_path + "/" + filename_csv + ".json"])

    # Now loop through upload data, create JSON spec and upload.
    logger.Log("Batch upload started.")
    for upload in upload_data:
        logger.Log(f"Uploading {upload[0]}")
        WriteMetaData(upload[0])
        json_object = get_druid_json(upload[0], upload[1], upload[2])
        ingest_to_druid(json_object,f"{settings.DRUID_SERVERLOC}:{settings.DRUID_PORT}/druid/indexer/v1/task")
except:
    logger.LogException()