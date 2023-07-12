import pydruid_helper
import json

try:
    task_list = pydruid_helper.ExecuteSQLQueryGetJSON("SELECT * from sys.tasks")
    task_dict = {f"{i}" : task_list[i] for i in range(len(task_list)) }
    print(json.dumps(task_dict))
except:
    print("")