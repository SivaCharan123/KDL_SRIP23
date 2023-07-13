# DRUID_STATUS.PHP

## Function

The Druid status page. Used for debugging the cluster. It shows server information, Druid task log, output of `log.txt`, and allows the programmer to execute custom queries to the Druid cluster from the web interface.

## Working

The Druid task log is retrieved by calling `get_tasks.py` through `shell_exec` and is turned into a table. The `log.txt` is read using PHP's `fread()` and the server information is read using PHP's `file_get_contents()` to the local Druid instance on localhost:8888/status.

The Druid monitor works by sending user typed queries to `druid-query.php` as an AJAX request and then outputs them into a table format in a `<div>` located below the input.