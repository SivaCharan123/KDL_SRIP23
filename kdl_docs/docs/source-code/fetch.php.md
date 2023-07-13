# FETCH.PHP

## Function

Returns an HTML table containing the first 100 rows of a dataset name given by "datasetName" as part of a GET query.

## Working

It calls a Python script `fetch_rows.py` using PHP's `shell_exec` with the `datasetName` as an argument. The output of `fetch_rows.py` is then turned into an HTML table and returned to the client.

## Usage

- `data-catalog.php` does an AJAX call to `fetch.php` from the client side to fetch 100 rows. The `fetch_url` passed to ```fetch100Rows(fetch_url)``` is `fetch.php?datasetName=<dataset selected by user>`. ```fetch100Rows()``` does an AJAX call and then outputs the 100 rows to a Bootstrap modal which is then shown to user.