import pydruid_helper
import json
import sys
import csv

if(len(sys.argv) != 2):
    print('Expected csv file to output metadata')
    exit(-1)

druid_query = "SELECT * FROM \"INFORMATION_SCHEMA\".\"TABLES\" WHERE \"TABLE_SCHEMA\" = 'druid'"
tables = pydruid_helper.ExecuteSQLQuery(druid_query)["TABLE_NAME"]
metadata_fields = ['name', 'year', 'description', 'sdg_flags', 'filename', 'upload_time']
metadata_rows = []

for table in tables:
    data = {}
    try:
        query = "SELECT \"KDL_METADATA\" FROM" + f"\"{table}\" LIMIT 1"
        json_data = pydruid_helper.ExecuteSQLQuery(query)['KDL_METADATA'][0]
        data = json.loads(json_data)
    except:
        data = {"name": "#NOMETA", "year": "#NOMETA", "description": "#NOMETA", "sdg_flags": "#NOMETA", "filename": "#NOMETA", "upload_time": "#NOMETA"}
    
    metadata_rows.append([data['name'], data['year'], data['description'], data['sdg_flags'], table, data['upload_time']])

with open(sys.argv[1], "w") as out: 
    csvwriter = csv.writer(out)
    csvwriter.writerow(metadata_fields)
    csvwriter.writerows(metadata_rows)
