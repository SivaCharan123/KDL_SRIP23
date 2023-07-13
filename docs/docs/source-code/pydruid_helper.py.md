# PYDRUID_HELPER.PY

## Function

It implements `ExecuteSQLQuery()` (returns a pandas dataFrame) and `ExecuteSQLQueryGetJSON()` (returns a JSON) which can be used to receive results of queries from Druid SQL.

!!! note
    The filename was originally `pydruid_helper` as it used the `pydruid` library, but I realized for our purposes that Python `requests` was enough and simple. So I removed the Pydruid code but retained the name.

## Used By

- `query_exec.py`
- `fetch_catalog.py`
- `fetch_rows.py`