import pandas
import sys

if(len(sys.argv) != 3):
    print("Usage: xlsx-to-csv.py [XLSX NAME] [OUTPUT NAME]")

xlsx = pandas.read_excel(sys.argv[1])
xlsx.to_csv(sys.argv[2], encoding='utf-8', index=False)
