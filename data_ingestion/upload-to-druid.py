import settings
from druid_lib import *

if(len(sys.argv) < 2):
    print("[ERR]: Expected CSV file!")
    exit(-1)

print("[LOG] Python Script Started.")
print("[LOG] Loading CSV...", sys.argv[1])
csv_file_name = sys.argv[1]
json_object = get_druid_json(csv_file_name, os.getcwd() + "/" + settings.DATASETS_DIR, settings.DATASETS_DIR + "/" + csv_file_name + ".json")
ingest_to_druid(json_object,f"{settings.DRUID_SERVERLOC}:{settings.DRUID_PORT}/druid/indexer/v1/task")