import boto3
import os
import json
import settings 

class S3Info:

    def __init__(self, AWS_key, AWS_secret, AWS_bucket, AWS_downloaddir):
        self.AWS_key = AWS_key
        self.AWS_secret = AWS_secret
        self.AWS_bucket = AWS_bucket
        self.AWS_downloaddir = AWS_downloaddir
    def __repr__(self):
        return f"<AWS key: '{self.AWS_key}', AWS Secret: '{self.AWS_secret}', AWS Bucket Name: '{self.AWS_bucket}', Downloading to: {self.AWS_downloaddir}>"


class BrowserItem:
    def __init__(self, name, type='file'):
        self.name = name
        self.type = type
    
    def __repr__(self) -> str:
        return f"<BrowserItem, name: {self.name}, type:{self.type}>"

def GetS3InfoFromConsole():
    defaults = json.loads(''.join(open("defaults.secure_json").readlines()))

    AWS_keyID = input(f"Enter Amazon AWS Key ID (default: {defaults['AWS_KEYID']}): ")
    AWS_secret = input(f"Enter Amazon Secret Key (default: {defaults['AWS_SECRET']}): ")
    AWS_bucket = input(f"Enter AWS Bucket Name (default: {defaults['AWS_BUCKET']}): ")
    AWS_downloaddir = input(f"Enter AWS Downloads directory (default: {defaults['AWS_DOWNLOAD_DIRECTORY']})")

    if(AWS_keyID == ""):
        AWS_keyID = defaults['AWS_KEYID']
    
    if(AWS_secret == ""):
        AWS_secret = defaults['AWS_SECRET']
    
    if(AWS_bucket == ""):
        AWS_bucket = defaults['AWS_BUCKET']
    
    if(AWS_downloaddir == ""):
        AWS_downloaddir = defaults['AWS_DOWNLOAD_DIRECTORY']
    
    return [S3Info(AWS_keyID, AWS_secret, AWS_bucket, AWS_downloaddir), None]

def ConstructBrowserTree(files, folders):
    browser_tree = []

    browser_tree.append(BrowserItem("..", 'dir'))

    for file in files:
        if(file != ''):
            browser_tree.append(BrowserItem(file, 'file'))
    
    for folder in folders:
        if(folder != ''):
            browser_tree.append(BrowserItem(folder, 'dir'))
    
    return browser_tree

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

def ExploreS3Directory(s3_client, s3info, base_dir):
    current_files = GetS3Files(s3_client, s3info, base_dir)
    current_directories = GetS3Directories(s3_client, s3info, base_dir)
    browser_tree = ConstructBrowserTree(current_files, current_directories)
    return browser_tree

def S3PathToLocal(s3info, s3_path):
    return os.path.join(s3info.AWS_downloaddir, os.path.basename(settings.S3_DOWNLOAD_FILE_PREFIX + s3_path.replace("/", "__")))

def DownloadS3File(s3_client, s3info, filename):
    print("Downloading file... ", filename, " from ", s3info.AWS_bucket)
    s3_client.download_file(s3info.AWS_bucket, filename, S3PathToLocal(s3info, filename))
    return S3PathToLocal(s3info, filename)