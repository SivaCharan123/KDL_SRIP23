from form_s3browser import Form_S3Browser
from form_s3info import Form_S3Info

if __name__ == "__main__":
    s3info = Form_S3Info()
    if(s3info == None):
        exit(-1)
    else:
        Form_S3Browser(s3info)