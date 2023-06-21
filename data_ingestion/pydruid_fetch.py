import sys
import os
import pandas as pd
import settings
from pydruid.db import connect

# Remove the extension
dataset_name = os.path.splitext(sys.argv[1])[0]
druid_query = 'SELECT * FROM "' + dataset_name + '"'
druid_connection = connect(host=settings.DRUID_SERVERLOC, port=settings.DRUID_PORT, path=settings.DRUID_SQL_PATH, scheme=settings.DRUID_PROTOCOL)
druid_cursor = druid_connection.cursor()
druid_cursor.execute(druid_query)
df = pd.DataFrame(druid_cursor.fetchall())
print(df.to_json(orient='records'))