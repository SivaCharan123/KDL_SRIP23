# RETRIEVAL.PHP

## Function

PHP page to retrieve data from the cluster. Allows grouping of data as per SDGs, and also allows custom queries and viewing available ontologies.

## Working

It uses `csv-from-druid.php` which queries Druid for CSV format. It also uses ACE 1.5.0 editor for the SQL editor to make it easy to write SQL code on the browser itself. It uses FileSaver.js to provide file saving functionality.

