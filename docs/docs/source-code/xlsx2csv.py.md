# XLSX2CSV.PY

## Function

If an XLSX file is uploaded, two things need to be done:

1. Convert the XLSX to CSV.
2. Turn each sheet of the XLSX into a separate CSV to be sent to Druid.

## Working

It contains a function called `convert2CSV` which takess a directory name, and the Excel file name, and then iterates over its sheets turning every sheet into a separate CSV. The sheet names are appended to the filename with an underscore to generate a new CSV file name. It then turns an array containing the names of all CSVs outputted.

As an example, if the input is "FILE.XLSX" with "SHEET1" and "SHEET2", `convert2CSV` will create two files, "FILE_SHEET1.CSV" and "FILE_SHEET2.CSV", and return the Pythonic array `["FILE1_SHEET1.CSV", "FILE2_SHEET2.CSV"]`.

## Used By

- `upload_single_druid.py`
- `upload_batch_druid.py`
