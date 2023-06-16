<link href="styles/google-font.css" rel="stylesheet" type="text/css" />
<link href="styles/styles.css" rel="stylesheet" type="text/css"/>
<div class='gfont'>
<?php

function validate_data($DATA) 
{
    $DATA = trim($DATA);
    $DATA = stripslashes($DATA);
    $DATA = htmlspecialchars($DATA);
    return $DATA;
}

function notify($MSG)
{
    echo "<div class=\"notification\">";
    echo $MSG;
    echo "</div>";
}
$DATA_DUMP_NAME = validate_data($_POST["batch_name"]);
$DATA_DUMP_YEAR = validate_data($_POST["batch_year"]);
$DATA_DUMP_DESC = validate_data($_POST["batch_description"]);

$timestamp = time();
$target_dir =  $timestamp . "_" . $DATA_DUMP_NAME . "/";
$target_dir_fullpath = "datasets/" . $target_dir;

// First create the directory that would hold the batch files
mkdir($target_dir_fullpath);
// Next move all batch files to directory
$count = 0;
foreach($_FILES["batch_files"]["name"] as $filename)
{
    echo "<div class=\"notification\">";
    echo "Uploading " . $filename . " ... ";
    if(!move_uploaded_file($_FILES["batch_files"]["tmp_name"][$count], $target_dir_fullpath . basename($filename)))
    {
        echo "FAILED!<br>";
    }
    else
    {
        shell_exec("/usr/bin/python3 csv_writer.py \"" . $DATA_DUMP_NAME . "\" \"" . $DATA_DUMP_YEAR  . "\" \"" .  $DATA_DUMP_DESC . "\" \"" . 0 . "\" \"" .  basename($filename) ."\"");
        echo "SUCCESS!<br>";
    }
    $count = $count + 1;
    echo "</div>";
}
notify("Files uploaded successfully!");
notify("Now, sending data to Druid server...");
// The Python script will upload any CSV/XLSX files
shell_exec("/usr/bin/python3 upload_batch_druid.py \"" . $target_dir . "\"");
// Redirect 
echo "<h2><center>Redirecting to upload page in 10 seconds...</center></h2><br>";
header('refresh:10;url=form-upload.php');
?>
</div>