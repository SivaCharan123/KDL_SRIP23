import os
import sys
import pydruid_helper
import logger

try:
    # Remove the extension
    dataset_name = os.path.splitext(sys.argv[1])[0]
    druid_query = 'SELECT * FROM "' + dataset_name + '" LIMIT 100'
    df = pydruid_helper.ExecuteSQLQuery(druid_query)
    df = df.drop(['KDL_METADATA'], axis=1)
    print(df.to_json(orient='records'))
except:
    logger.LogException()