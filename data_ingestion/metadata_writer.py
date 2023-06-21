from csv import writer
from datetime import datetime
import os
import settings
import logger

def WriteMetaData(uploaded_file):
    try:
        logger.Log(f"Writing metadata for {uploaded_file}.")
        # This file contains meta data taken from the form.
        meta_data = open("tmp.meta").read().splitlines()
        # Remove extension as files are named without extension in Druid
        uploaded_file = os.path.splitext(uploaded_file)[0]
        new_row = [meta_data[0], int(meta_data[1]), meta_data[2], int(meta_data[3]), uploaded_file, str(datetime.now())]
        with open(settings.METADATA_CSV, 'a') as catalog:
            writer_object = writer(catalog)
            writer_object.writerow(new_row)
            catalog.close()
    except:
        logger.LogException()