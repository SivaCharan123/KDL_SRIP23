import settings
import logger
from druid_lib import *
from xlsx2csv import convert2CSV
from metadata_writer import WriteMetaData

if(len(sys.argv) < 2):
    print("[ERR]: Expected CSV file!")
    exit(-1)

try:
    csv_file_name = sys.argv[1]
    upload_data = [csv_file_name]
    if(csv_file_name.endswith(".xlsx") or csv_file_name.endswith(".xls")):
        upload_data = convert2CSV(settings.DATASETS_DIR, csv_file_name)

    logger.Log(f"Uploading file: {csv_file_name}")
    for file in upload_data:
        WriteMetaData(file)
        json_object = get_druid_json(file, os.getcwd() + "/" + settings.DATASETS_DIR, settings.DATASETS_DIR + "/" + file + ".json")
        ingest_to_druid(json_object,f"{settings.DRUID_SERVERLOC}:{settings.DRUID_PORT}/druid/indexer/v1/task")
        

except:
    logger.LogException()