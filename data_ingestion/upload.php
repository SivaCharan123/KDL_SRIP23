<link href="styles/google-font.css" rel="stylesheet" type="text/css" />
<link href="styles/styles.css" rel="stylesheet" type="text/css"/>
<?php
echo "<div class='gfont'>";

$timestamp = time();
$target_dir = "datasets/";
$target_file_name = $timestamp . "_" . basename($_FILES["csv_file"]["name"]);
$target_file = $target_dir . $target_file_name;
$file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$uploadOk = 1;

if($_FILES["csv_file"]["size"] > 500000)
{
    echo "Sorry, your file is too large!";
    $uploadOk = 0;
}

if($file_type != "csv")
{
    echo "Sorry, only CSV files allowed!";
    $uploadOk = 0;
}

if($uploadOk == 0)
{
    echo "Unable to upload file. See error message.";
}
else
{
    if(move_uploaded_file($_FILES["csv_file"]["tmp_name"], $target_file))
    {
        $metafilename = $target_dir . $timestamp . "_" . basename($_FILES["csv_file"]["name"]) . ".meta";
        $metafile = fopen($metafilename, "w");
        fwrite($metafile, "<dataset filename=" . "\"" . $_FILES["csv_file"]["name"] . "\"" . ">\n");
        fwrite($metafile, "\t<name>" . $_POST["csv_name"] . "</name>\n");
        fwrite($metafile, "\t<year>" . $_POST["csv_year"] . "'</year>\n");
        fwrite($metafile, "\t<desc>\n\t\t" . $_POST["csv_description"] . "\n\t</desc>\n");
        fwrite($metafile, "</dataset>");
        fclose($metafile);
        echo "<h1><center>The file " . htmlspecialchars(basename($_FILES["csv_file"]["name"])) . " has been uploaded!</center></h1>";
        $output = shell_exec("/usr/bin/python3 upload-to-druid.py " . $target_file_name);
    }
    else
    {
        echo "There was an error uploading the file! Contact site administrator.";
    }
}

echo "<h2><center>Redirecting to upload page in 5 seconds...</h2></center>";
echo "</div>";
echo "Shell Output:<code>";
echo "<p style=\"font-family:'Lucida Console', monospace\" align=\"center\">";
echo $output;
echo "</p></code>";
header('refresh:5;url=index.php');
?>