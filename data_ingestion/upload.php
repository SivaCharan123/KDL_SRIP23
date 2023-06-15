<link href="styles/google-font.css" rel="stylesheet" type="text/css" />
<link href="styles/styles.css" rel="stylesheet" type="text/css"/>
<?php
echo "<div class='gfont'>";
 
// Validate form

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
    if(!isset($_POST['csv_sdg']))
    {
        return 0;
    }
    $FORM_SDG_DATA = $_POST["csv_sdg"];
    $SDG_FLAG = 0;
    foreach($FORM_SDG_DATA as $SDG)
    {
        $SDG_FLAG = set_bit($SDG_FLAG, $SDG);
    }
    return $SDG_FLAG;
}

$FORM_FILE_NAME = validate_data($_FILES["csv_file"]["name"]);
$FORM_DATASET_NAME = validate_data($_POST["csv_name"]);
$FORM_DATASET_DESC = validate_data($_POST["csv_description"]);
$FORM_DATASET_YEAR = validate_data($_POST["csv_year"]);
$FORM_FILE_SIZE = $_FILES["csv_file"]["size"];
$FORM_SDG_FLAG = get_SDG_flag();

// Get UNIX timestamp to append to file name to prevent duplicates.
$timestamp = time();
$target_dir = "datasets/";
$target_file_name = $timestamp . "_" . basename($FORM_FILE_NAME);
$target_file = $target_dir . $target_file_name;
$file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$uploadOk = 1;

if($_FILES["csv_file"]["size"] > 500000)
{
    echo "<div class=\"error\">Error: File exceeds 50 MB!</div>";
    $uploadOk = 0;
}

if($FORM_DATASET_NAME == "")
{
    echo "<div class=\"error\">Error: No name given to the data. Please enter name!</div>";
    $uploadOk = 0;
}

if($FORM_DATASET_YEAR == "")
{
    echo "<div class=\"error\">Error: No year given to the dataset. Please enter year!</div>";
    $uploadOk = 0;
}

if($FORM_SDG_FLAG == 0)
{
    echo "<div class=\"error\">Error: No relevant SDG goals chosen. Please choose AT LEAST ONE relevant to your dataset!</div>";
    $uploadOk = 0;
}

if($file_type != "csv" && $file_type != "xlsx" && $file_type != 'xls')
{
    echo "<div class=\"error\">Error: Sorry, only CSV/XLSX files allowed!</div><br>";
    $uploadOk = 0;
}

if($uploadOk == 0)
{
    echo "<div class=\"notification\">Unable to upload file. See error message.</div>";
}
else
{
    if(move_uploaded_file($_FILES["csv_file"]["tmp_name"], $target_file))
    {
        if($file_type == 'xlsx' || $file_type == 'xls')
        {
            shell_exec("/usr/bin/python3 xlsx-to-csv.py " . $target_file . " " . str_replace($file_type, "csv", $target_file));
            $target_file = str_replace($file_type, "csv", $target_file);
            $target_file_name = str_replace($file_type, "csv", $target_file_name);
        }
        $metafilename = $target_dir . $timestamp . "_" . substr(basename($FORM_FILE_NAME), 0 , (strrpos(basename($FORM_FILE_NAME), "."))) . ".meta";
        $metafile = fopen($metafilename, "w");
        fwrite($metafile, "<dataset filename=" . "\"" . $_FILES["csv_file"]["name"] . "\"" . ">\n");
        fwrite($metafile, "\t<name>" . $FORM_DATASET_NAME . "</name>\n");
        fwrite($metafile, "\t<year>" . $FORM_DATASET_YEAR . "</year>\n");
        fwrite($metafile, "\t<desc>\n\t\t" . $FORM_DATASET_DESC. "\n\t</desc>\n");
        fwrite($metafile, "\t<sdg>" . $FORM_SDG_FLAG . "</sdg>\n");
        fwrite($metafile, "</dataset>");
        fclose($metafile);
        shell_exec("/usr/bin/python3 csv-writer.py \"" . $FORM_DATASET_NAME . "\" \"" . $FORM_DATASET_YEAR  . "\" \"" .  $FORM_DATASET_DESC . "\" \"" . $FORM_SDG_FLAG . "\" \"" .  $_FILES["csv_file"]["name"] ."\"");
        echo "<h1><center>The file " . htmlspecialchars(basename($FORM_FILE_NAME)) . " has been uploaded!</center></h1><br>";
        $output = shell_exec("/usr/bin/python3 upload-to-druid.py " . $target_file_name);
        echo "<div class=\"notification\"><h3>Shell Output<h3><br>";
        echo "<p style=\"font-family:'Lucida Console', monospace\" align=\"left\">";
        echo "<pre>";
        echo $output;
        echo "</pre>";
        echo "</p></div>";
    }
    else
    {
        echo "<div class=\"error\">There was an error on the server side uploading the file. Contact site administrator.</div>";
    }
}

echo "<h2><center>Redirecting to upload page in 10 seconds...</center></h2><br>";
header('refresh:10;url=form-upload.php');
?>