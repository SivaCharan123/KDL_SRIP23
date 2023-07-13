# LOGGER.PY

## Function

This Python script contains two functions `Log()` and `LogException()`. Both output a string with the timestamp attached to `log.txt`, except the `LogException()` adds a `*** EXCEPTION ***`.

It is recommended that if you are adding an Python code, to use this logger. When your code is deployed on the server, there is no stdout, or stderr, thus having the `log.txt` file to see what went wrong at some point helps.

## Usage

- Almost every Python script logs it activity into `log.txt` for debugging purposes. 

- `druid-status.php` reads `log.txt` and displays it.