#!/usr/bin/python3
import os
import sys
import settings
from druid_lib import *

if(len(sys.argv) != 2):
    print("Usage: upload-batch-druid.py <directory name>")
    exit(-1)

dir_path = sys.argv[1]
dir_names = os.listdir(settings.DATASETS_DIR + "/" + dir_path)

upload_data = []

for filename in dir_names:
    full_file_path = dir_path + "/" + filename
    if(filename.endswith(".csv")):
        upload_data.append([filename,  os.getcwd() + "/" + settings.DATASETS_DIR + "/" + dir_path + "/", settings.DATASETS_DIR + "/" + dir_path + "/" + filename + ".json"])
    elif(filename.endswith(".xlsx") or filename.endswith(".xls")):
        converted_csv_path = full_file_path.replace(".xlsx", ".csv").replace(".xls", ".csv")
        filename_csv = filename.replace(".xlsx", ".csv").replace(".xls", ".csv")
        os.system("/usr/bin/python3 xlsx-to-csv.py \"" + settings.DATASETS_DIR + "/" + full_file_path + "\" \"" + settings.DATASETS_DIR + "/" + converted_csv_path + "\"")
        upload_data.append([filename_csv, os.getcwd() + "/" + settings.DATASETS_DIR + "/" + dir_path + "/", settings.DATASETS_DIR + "/" + dir_path + "/" + filename_csv + ".json"])
    
for upload in upload_data:
    json_object = get_druid_json(upload[0], upload[1], upload[2])
    ingest_to_druid(json_object,f"{settings.DRUID_SERVERLOC}:{settings.DRUID_PORT}/druid/indexer/v1/task")
