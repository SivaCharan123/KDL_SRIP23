import tkinter as tk 
import tkinter.filedialog as tfd
import settings
import json 

class S3Info:

    def __init__(self, AWS_key, AWS_secret, AWS_bucket, AWS_downloaddir):
        self.AWS_key = AWS_key
        self.AWS_secret = AWS_secret
        self.AWS_bucket = AWS_bucket
        self.AWS_downloaddir = AWS_downloaddir
    def __repr__(self):
        return f"<AWS key: '{self.AWS_key}', AWS Secret: '{self.AWS_secret}', AWS Bucket Name: '{self.AWS_bucket}', Downloading to: {self.AWS_downloaddir}>"

class DruidInfo:

    def __init__(self, Druid_protocol, Druid_host, Druid_port, Druid_taskpath):
        self.Druid_protocol = Druid_protocol
        self.Druid_host = Druid_host
        self.Druid_port = Druid_port
        self.Druid_taskpath = Druid_taskpath
    
    def __repr__(self):
        return f"Druid Server Info: <{self.Druid_protocol}://{self.Druid_host}:{self.Druid_port}{self.Druid_taskpath}>"

def Form_S3Info():

    window = tk.Tk()
    window.eval('tk::PlaceWindow . center')
    window.title("S3 to Druid File Transfer")
    window.resizable(width=False, height=False)

    AWS_keyid = tk.StringVar()
    AWS_secret = tk.StringVar()
    AWS_bucket = tk.StringVar()
    Druid_protocol = tk.StringVar()
    Druid_host = tk.StringVar()
    Druid_port = tk.StringVar()
    Druid_taskpath = tk.StringVar()
    AWS_downloaddir = tk.StringVar()
    GetClicked = False

    # Load defaults
    defaults = json.loads(''.join(open("defaults.secure_json").readlines()))
    AWS_keyid.set(defaults["AWS_KEYID"])
    AWS_secret.set(defaults["AWS_SECRET"])
    AWS_bucket.set(defaults["AWS_BUCKET"])
    AWS_downloaddir.set(defaults["AWS_DOWNLOAD_DIRECTORY"])
    Druid_protocol.set(defaults["DRUID_PROTOCOL"])
    Druid_host.set(defaults["DRUID_SERVERLOC"])
    Druid_port.set(defaults["DRUID_PORT"])
    Druid_taskpath.set(defaults["DRUID_TASK_PATH"])
    
    def inner_OnSubmitClick():
        nonlocal GetClicked
        GetClicked = True
        window.destroy()

    def inner_BrowseAWSDir():
        filename = tfd.askdirectory()
        AWS_downloaddir.set(filename)

    # -------------- FRAME CREATION ------
    frm_s3 = tk.Frame(master=window, borderwidth=2, relief="ridge")
    frm_druid = tk.Frame(master=window, borderwidth=2, relief="ridge")

    # -------------- S3 FORM --------------
    ent_AWS_keyid = tk.Entry(master=frm_s3, width=50, textvariable=AWS_keyid)
    lbl_AWS_keyid = tk.Label(master=frm_s3, text="AWS Key ID")

    ent_AWS_secret = tk.Entry(master=frm_s3, width=50, textvariable=AWS_secret)
    lbl_AWS_secret = tk.Label(master=frm_s3, text="AWS Secret Key")

    ent_AWS_bucketname = tk.Entry(master=frm_s3, width=50, textvariable=AWS_bucket)
    lbl_AWS_bucketname = tk.Label(master=frm_s3, text="AWS Bucket")

    ent_AWS_downloaddir = tk.Entry(master=frm_s3, width=50, textvariable=AWS_downloaddir)
    lbl_AWS_downloaddir = tk.Label(master=frm_s3, text="AWS Downloads Directory")

    btn_AWS_browsedownloaddir = tk.Button(master=frm_s3, text="...", command=inner_BrowseAWSDir)

    lbl_AWS_keyid.grid(row=0, column=0, sticky="w", padx=settings.DEFAULT_PADDING_X, pady=settings.DEFAULT_PADDING_Y)
    ent_AWS_keyid.grid(row=0, column=1, sticky="e", padx=settings.DEFAULT_PADDING_X, pady=settings.DEFAULT_PADDING_Y)

    lbl_AWS_secret.grid(row=1, column=0, sticky="w", padx=settings.DEFAULT_PADDING_X, pady=settings.DEFAULT_PADDING_Y)
    ent_AWS_secret.grid(row=1, column=1, sticky="e", padx=settings.DEFAULT_PADDING_X, pady=settings.DEFAULT_PADDING_Y)

    lbl_AWS_bucketname.grid(row=2, column=0, sticky="w", padx=settings.DEFAULT_PADDING_X, pady=settings.DEFAULT_PADDING_Y)
    ent_AWS_bucketname.grid(row=2, column=1, sticky="w", padx=settings.DEFAULT_PADDING_X, pady=settings.DEFAULT_PADDING_Y)

    lbl_AWS_downloaddir.grid(row=3, column=0, sticky="w", padx=settings.DEFAULT_PADDING_X, pady=settings.DEFAULT_PADDING_Y)
    ent_AWS_downloaddir.grid(row=3, column=1, sticky="w", padx=settings.DEFAULT_PADDING_X, pady=settings.DEFAULT_PADDING_Y)

    btn_AWS_browsedownloaddir.grid(row=3, column=2)
    
    # -------------- DRUID FORM --------------
    ent_Druid_protocol = tk.Entry(master=frm_druid, width=5, textvariable=Druid_protocol)
    lbl_Druid_protocol = tk.Label(master=frm_druid, text="Protocol")
    lbl_URL_delimiter = tk.Label(master=frm_druid, text="://")

    ent_Druid_host = tk.Entry(master=frm_druid, width=25, textvariable=Druid_host)
    lbl_Druid_host = tk.Label(master=frm_druid, text="Hostname")

    ent_Druid_port = tk.Entry(master=frm_druid, width=5, textvariable=Druid_port)
    lbl_Druid_port = tk.Label(master=frm_druid, text="Port")
    lbl_port_delimiter = tk.Label(master=frm_druid, text=":")

    ent_Druid_taskpath = tk.Entry(master=frm_druid, width=25, textvariable=Druid_taskpath)
    lbl_Druid_taskpath = tk.Label(master=frm_druid, text="Task")

    lbl_Druid_protocol.grid(row=0, column=0)
    ent_Druid_protocol.grid(row=1, column=0, pady=settings.DEFAULT_PADDING_Y)
    lbl_URL_delimiter.grid(row=1, column=1, pady=settings.DEFAULT_PADDING_Y)

    lbl_Druid_host.grid(row=0, column=2)
    ent_Druid_host.grid(row=1, column=2)

    lbl_port_delimiter.grid(row=1,column=3)
    lbl_Druid_port.grid(row=0, column=4)
    ent_Druid_port.grid(row=1, column=4)

    lbl_Druid_taskpath.grid(row=0, column=5)
    ent_Druid_taskpath.grid(row=1, column=5)

    # ------- OUTER ELEMENTS -------
    lbl_AWS_title = tk.Label(window, text="AWS Details")
    lbl_Druid_title = tk.Label(window, text="Druid Details")
    btn_submit = tk.Button(master=window, text="Get Bucket Files \N{RIGHTWARDS BLACK ARROW}", command=inner_OnSubmitClick)

    lbl_AWS_title.grid(row=0, column=0)
    frm_s3.grid(row=1, column=0, padx=settings.FRAME_PADDING_X, pady=settings.FRAME_PADDING_Y)
    lbl_Druid_title.grid(row=2, column=0)

    frm_druid.grid(row=3, column=0, padx=settings.FRAME_PADDING_X, pady=settings.FRAME_PADDING_Y)
    btn_submit.grid(row=4, column=0, padx=settings.DEFAULT_PADDING_X, pady=settings.DEFAULT_PADDING_Y)

    window.mainloop()

    if(GetClicked):
        return [S3Info(AWS_keyid.get(), AWS_secret.get(), AWS_bucket.get(), AWS_downloaddir.get()), DruidInfo(Druid_protocol.get(), Druid_host.get(), Druid_port.get(), Druid_taskpath.get())]
    else:
        return None