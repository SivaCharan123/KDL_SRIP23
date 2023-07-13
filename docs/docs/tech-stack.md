# Tech Stack

Currently, all the utilities are running on the [WSL-CADS](http://cads.iiitb.ac.in/) server. The server itself uses Apache2 and runs a copy of Ubuntu 22.04 LTS at the time of writing this documentation.

## Frontend

The frontend code is written entirely in HTML, CSS and Javascript. It uses Bootstrap 5.0.1 for the UI and Font-Awesome 6.4.0 for various UI icons.

## Backend

The backend is written entirely in PHP 8.1. Various utilities are written in Python that are then called through `shell_exec` in the PHP code.

## Data Solution

The data solution used is Apache Druid 26.0.0. It resides on the CADS server and can be either interacted with using the Druid Console on localhost:8888, or if you are unable to, as would be the case if you are working remotely, can be partially observed via the Druid Status page to view task logs and execute SQL queries.