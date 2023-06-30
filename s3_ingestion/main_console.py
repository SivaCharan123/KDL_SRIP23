import json
import shlex
import os
import colorama

import s3
from s3 import S3Info
from form_s3info import DruidInfo

def console_askinfo():
    return s3.GetS3InfoFromConsole()

def connect(s3info):
    return s3.CreateS3Client(s3info)

def console(s3_client, s3info):
    current_dir = ''
    current_filetree = s3.ExploreS3Directory(s3_client, s3info, current_dir)

    def inner_SwitchDir(dir):
        nonlocal current_filetree, current_dir
        found = False
        to_switch = dir
        if(to_switch == ''):
            return
        
        if(to_switch == '..'):
            found = True
            if(current_dir == ''):
                pass
            else:
                current_dir = os.path.dirname(os.path.normpath(current_dir)) + "/"
                if(current_dir == '/'):
                    current_dir = ""
        else:
            for file in current_filetree:
                if(file.name == to_switch and file.type == 'dir'):
                    current_dir += file.name + "/"
                    found = True
                    break
            
        if(not found):
            print(f"No such directory: {to_switch}")
            return False
        else:
            current_filetree = s3.ExploreS3Directory(s3_client, s3info, current_dir)
            return True
    
    def inner_RecursiveExplore(cdir):
        global recur_explore_files, recur_explore_dirs
        nonlocal s3_client, s3info
        file_list = []
        current_tree = s3.ExploreS3Directory(s3_client, s3info, cdir)
        for file in current_tree:
            if(file.type == 'file'):
                file_list.append(cdir + file.name)
                recur_explore_files += 1
            else:
                if(file.name == ".."):
                    continue
                else:
                    file_list.append(cdir + file.name + "/")
                    file_list += inner_RecursiveExplore(cdir + file.name + "/")
                    recur_explore_dirs += 1
        return file_list

    def inner_TraversePath(path):
        nonlocal current_filetree, current_dir
        save_currentfile_tree = current_filetree
        save_current_dir = current_dir
        path = os.path.normpath(path)
        directories = path.split(os.sep)
        for dir in directories:
            status = inner_SwitchDir(dir)
            if(not status):
                current_filetree = save_currentfile_tree
                current_dir = save_current_dir
                break
    while True:
        prompt = colorama.Fore.RED + s3info.AWS_bucket + colorama.Fore.RESET + ":" + colorama.Fore.BLUE + "root/" + current_dir + colorama.Fore.RESET + "$ "
        inpt = input(prompt)
        tokens = shlex.split(inpt)
        command = tokens[0]
        
        if(command == 'ls'):
            for file in current_filetree:
                if(file.type == 'file'):
                    print(colorama.Fore.GREEN + file.name + colorama.Fore.RESET)
                else:
                    print(colorama.Back.GREEN + colorama.Fore.BLACK + file.name + '/' + colorama.Fore.RESET + colorama.Back.RESET)
            print("")

        elif(command == 'cd'):
            if(len(tokens) != 2):
                print("Expected: cd <directory>")
            else:
                inner_TraversePath(tokens[1])
        elif(command == "explore"):
            print("WARNING: This will recursively explore the S3 database and return the names of all files!")
            filename = input("Enter place to store files (empty to output to shell): ")
            global recur_explore_files, recur_explore_dirs
            recur_explore_files = 0
            recur_explore_dirs = 0
            file_tree = inner_RecursiveExplore(current_dir)
            if(filename != ""):
                f = open(filename, 'w')
                f.write('\n'.join(file_tree))
                f.close()
            else:
                for file in file_tree:
                    print(file)
            print(f"Found {recur_explore_dirs} directories, and {recur_explore_files} files.")
        elif(command == 'exit'):
            exit(0)

def print_intro():
    print(colorama.Fore.CYAN + "---- S3 to Druid Console ----" + colorama.Fore.RESET)
    print(colorama.Fore.RED + "(C) Siddharth Gautam, 2023" + colorama.Fore.RESET)

if __name__ == "__main__":
    print_intro()
    s3info = console_askinfo()[0]
    print(s3info)
    s3_client = connect(s3info)
    console(s3_client, s3info)