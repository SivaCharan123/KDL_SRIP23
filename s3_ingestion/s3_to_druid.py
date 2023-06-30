import s3
import os
import pandas as pd
import tkinter.messagebox as tmb
import traceback
from threading import *
from druid_lib import *

downloaded_files = []

def download_directory(s3_client, s3info, directory):
    files = s3.ExploreS3Directory(s3_client, s3info, directory + "/")
    for file in files:
        if(file.type == 'file'):
            downloaded_files.append(s3.DownloadS3File(s3_client, s3info, directory + "/" + file.name))
        elif(file.type == 'directory'):
            download_directory(s3_client, s3info, directory + "/" + file.name)

def s3_to_druid(s3_client, s3info, druidinfo, files):
    global downloaded_files
    downloaded_files = []
    # First download files
    for file in files:
        if(file.type == 'file'):
            downloaded_files.append(s3.DownloadS3File(s3_client, s3info, file.name))
        elif(file.type == 'dir'):
            download_directory(s3_client, s3info, file.name)

    druid_url = f"{druidinfo.Druid_protocol}://{druidinfo.Druid_host}:{druidinfo.Druid_port}{druidinfo.Druid_taskpath}"
    # Now iterate through the downloaded files for CSVs.
    try:
        status = None
        for file in downloaded_files:
            if(file.endswith(".csv")):
                print(f"Uploading {file} to Druid Server at {druid_url}")
                df = pd.read_csv(file)
                df.columns = [col.replace(',', '') for col in df.columns]

                # Extract first 10 columns for debugging purposes.
                #first_10 = df.head(10)
                #file = os.path.splitext(file)[0] + "_FIRST10" + ".csv"
                #first_10.to_csv(file)

                df.to_csv(file)
                json_object = get_druid_json(os.path.basename(file), os.path.dirname(file), os.path.dirname(file) + "/" + os.path.basename(file) + ".json")
                status = ingest_to_druid(json_object, druid_url)

        if(status):
            tmb.showinfo("S32Druid", "All files were sent to Druid successfully!")
        else:
            tmb.showerror("S32Druid", "Failed to send to Druid. Please see terminal for error messages.")
    except:
        tmb.showerror("S32Druid", traceback.format_exc())
        