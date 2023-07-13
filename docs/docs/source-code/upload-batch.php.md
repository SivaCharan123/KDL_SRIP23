# UPLOAD-BATCH.PHP

## Function

This is the first part of the ingestion mechanism. After the files and metadata from the user is received from the `form-upload.php`, for batch file upload (CSV/XLSX) this file is invoked.

## Working
The first thing that it does is validate the data, whether SDGs have been checked, name, year, and other details have been provided. 

Next, it creates something called "SDG Flags" (`sdg_flags`). As there are 17 Sustainable Development Goals, the most efficient way to store all SDGs is by creating a number where bits 1-17 will specify whether the relevant SDG applies to the data set or not. This "SDG Flag" is used by all utilities.

Then it uses PHP's `move_uploaded_file()` to move all files uploaded from the temporary POST storage to the `datasets/<Data Set Name>/` directory. It also adds a UNIX timestamp in front of the directory to preserve its uniqueness. For every file uploaded, it adds a `$count` to preserve its uniqueness again, in case the user uploaded two files with identical names. As an example, if the user uploaded files as FILE1.CSV, and FILE2.XLSX, and gave the dataset name as "My Data Set", the files on the server would go in: datasets/16492329323_My Data Set/FILE1.CSV and datasets/16492329323_My Data Set/FILE2.XLSX.

Next, it creates a file called `tmp.meta`, which contains all the metadata (SDG Flags, Year, Name, Description, etc.) that will be read by `metadata_writer.py`.

Finally, it calls `upload_batch_druid.py` with the given directory name.