# DATA-CATALOG.PHP

## Function

This is the data catalog that queries the local Druid cluster and then outputs an interactive HTML page.

## Working

First the PHP script calls `/usr/bin/python3 fetch_catalog.py` through PHP's `shell_exec` which stores the catalog into a local file called `dataset-catalog.csv`.

This `dataset-catalog.csv` is then read and turned into an HTML table by the PHP and send to the client.

In addition, the catalog adds an `onClick` handler to the dataset filenames that cause an AJAX request to `fetch.php` that returns an HTML table containing the first 100 rows from Druid, which is then appended to a modal that is presented to the user.