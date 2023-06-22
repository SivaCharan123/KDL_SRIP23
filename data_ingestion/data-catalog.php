<!DOCTYPE html>
<html>
<head>
    <title>Data Catalog</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<link href="styles/google-font.css" rel="stylesheet" type="text/css" />
<link href="styles/styles.css" rel="stylesheet" type="text/css"/>
<div class="gfont">
<h1><center>DATASET CATALOG</center></h1>
<p>
This is the dataset catalog. Here, you may find all the datasets that have been uploaded to the server. Click on the filename to preview the dataset.
</p>
<?php
// Function to sanitize the cell data
function sanitize($data)
{
    return htmlspecialchars(trim($data));
}

chdir(dirname(__FILE__));

// Read the CSV file
$file = 'dataset-catalog.csv';
$csvData = file_get_contents($file);
$lines = explode(PHP_EOL, $csvData);

// Generate the table
$table = '<table>';
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
            if ($k === 4) {
                $link = 'fetch.php?datasetName=' . urlencode($cellData);
                $table .= '<td><a href="' . $link . '">' . $cellData . '</a></td>';
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