# CSV_FROM_DRUID.PY

## Function

Returns CSV data from Druid given query as argument.

## Working

It accepts the query as an argument and calls `ExecuteSQLQueryGetCSV()` `from pydruid_helper.py`.

If the query suceeded, the program prints:

`{ "Error": "", "CSV": "..." }`

If it fails, the program prints:

`[{ "Error": "Error Message", "Message": "Full Message from Druid" }]`

## Used By

- `csv-from-druid.php`