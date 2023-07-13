# JS/DRUID-QUERY.JS

## Function

Used by `druid-status.php` to allow the client to send AJAX requests to `druid-query.php` which is used by the Druid monitor.

## Working 

It provides a `sendQueryToPHP()` which sends the Druid SQL query in a textbox with id "druid_query" to `druid-query.php` as a POST request in a JSON file. On success, it calls another function provided by it called `buildTable()` which generates an HTML table from the query. Error handling is done on the server side, so in case of an error, an error table containing an error message is returned by `query_exec.py` which is forwarded to `druid-query.php` which then forwards it to there.

## Used By

- `druid-status.php`