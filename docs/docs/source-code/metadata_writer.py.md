# METADATA_WRITER.PY

## Function

Writes the metadata into a separate column called `KDL_METADATA` into a CSV and stores a backup in `metadata.json`.

## Working

Both `upload.php` and `upload-batch.php` write a temporary file called `tmp.meta` which contains the metadata (Name, Year, SDG Checkbox Data, etc.) entered by the user. This turns it into a Python dict, and attaches it to the CSV being sent by adding a column called `KDL_METADATA` and adds the entry to the local `metadata.json` as well. Both are read by `fetch_catalog.py`.

!!! warning
    It seems that KDL_METADATA row is almost always discarded by Druid. So, it is advisable to keep the `metadata.json` intact, and it will likely be the only copy of the metadata for the tables.
    
## Used By

- `upload_single_druid.py`
- `upload_batch_druid.py`