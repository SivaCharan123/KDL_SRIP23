# DRUID_LIB.PY

## Function

It contains the two major functions `get_druid_json()` and `ingest_to_druid()` which are used by the ingestion pipeline to forward CSVs to the Druid cluster.

## Working

The original file was contributed by Phani on the WSL-CADS-CODR repository in 2021 as `ingest.py`. I only modified parts of the code and made it usable in a general purpose scenario.

`get_druid_json(file_name, file_path, json_file_name)` creates a JSON spec that is required to be send to the Druid API with a POST request. It takes in a CSV file name, directory of the CSV, and the JSON file name to be created. It creates the JSON containing the required Druid spec (mostly the data types of every column, and a few other things). It then returns the JSON object.

`ingest_to_druid(json_object)` sends that POST request to the Druid cluster.

## Used By

- upload_batch_druid.py
- upload_single_druid.py