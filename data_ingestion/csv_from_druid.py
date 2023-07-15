import pydruid_helper
import json
import sys
import logger

# If Druid itself fails
druid_error_json = { "Error": "DRUID_STATUS_NOT_OK", "Message": 
                    "There was an error querying Druid. The Druid service has stopped\
                    responding or the service has not started yet.\
                    The exception has been logged. Inform site administrator. This should not happen." }

# For query errors
error_json = { "Error": "", "Message": ""}

if(len(sys.argv) != 2):
    print(json.dumps(error_json))
else:
    try:
        result = pydruid_helper.ExecuteSQLQueryGetCSV(sys.argv[1])
        try:
            # If its a JSON, it means the query error'd out
            # As Druid simply returns the CSV as response.text 
            # from ExecuteSQLQueryGetCSV.
            check_for_error = json.loads(result)
            error_json["Error"] = check_for_error["error"]
            error_json["Message"] = check_for_error["errorMessage"]
            print(json.dumps([error_json]))
        except:
            result_json = { "Error": "", "CSV": result }
            print(json.dumps(result_json))
    except:
        logger.LogException()
        print(json.dumps([druid_error_json]))