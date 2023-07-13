# DRUID-QUERY.PHP

## Function

This PHP file takes a JSON POST and forwards it to the Druid instance, then it returns a sanitized JSON array which contains the table returned by Druid.

## Working

It calls `query_exec.py` with the query received as an argument. 

## Used By

- `druid-status.php`'s Druid monitor uses this to allow remote queries to the Druid instance for debugging purposes.