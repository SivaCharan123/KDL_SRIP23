import sys
import traceback
import settings
from datetime import datetime

def GetTimeStamp():
    now = datetime.now()
    return now.strftime("%d/%m/%Y %H:%M:%S")

def Log(s):
    if(not settings.LOGGING):
        return
    
    dt_string = GetTimeStamp()
    with open(settings.LOG_FILE, 'a') as file:
        file.write(f"[{dt_string}] {s}\n")
        file.close()

def LogException():
    if(not settings.LOGGING):
        return
    
    dt_string = GetTimeStamp()
    with open(settings.LOG_FILE, 'a') as file:
        tb = traceback.format_exc()
        lines = tb.split("\n")
        file.write(f"[{dt_string}] *** EXCEPTION ***\n")
        for line in lines:
            file.write(f"[{dt_string}] {line}\n")
        file.write(f"[{dt_string}] ***\n")
        file.close()
    