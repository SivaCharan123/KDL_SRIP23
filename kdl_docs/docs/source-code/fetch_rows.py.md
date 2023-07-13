# FETCH_ROWS.PY

## Function

This is a Python script that fetches the first 100 rows. 

## Working

It sends a `SELECT * from sys.argv[1]` query to the local Druid instance using `pydruid_helper.py`. Then outputs a dataframe ordered by records turned into a JSON string that is read by PHP as return value of `shell_exec()`.

## Used By

* `fetch.php` uses this script and turns the output into an HTML table which is sent to the client.