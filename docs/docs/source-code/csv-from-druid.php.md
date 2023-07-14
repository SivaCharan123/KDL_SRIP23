# CSV-FROM-DRUID.PHP

## Function

Web interface to `csv_from_druid.py`.

## Working

It accepts the query as a POST request with "query" as the query and forwards it to `csv_from_druid.py`. It returns the JSON outputted by `csv_from_druid.py` to the client.

## Used By

- `retrieval.php`