<!DOCTYPE html>
<html>
<head>
    <title>Data Catalog</title>
    <link href="styles/tables.css" rel=stylesheet type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="styles/google-font.css" rel="stylesheet" type="text/css" />
    <link href="styles/styles.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div class="container-fluid gfont">
<h1 style="text-align:center;">DATASET CATALOG</h1>
<p style="text-align:center;">
This is the dataset catalog. Here, you may find all the datasets that have been uploaded to the server. Click on the filename to preview the dataset.
</p>
<?php
// Function to sanitize the cell data
function sanitize($data)
{
    return htmlspecialchars(trim($data));
}

// Parse the SDG Flag
function parse_sdgs($sdg_flags)
{
    $sdg_out = "";
    for($i = 0 ; $i < 18; $i++)
    {
        if($sdg_flags & (1 << $i))
        {
            $sdg_out .= (string)$i . "<br>";
        }
    }
    if($sdg_out == "")
        $sdg_out = 'None';
    return $sdg_out;
}

chdir(dirname(__FILE__));

// Read the CSV file
$file = 'dataset-catalog.csv';

shell_exec("/usr/bin/python3 fetch_catalog.py " . $file);

$csvData = file_get_contents($file);
$lines = explode(PHP_EOL, $csvData);

// Generate the table
$table = '<table class="mx-auto">';
$table .= '<tr>';
$table .= '<th>S.No</th>';
$table .= '<th>Dataset Name</th>';
$table .= '<th>Year of the Database</th>';
$table .= '<th>Dataset Description (optional)</th>';
$table .= '<th>Relevant SDGs</th>';
$table .= '<th>File Name</th>';
$table .= '<th>Upload time &amp; Date</th>';
$table .= '</tr>';

// Process each row
for ($i = 1; $i < count($lines); $i++) {
    $cells = str_getcsv($lines[$i]);

    // Only process rows with data
    if (!empty($cells[0])) {
        // Display the data rows
        $table .= '<tr>';
        // Serial number column
        $table .= '<td>' . $i . '</td>';

        for ($k = 0; $k < count($cells); $k++) {
            $cellData = sanitize($cells[$k]);

            // Remove surrounding quotes if present
            if (preg_match('/^"(.*)"$/s', $cellData, $matches)) {
                $cellData = $matches[1];
            }

            // Check if the column is "File Name"
            if ($k == 4) {
                $link = 'fetch.php?datasetName=' . urlencode($cellData);
                $table .= '<td><a href="' . $link . '">' . $cellData . '</a></td>';
            } else if($k == 3)
            {
                // Parse SDGs
                $sdgs = parse_sdgs((int)$cellData);
                $table .= '<td>' . $sdgs . '</td>';
            } else {
                $table .= '<td>' . $cellData . '</td>';
            }
        }

        $table .= '</tr>';
    }
}

$table .= '</table>';

echo $table;
?>
</div>
</body>
</html>