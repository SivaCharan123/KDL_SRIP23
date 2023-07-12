<!DOCTYPE html>
<html>

<head>
    <title>Uploading...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .alert {
            line-height: 30px;
            padding: 0px 10px;
        }
    </style>
</head>

<body>
    <div class="container py-3">
        <div class="card shadow mx-auto w-50">
            <div class="card-header">
                <h5 class="card-title"><span class="fa fa-upload"></span>&nbsp; Uploading your dataset...</h5>
                See the messages below for details.
            </div>
            <div class="card-body mx-auto">
                <?php

                include 'helper.php';
                chdir(dirname(__FILE__));

                if (isFileArrayEmpty("batch_files")) {
                    error("No file selected.");
                } else {
                    $DATA_DUMP_NAME = validate_data($_POST["batch_name"]);
                    $DATA_DUMP_YEAR = validate_data($_POST["batch_year"]);
                    $DATA_DUMP_DESC = validate_data($_POST["batch_description"]);
                    $DATA_DUMP_SDG = get_SDG_flag("batch_sdg");

                    $timestamp = time();
                    $target_dir = $timestamp . "_" . $DATA_DUMP_NAME . "/";
                    $target_dir_fullpath = "datasets/" . $target_dir;

                    // First create the directory that would hold the batch files
                    mkdir($target_dir_fullpath);
                    // Next move all batch files to directory
                    $count = 0;
                    foreach ($_FILES["batch_files"]["name"] as $filename) {
                        if ($filename == "") {
                            continue;
                        }
                        notify("Uploading " . $filename . " ... ");
                        $target_file_name = basename($filename);
                        // Prefix filenames with their index to make the filename unique
                        $target_file_name_actual = $count . "_" . $target_file_name;
                        if (!move_uploaded_file($_FILES["batch_files"]["tmp_name"][$count], $target_dir_fullpath . $target_file_name_actual)) {
                            error("Failed to upload " . $filename . ", error code: " . $_FILES["batch_files"]["name"][$filename]["error"]);
                        } else {
                            // Create meta file.
                            shell_exec("echo " . escapeshellarg($DATA_DUMP_NAME) . " > tmp.meta");
                            shell_exec("echo " . escapeshellarg($DATA_DUMP_YEAR) . " >> tmp.meta");
                            shell_exec("echo " . escapeshellarg($DATA_DUMP_DESC) . " >> tmp.meta");
                            shell_exec("echo " . escapeshellarg($DATA_DUMP_SDG) . " >> tmp.meta");
                            success("Uploaded  " . $filename);
                        }
                        $count = $count + 1;
                    }
                    notify("Files uploaded successfully!");
                    notify("Now, sending data to Druid server...");
                    // The Python script will upload any CSV/XLSX files
                    shell_exec("/usr/bin/python3 upload_batch_druid.py " . escapeshellarg($target_dir));
                    shell_exec("rm tmp.meta");
                }
                ?>
            </div>
            <div class="card-footer">
                <form action="form-upload.php">
                    <button type="submit" class="btn btn-success"><i class="fa fa-arrow-left"></i>&nbsp;Go back
                        to the upload form</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>