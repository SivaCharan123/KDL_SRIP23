# Data Ingestion for Druid

Upload Form (CSV/XLSX, Single) | Upload Form (Batch)
---|---|
![](doc/img/upload-form.png)  |  ![](doc/img/upload-form-dump.png)|

This folder contains the following files and directories, 

* `form-upload.php` : The page which contains an upload form that takes CSV/XLSX files, written in PHP, HTML and Bootstrap 5.

* `upload-batch.php` : The backend code for batch (multiple file) upload.

* `upload.php` : The PHP code that is run after the submit button is clicked on the upload form. It does the following things: first moves the file from POST to `datasets/` directory, then creates a `.meta` file which contains information retrived from the form in XML format, then executes a script called `upload-to-druid.py` which creates a `.json` file for Druid ingestion and sends the request to the Druid server.

* `upload-to-druid.py` : The Python code for Druid ingestion (single file).

* `upload-batch-druid.py` : The Python code to ingest a directory by going through CSV/XLSX files for a batch upload

* `druid_lib.py` : Contains relevant functions to be used for Druid ingestion

* `dataset-catalog.csv` : Contains metadata of the uploaded files.

* `csv-writer.py` : Used to write metadata to CSV.

* `settings.py` : The settings (such as default Druid server and port location, along with the default datasets directory). 

* `styles/` : Contains CSS stylesheets used by the webpages

* `datasets/` : Local storage for datasets uploaded.

## Running

* Ensure you are on WSL / Ubuntu and have VS Code running.

* Download the PHP server extension for VS Code.

* Host `index.php` by right clicking in the code editor and selecting `Serve Webpage` to `http://localhost:3000` by default.

* Run Apache Druid on your machine, by default the Druid web server listens to `http://localhost:8888`, if not, modify the `settings.py` file.

* Upload a CSV file using the form and test!

## Form metadata

The form meta data is stored in the file `UNIXTIMESTAMP_filename.csv.meta`. It is in XML format with the following tags,

- `<dataset> ... </dataset>`: Opening and closing.

- `<name> ... </name>`: Name of the data set.

- `<year> ... </year>`: Year corresponding to the data set.

- `<desc> ... </desc>`: Description of the data set.

- `<sdg> NUMBER </sdg>`: A bitwise encoding of the SDG goals relevant to the data set. Bits 0 - 16 stand for SDG goals 1-17 respectively as per UN. 

## TODO

- [X] Add SDG goals in the form. 

- [X] Sanity checks in the form.

- [ ] Data cleaning before ingestion.

- [X] Implement XLSX/PDF formats.

- [ ] Send form meta data to Druid.

- [ ] Append taluka codes for district based data.

- [ ] Classification of dataset based on column labels.

- [X] Implement a metadata CSV file for easier access.

- [X] Implement a batch upload dump form.

- [ ] Integrate with SSH.

- [ ] Embed to WP.

- [ ] Better error reporting