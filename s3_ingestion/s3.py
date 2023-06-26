import boto3
import os
import settings 

class BrowserItem:
    def __init__(self, name, type='file'):
        self.name = name
        self.type = type
    
    def __repr__(self) -> str:
        return f"<BrowserItem, name: {self.name}, type:{self.type}>"

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