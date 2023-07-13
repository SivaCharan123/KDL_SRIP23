# UPLOAD_SINGLE_DRUID.PY

## Function

Sends a single XLSX/CSV file to Druid instance.

## Working

It takes in the argument as a directory, and iterates over the items in that directory.

- If it is a CSV file, then it uses `druid_lib.py`'s functions to create a JSON spec, then calls `WriteMetaData()` to write it, and sends it to Druid using `ingest_to_druid()`.

- If it is an XLSX file, then it uses `xlsx2csv.py`'s `convert2CSV()` which returns an array of CSV file names corresponding to every Excel sheet, and then calls `WriteMetaData()` every time a CSV is sent to Druid.

- All other files are ignored.

## Used By

- `upload-batch.php`