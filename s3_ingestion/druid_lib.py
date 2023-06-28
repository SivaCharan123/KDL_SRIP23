from datetime import date 
import pandas as pd
import numpy as np
import json
import requests
from requests.api import request

def get_druid_json(file_name, file_path, json_file_name):
    '''
        This method reads the CSV file that needs to be uploaded and create a JSON spec for the required upload.
        Arguments:
            -> The name of the CSV file - String
            -> The path to the CSV file for Druid - String
            -> The name of the JSON file where the spec is saved - String
        Returns:
            -> JSON spec for Druid - JSON object
    '''
    # Reading the file and the columns
    df = pd.read_csv(file_path + "/" + file_name, delimiter=',')
    column_list = list(df.columns)

    # Initiating a dictionary in the format of an ingestion spec
    current_date = date.today().strftime("%Y-%m-%d") + "T00:00:00Z"
    ingest_schema = {"type" : "index_parallel","spec" : {"type" : "index_parallel","dataSchema" : {}, "ioConfig" : {"type" : "index_parallel","inputSource" : {"type" : "local", "baseDir" : file_path, "filter" : file_name},"inputFormat" : {"type" : "csv", "findColumnsFromHeader" : True}}, "tuningConfig": {"type" : "index_parallel","partitionsSpec" : {"type" : "dynamic"}}}}
    ingest_schema['spec']['dataSchema']["dataSource"] = file_name.replace(".csv","")
    ingest_schema['spec']['dataSchema']["timestampSpec"] = {"column": "!!!_no_such_column_!!!","missingValue": current_date}
    ingest_schema['spec']['dataSchema']["granularitySpec"] = {"type": "uniform", "segmentGranularity": "DAY", "queryGranularity": "NONE", "rollup": False}
    ingest_schema['spec']['dataSchema']["dimensionsSpec"] = {"dimensions" : []}

    # The columns over here are saved in the type of a string
    for i in column_list:
        if(df.dtypes[i]==np.int64):
            ingest_schema['spec']['dataSchema']["dimensionsSpec"]["dimensions"].append({"type" : "long", "name" : i})
        elif(df.dtypes[i]==np.float64):
            ingest_schema['spec']['dataSchema']["dimensionsSpec"]["dimensions"].append({"type" : "double", "name" : i})
        else:
            ingest_schema['spec']['dataSchema']["dimensionsSpec"]["dimensions"].append(i)
    
    # Converting the dictionary into a JSON object and writing it into a file
    json_object = json.dumps(ingest_schema,indent=4)
    print(f"Creating JSON spec for {file_name} in {json_file_name}")
    with open(json_file_name,'w') as outfile:
        outfile.write(json_object)

    return json_object

def ingest_to_druid(json_obj,url):
    '''
        This method runs an API request to Druid for posting the ingestion spec in the form of a JSON object.
        Arguments:
            -> JSON spec for Druid - JSON object
            -> The URL to Druid for making the POST request - String
    '''
    headers = {"Content-Type" : "application/json"}
    response = requests.post(url, headers=headers, data=json_obj)
    if(response.status_code==200):
        print(f"Druid Task Submitted.")
    else:
        print(f"Druid Task Failed.")
    
    task_id = response.json()['task']

    print("Waiting for Druid Task Report...")
    while True:
        status = check_task_status(url, task_id)
        if(status == "SUCCESS"):
            print("Druid Task Suceeded.")
            return True

        if(status != None and status != ""):
            for i in range(0, 5):
                print(check_task_status(url, task_id))
            print(status)
            print("Druid Task Failed.")
            return False
def check_task_status(url, task_id):
    try:
        spec = requests.get(url + "/" + task_id + "/reports").json()
        if(spec["ingestionStatsAndErrors"]["payload"]["ingestionState"] == "COMPLETED"):
            return "SUCCESS"
        else:
            return spec["ingestionStatsAndErrors"]["payload"]["errorMsg"]
    except:
        return None