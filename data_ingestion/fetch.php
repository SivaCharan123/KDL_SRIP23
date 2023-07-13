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
    $command = '/usr/bin/python3 fetch_rows.py ' . escapeshellarg($datasetName);
    $output = shell_exec($command);

    // Decode the JSON response
    $data = json_decode($output, true);

    // Generate the table
    if (!empty($data)) {
        $table = '<table class="table mx-auto" id="fetch_php_table">';
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
        $table = 'No data available. Druid is perhaps not running on the server. If you are a server administrator reading this, please initialize Druid.';
    }

    echo $table;
} else {
    echo 'Invalid request.';
}
?>