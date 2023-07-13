# QUERY_EXEC.PY

## Function

Execute a query to the Druid cluster just by passing it as an argument to this script.

## Working

It simply calls `ExecuteSQLQueryGetJSON()` from `pydruid_helper.py` with argument as `sys.argv[1]`.

## Used By

- `druid_status.php` as part of the Druid monitor