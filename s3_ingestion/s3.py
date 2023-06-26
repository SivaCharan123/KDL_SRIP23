import boto3
import os

def CreateS3Client(s3info):
    s3_session = boto3.Session(aws_access_key_id=s3info.AWS_key, aws_secret_access_key=s3info.AWS_secret)
    return s3_session.client('s3')

def GetS3Files(s3_client, s3info, base_dir):
    response = s3_client.list_objects_v2(Bucket=s3info.AWS_bucket, Prefix=base_dir, Delimiter='/')
    try:
        return [os.path.basename(obj['Key']) for obj in response.get('Contents', [])]
    except:
        return []
    
def GetS3Directories(s3_client, s3info, base_dir):
    response = s3_client.list_objects_v2(Bucket=s3info.AWS_bucket, Prefix=base_dir, Delimiter='/')
    try:
        return [os.path.basename(os.path.normpath(obj.get('Prefix'))) for obj in response.get('CommonPrefixes')]
    except TypeError:
        return []

def DownloadS3File(s3_client, s3info, filename, destination_folder):
    s3_client.download_file(s3info.AWS_bucket, filename, os.path.join(destination_folder, os.path.basename(filename.replace("/", "__"))))