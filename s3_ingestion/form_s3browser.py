import tkinter as tk
import form_settings
from s3 import *

class BrowserItem:
    def __init__(self, name, type='file'):
        self.name = name
        self.type = type

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

def Form_S3Browser(s3info):
    s3_client = CreateS3Client(s3info)
    current_dir = ''
    current_files = GetS3Files(s3_client, s3info, current_dir)
    current_directories = GetS3Directories(s3_client, s3info, current_dir)
    browser_tree = ConstructBrowserTree(current_files, current_directories)

    # Array to hold selected items
    selected_items = []

    window = tk.Tk()
    window.title(f"Browsing {s3info.AWS_bucket}")
    window.resizable(width=False, height=False)
    window.eval('tk::PlaceWindow . center')

    frm_browser = tk.Frame(master=window)

    label_browser = tk.Label(master=frm_browser, text="Files on S3")
    label_added = tk.Label(master=frm_browser, text="Files to transfer to Druid")

    # Create two list boxes, one for browsing and the other for selections.
    listbox_browser = tk.Listbox(master=frm_browser, height=20, width=50, bg="white", activestyle='dotbox', font="Helvetica", fg="black", selectmode="extended")
    selected_browser = tk.Listbox(master=frm_browser, height=20, width=50, bg="white", activestyle='dotbox', font="Helvetica", fg="black", selectmode="extended")

    # This function is always called when a directory is changed or an item is selected/deselected.
    def inner_PopulateBrowserBox():
        nonlocal listbox_browser, browser_tree
        listbox_browser.delete(0, tk.END)
        for idx, item in enumerate(browser_tree):
            if(item.type == 'file'):
                listbox_browser.insert(idx, "\N{DOCUMENT} " + item.name)
            else:
                listbox_browser.insert(idx, "\N{FILE FOLDER} " + item.name)
            
            added_names = [selected_items[i].name for i in range(len(selected_items))]

            # Turn selected items red
            if(current_dir + item.name in added_names):
                listbox_browser.itemconfig(idx, {'bg': 'red', 'fg': 'white'})
    
    # This function is always called when an item is selected / deselected
    def inner_PopulateSelectedBox():
        selected_browser.delete(0, tk.END)
        for idx, item in enumerate(selected_items):
            if(item.type == 'file'):
                selected_browser.insert(idx, "\N{DOCUMENT} " + item.name)
            else:
                selected_browser.insert(idx, "\N{FILE FOLDER} " + item.name)
    
    # This function is called when a directory changed is requested
    def inner_OnListBoxDoubleClick(ev):
        nonlocal current_dir, current_files, current_directories, browser_tree
        get_selections = listbox_browser.curselection()

        # If more than one is selected, ignore.
        if(len(get_selections) > 1):
            return
        id = get_selections[0]
        if(browser_tree[id].type != 'file'):
        # If ".." is the file name, traverse one directory back.
            if(browser_tree[id].name == ".."):
                if(current_dir == ''):
                    return
                current_dir = os.path.dirname(os.path.normpath(current_dir))
            else:
                current_dir += browser_tree[id].name + "/"

            current_files = GetS3Files(s3_client, s3info, current_dir)
            current_directories = GetS3Directories(s3_client, s3info, current_dir)
            browser_tree = ConstructBrowserTree(current_files, current_directories)
            inner_PopulateBrowserBox()

    # This function adds to the selection
    def inner_AddSelection():
        nonlocal selected_items
        to_add = [BrowserItem(current_dir + browser_tree[i].name, browser_tree[i].type) for i in listbox_browser.curselection()]
        print("Adding selected: ", [to_add[i].name for i in range(len(to_add))])
        selected_items += to_add
        inner_PopulateBrowserBox()
        inner_PopulateSelectedBox()
    
    # This function removes from the selection
    def inner_RemoveSelection():
        to_remove = selected_browser.curselection()
        for item in to_remove:
            del selected_items[item]
        inner_PopulateBrowserBox()
        inner_PopulateSelectedBox()

    # This function is called when a submit button is clicked
    def inner_Submit():
        for file in selected_items:
            DownloadS3File(s3_client, s3info, file.name, "./")
    
    # Populate the browser box
    inner_PopulateBrowserBox() 
    # Bind double click to our first listbox
    listbox_browser.bind("<Double-1>", inner_OnListBoxDoubleClick)
    btn_add = tk.Button(master=frm_browser, text="Add Selected to Druid", command=inner_AddSelection)
    btn_remove = tk.Button(master=frm_browser, text="Remove Selected from Druid", command=inner_RemoveSelection)
    btn_submit = tk.Button(master=window, text="Push to Druid!", command=inner_Submit)
    
    label_browser.grid(row=0, column=0)
    label_added.grid(row=0, column=1)
    listbox_browser.grid(row=1, column=0, sticky="e", pady=form_settings.DEFAULT_PADDING_Y)
    selected_browser.grid(row=1, column=1, sticky="w", pady=form_settings.DEFAULT_PADDING_Y)

    btn_add.grid(row=2, column=0)
    btn_remove.grid(row=2, column=1)

    frm_browser.grid(row=0, column=0, padx=form_settings.DEFAULT_PADDING_X, pady=form_settings.DEFAULT_PADDING_Y)
    btn_submit.grid(row=1, column=0, padx=form_settings.DEFAULT_PADDING_X, pady=form_settings.DEFAULT_PADDING_Y)
    window.mainloop()