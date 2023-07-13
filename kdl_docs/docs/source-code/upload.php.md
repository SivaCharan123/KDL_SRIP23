# UPLOAD.PHP

## Function

This is the first part of the ingestion mechanism. After the files and metadata from the user is received from the `form-upload.php`, for a single file upload (CSV/XLSX) this file is invoked.

## Working

The first thing that it does is validate the data, whether SDGs have been checked, name, year, and other details have been provided. 

Next, it creates something called "SDG Flags" (`sdg_flags`). As there are 17 Sustainable Development Goals, the most efficient way to store all SDGs is by creating a number where bits 1-17 will specify whether the relevant SDG applies to the data set or not. This "SDG Flag" is used by all utilities.

Then it checks for the file size (50 MB limit imposed), and uses PHP's `move_uploaded_file()` to move it from the temporary POST storage to the `datasets/` directory. It also adds a UNIX timestamp in front of the filename to preserve its uniqueness. Next, it creates a file called `tmp.meta`, which contains all the metadata (SDG Flags, Year, Name, Description, etc.) that will be read by `metadata_writer.py`.

Finally, it calls `upload_single_druid.py` with the given filename.