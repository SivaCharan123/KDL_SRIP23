import pydruid_helper
import json
import sys

error_json = { "Error": "Invalid query.", "Message": ""}

if(len(sys.argv) != 2):
    print(json.dumps(error_json))
else:
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