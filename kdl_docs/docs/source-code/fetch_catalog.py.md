# FETCH_CATALOG.PY

## Function

It fetches the data catalog along with the metadata from the Druid cluster and `metadata.json` into a CSV file `dataset-catalog.csv` which is then read by `data-catalog.php`.

## Working

First it sends the query `SELECT * FROM "INFORMATION_SCHEMA"."TABLES" WHERE "TABLE_SCHEMA" = 'druid'` to the cluster, this returns all the sets of tables loaded into the cluster. Then, it iterates through each to find whether the column `KDL_METADATA` exists as the metadata writer writes the first row into this column as a JSON object containing the metadata, if it does not, then it looks for a JSON entry in the local `metadata.json`. It then adds this row to the pandas DataFrame which is finally outputted as `dataset-catalog.csv`.

!!! warning
    It seems that KDL_METADATA row is almost always discarded by Druid. So, it is advisable to keep the `metadata.json` intact, and it will likely be the only copy of the metadata for the tables.

## Used By

- `data-catalog.php`