# HELPER.PHP

## Function

It contains some common helpful routines.

## Working

It contains functions like `error(msg)` and `notify(msg)` which automatically create a nice looking Bootstrap alert. It also contains `get_SDG_flags()` which turns the SDG checkboxes received into a bitwise represenation which can be stored simply as an integer. 

## Used By

- `upload.php`
- `upload-batch.php`