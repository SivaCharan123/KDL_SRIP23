import sys
import os
import pandas as pd
from pydruid.db import connect

druid_host = "localhost"
druid_port = 8888
druid_path = "/druid/v2/sql"
druid_scheme = "http"

# Remove the extension
dataset_name = os.path.splitext(sys.argv[1])[0]
druid_query = 'SELECT * FROM "' + dataset_name + '"'
druid_connection = connect(host=druid_host, port=druid_port, path=druid_path, scheme=druid_scheme)
druid_cursor = druid_connection.cursor()
druid_cursor.execute(druid_query)
df = pd.DataFrame(druid_cursor.fetchall())
print(df.to_json(orient='records'))