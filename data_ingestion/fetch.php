<!DOCTYPE html>
<html>
<head>
    <title>Data Fetch</title>
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
<?php
// Function to sanitize the cell data
function sanitize($data)
{
    return htmlspecialchars(trim($data));
}

// Check if the datasetName parameter is present in the URL
if (isset($_GET['datasetName'])) {
    // Get the dataset name from the URL parameter
    $datasetName = $_GET['datasetName'];

    // Call the Python script using system command
    $command = '/usr/bin/python3 pydruid_fetch.py ' . escapeshellarg($datasetName);
    $output = shell_exec($command);

    // Decode the JSON response
    $data = json_decode($output, true);

    // Generate the table
    if (!empty($data)) {
        $table = '<table>';
        $table .= '<tr>';

        // Generate table headers dynamically based on the JSON keys
        foreach ($data[0] as $key => $value) {
            $table .= '<th>' . sanitize($key) . '</th>';
        }

        $table .= '</tr>';

        // Display the first 100 rows
        for ($i = 0; $i < min(100, count($data)); $i++) {
            $table .= '<tr>';

            foreach ($data[$i] as $value) {
                $table .= '<td>' . sanitize($value) . '</td>';
            }

            $table .= '</tr>';
        }

        $table .= '</table>';
    } else {
        $table = 'No data available.';
    }

    echo $table;
} else {
    echo 'Invalid request.';
}
?>
</div>
</body>
</html>