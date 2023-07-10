from datetime import datetime
import os
import logger
import json
import pandas

def WriteMetaData(uploaded_file, path):
    try:
        logger.Log(f"Writing metadata for {uploaded_file}.")
        # This file contains meta data taken from the form.
        meta_data = open("tmp.meta").read().splitlines()
        # Remove extension as files are named without extension in Druid
        uploaded_file = os.path.splitext(uploaded_file)[0]
        meta_data_json = { 'name': meta_data[0], 'year': int(meta_data[1]), 'description': meta_data[2], 'sdg_flags': int(meta_data[3]), 'filename': uploaded_file, 'upload_time': str(datetime.now()) } 
        df = pandas.read_csv(path + "/" + uploaded_file + ".csv")
        df["KDL_METADATA"] = [json.dumps(meta_data_json)] + ["N.A." for i in range(0, len(df.index) - 1)]
        df.to_csv(path + "/" + uploaded_file + ".csv")
        # Write to metadata.json
        metadata_file = open('metadata.json', 'r')
        existing_metadata = json.loads(''.join(metadata_file.readlines()))
        metadata_file.close()
        existing_metadata[uploaded_file] = meta_data_json
        f = open('metadata.json', 'w')
        f.write(json.dumps(existing_metadata))
        f.close()
        
    except:
        logger.LogException()