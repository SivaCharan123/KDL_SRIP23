from form_s3browser import Form_S3Browser
from form_s3info import Form_S3Info

if __name__ == "__main__":
    form_info = Form_S3Info()
    s3info = form_info[0]
    druidinfo = form_info[1]
    print(s3info)
    print(druidinfo)
    if(form_info == None):
        exit(-1)
    else:
        Form_S3Browser(s3info)