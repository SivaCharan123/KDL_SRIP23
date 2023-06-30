import s3
import os
import pathlib

s3info = s3.GetS3InfoFromConsole()[0]
file_list = input("Enter filename with list of all files: ")
s3_client = s3.CreateS3Client(s3info)

with open(file_list, 'r') as f:
    file_names = f.readlines()
    file_names = [file.replace("\n", "") for file in file_names]
    for file in file_names:
        if(file.endswith(".csv")):
            print(f"Downloading file {file}...")
            final_dir = os.path.join(s3info.AWS_downloaddir + "/s3/", os.path.dirname(file))
            pathlib.Path(final_dir).mkdir(parents=True, exist_ok=True)
            final_location = os.path.join(final_dir, os.path.basename(file))
            s3_client.download_file(s3info.AWS_bucket, file, final_location) 

    f.close()
