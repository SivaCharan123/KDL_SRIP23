import pydruid_helper
import json
import sys

error_json = [{ "Error": "Invalid query.", "Message": ""}]

if(len(sys.argv) != 2):
    print(json.dumps(error_json))
else:
    query_list = pydruid_helper.ExecuteSQLQueryGetJSON(sys.argv[1])
    try:
        query_dict = {f"{i}" : query_list[i] for i in range(len(query_list)) }
        print(json.dumps(query_dict))
    except:
        error_json[0]["Error"] = query_list["error"]
        error_json[0]["Message"] = query_list["errorMessage"]
        print(json.dumps(error_json))