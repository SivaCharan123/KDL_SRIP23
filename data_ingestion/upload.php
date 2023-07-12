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

                // Change to current working directory
                chdir(dirname(__FILE__));

                $FORM_FILE_NAME = validate_data($_FILES["csv_file"]["name"]);
                $FORM_DATASET_NAME = validate_data($_POST["csv_name"]);
                $FORM_DATASET_DESC = validate_data($_POST["csv_description"]);
                $FORM_DATASET_YEAR = validate_data($_POST["csv_year"]);
                $FORM_FILE_SIZE = $_FILES["csv_file"]["size"];
                $FORM_SDG_FLAG = get_SDG_flag("csv_sdg");

                // Get UNIX timestamp to append to file name to prevent duplicates.
                $timestamp = time();
                $target_dir = "datasets/";
                $target_file_name = $timestamp . "_" . basename($FORM_FILE_NAME);
                $target_file = $target_dir . $target_file_name;
                $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $uploadOk = 1;

                if ($FORM_DATASET_NAME == "") {
                    error("No name given to the data. Please enter name.");
                    $uploadOk = 0;
                }

                if ($FORM_DATASET_YEAR == "") {
                    error("No year given to the dataset. Please enter year.");
                    $uploadOk = 0;
                }

                if ($FORM_SDG_FLAG == 0) {
                    error("No relevant SDG goals chosen. Please choose AT LEAST ONE relevant to your dataset.");
                    $uploadOk = 0;
                }

                if (!isFileEmpty("csv_file")) {
                    if ($file_type != "csv" && $file_type != "xlsx" && $file_type != 'xls') {
                        error("Sorry, only CSV/XLSX files allowed.");
                        $uploadOk = 0;
                    }
                } else {
                    error("You did not select a file.");
                }

                if ($uploadOk == 0) {
                    notify("Unable to upload file. Please see the error messages.");
                } else {
                    if (move_uploaded_file($_FILES["csv_file"]["tmp_name"], $target_file)) {
                        shell_exec("echo " . escapeshellarg($FORM_DATASET_NAME) . " > tmp.meta");
                        shell_exec("echo " . escapeshellarg($FORM_DATASET_YEAR) . " >> tmp.meta");
                        shell_exec("echo " . escapeshellarg($FORM_DATASET_DESC) . " >> tmp.meta");
                        shell_exec("echo " . escapeshellarg($FORM_SDG_FLAG) . " >> tmp.meta");
                        shell_exec("/usr/bin/python3 upload_single_druid.py " . escapeshellarg($target_file_name));
                        success("The file " . htmlspecialchars(basename($FORM_FILE_NAME)) . " has been uploaded!");
                    } else {
                        notify("There was an error on the server side uploading the file. Contact site administrator. See following error code for details: " . $uploadErrorCodes[$_FILES["csv_file"]["error"]]);
                    }
                }

                shell_exec("rm tmp.meta");
                ?>
            </div>
            <div class="card-footer">
                <form action="form-upload.php">
                    <button type="submit" class="btn btn-success"><i class="fa fa-arrow-left"></i>&nbsp;Go back
                        to the upload form</button>
                </form>
            </div>
        </div>
</body>

</html>