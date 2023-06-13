import sys
import shutil

store_location = "datasets/zip/"

if(len(sys.argv) < 2):
    print("Usage: zip-file directory-name")
    exit(-1)

filename_split = sys.argv[1].split("/")

directory_name = filename_split[-1]

if(directory_name == ""):
    directory_name = filename_split[-2]

zip_file_loc = store_location + directory_name + ".zip"
shutil.make_archive(zip_file_loc, 'zip', sys.argv[1])