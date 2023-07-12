<link href="styles/google-font.css" rel="stylesheet" type="text/css" />
<link href="styles/styles.css" rel="stylesheet" type="text/css" />
<div class='gfont'>
    <?php

    function validate_data($DATA)
    {
        $DATA = trim($DATA);
        $DATA = stripslashes($DATA);
        $DATA = htmlspecialchars($DATA);
        return $DATA;
    }

    // Helper function for SDG goals
    function set_bit($NUMBER, $BIT)
    {
        return ($NUMBER) | (1 << $BIT);
    }

    function get_SDG_flag()
    {
        if (!isset($_POST['batch_sdg'])) {
            return 0;
        }
        $FORM_SDG_DATA = $_POST["batch_sdg"];
        $SDG_FLAG = 0;
        foreach ($FORM_SDG_DATA as $SDG) {
            $SDG_FLAG = set_bit($SDG_FLAG, $SDG);
        }
        return $SDG_FLAG;
    }


    function notify($MSG)
    {
        echo "<div class=\"notification\">";
        echo $MSG;
        echo "</div>";
    }

    chdir(dirname(__FILE__));

    $DATA_DUMP_NAME = validate_data($_POST["batch_name"]);
    $DATA_DUMP_YEAR = validate_data($_POST["batch_year"]);
    $DATA_DUMP_DESC = validate_data($_POST["batch_description"]);
    $DATA_DUMP_SDG = get_SDG_flag();

    $timestamp = time();
    $target_dir = $timestamp . "_" . $DATA_DUMP_NAME . "/";
    $target_dir_fullpath = "datasets/" . $target_dir;

    // First create the directory that would hold the batch files
    mkdir($target_dir_fullpath);
    // Next move all batch files to directory
    $count = 0;
    foreach ($_FILES["batch_files"]["name"] as $filename) {
        echo "<div class=\"notification\">";
        echo "Uploading " . $filename . " ... ";
        $target_file_name = basename($filename);
        // Prefix filenames with their index to make the filename unique
        $target_file_name_actual = $count . "_" . $target_file_name;
        if (!move_uploaded_file($_FILES["batch_files"]["tmp_name"][$count], $target_dir_fullpath . $target_file_name_actual)) {
            echo "FAILED!<br>";
        } else {
            // Create meta file.
            shell_exec("echo \"" . $DATA_DUMP_NAME . "\" > tmp.meta");
            shell_exec("echo \"" . $DATA_DUMP_YEAR . "\" >> tmp.meta");
            shell_exec("echo \"" . $DATA_DUMP_DESC . "\" >> tmp.meta");
            shell_exec("echo \"" . $DATA_DUMP_SDG . "\" >> tmp.meta");
            echo "SUCCESS!<br>";
        }
        $count = $count + 1;
        echo "</div>";
    }
    notify("Files uploaded successfully!");
    notify("Now, sending data to Druid server...");
    // The Python script will upload any CSV/XLSX files
    shell_exec("/usr/bin/python3 upload_batch_druid.py \"" . $target_dir . "\"");
    shell_exec("rm tmp.meta");
    // Redirect 
    echo "<h2><center>Redirecting to upload page in 10 seconds...</center></h2><br>";
    header('refresh:10;url=form-upload.php');
    ?>
</div>