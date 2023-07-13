# Setup: Development Environment

## Install WSL

Ensure that you are have WSL (Windows Subsystem for Linux) if you are operating on a Windows system. Apache Druid is only available for Linux systems, so unless you have a Linux machine or are dual booting with a Linux OS, WSL will be the best option.

To install WSL, please follow the official instructions here: [Install WSL2 on Microsoft Windows](https://learn.microsoft.com/en-us/windows/wsl/install).

Once you have WSL installed, open a Powershell Window and type "wsl", you should have a Linux terminal prompt open up.

## Install Apache Druid

You'll need an installation of Druid. Simply head to [Apache Druid Website](https://druid.apache.org/) and download the `tar.gz` file.

!!! note
    You'll need OpenJDK 8/11 installed on your Linux distribution to run Druid as it uses Java. **DO NOT** download OpenJDK-17, it will not work as of writing this documentation.

    For Ubuntu, the command is:

    `apt install openjdk-11-jdk openjdk-11-jre`

    Finally, ensure that Java is installed with the command: `java --version`.

    I get:

    `openjdk version "11.0.19" 2023-04-18`

    Followed by a long list of Java options.

!!! warning
    It is recommended that you shift the Druid directory i.e. `apache-druid-26.0.0` to the WSL home directory (`~` or `/home/<your username>`, both are equivalent). This is because Druid has some problems when it is executed from Windows directory such as `/mnt/c/Users/username/Downloads`.

## Install PHP 8.1

Find the way to download PHP 8.1 for your WSL Linux distribution. For Ubuntu users, it is as simple as:

`apt install php8.1-cli`

!!! note
    Verify using: `php --version`

    For reference, I get:

    `PHP 8.1.2-1ubuntu2.11 (cli) (built: Feb 22 2023 22:56:18) (NTS)`

## Install Python3 and Python Dependencies

The PHP backend uses Python 3 code to automate many tasks, hence it is required. 

!!! note

    For Ubuntu, simply run: `apt install python3`.

    Also, install: `apt install python-is-python3`. This makes it so that the "python" command immediately launches python3.

    Confirm installation using:

    `/usr/bin/python3`

    If this works, we are good.

The following Python packages are required:

- pandas
- openpyxl
- requests
- numpy

!!! note
    Install with `pip install numpy pandas openpyxl requests`.

## Test

Open up two Powershell windows, start Druid in one using: `./bin/start-druid` from the `apache-druid-26.0.0` directory you downloaded from Druid website.

Start the PHP server in one using `php -S localhost:3000 -c .` from the `data-ingestion/` directory in the repository.

You should now have a server running on localhost:3000. Visit it and see the System, Python and Druid Status. 

Congratulations, your development environment is set up.