<!DOCTYPE html>
<html>

<head>
    <title>Data Catalog</title>
    <link href="styles/tables.css" rel=stylesheet type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="styles/styles.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="container">
        <h2 class="text-muted"><b>List of datasets uploaded to Apache&reg; Druid</b></h2>
            <p>
                <a class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#server_info" aria-expanded="false"
                    aria-controls="server_info">
                    Show Server Information
                </a>
            </p>

            <div class="collapse" id="server_info">
                <table>
                    <tr>
                        <td>Running on</td>
                        <td><span class="text-muted">
                                <?php echo (php_uname('s') . '-' . php_uname('r') . '-' . php_uname('m')) ?>
                            </span></td>
                    </tr>
                    <?php
                    ini_set("allow_url_fopen", 1);
                    $content = @file_get_contents('http://localhost:8888/status');
                    if ($content == FALSE) {
                        echo '<tr><td>Druid Status</td><td style="color:red;font-weight:bold;">NOT OK [USING CACHED CATALOG]</td></tr>';
                    } else {
                        $obj = json_decode($content);
                        $maxMem =  ceil($obj->memory->maxMemory / (1024 * 1024));
                        $freeMem = ceil($obj->memory->freeMemory / (1024 * 1024));
                        $percentFree = ceil(($freeMem / $maxMem)*100.0);
                        echo '<tr><td>Druid Status</td><td style="color:green;font-weight:bold;">OK</td></tr>';
                        echo "<tr><td>Druid version</td><td>" . $obj->version . "</td><tr>";
                        echo "<tr><td>Max Memory</td><td>" . $maxMem . " MB" . '</td></tr>';
                        echo "<tr><td>Free Memory</td><td>" . $freeMem . " MB (" . $percentFree . '%)' . '</td></tr>';
                    }
                    ?>
                </table>
            </div>

            <div class="py-2" style="float:right;">
                <label class="text-muted">Filter:</label>
                <input type="text" id="search_table_name" onkeyup="searchTable()" placeholder="Filter by Name..">
            </div>
            <?php
            // Function to sanitize the cell data
            function sanitize($data)
            {
                return htmlspecialchars(trim($data));
            }

            // Parse the SDG Flag
            function parse_sdgs($sdg_flags)
            {
                $SDG_URLS = array(
                    "",
                    "no-poverty",
                    "zero-hunger",
                    "good-health-and-well-being",
                    "quality-education",
                    "gender-equality",
                    "clean-water-and-sanitation",
                    "affordable-and-clean-energy",
                    "decent-work-and-economic-growth",
                    "industry-innovation-and-infrastructure",
                    "reduced-inequalities",
                    "sustainable-cities-and-communities",
                    "responsible-consumption-and-production",
                    "climate-action",
                    "life-below-water",
                    "life-on-land",
                    "peace-justice-and-strong-institutions",
                    "partnership-for-the-goals"
                );
                $sdg_out = "";
                for ($i = 1; $i < 18; $i++) {
                    if ($sdg_flags & (1 << $i)) {
                        $sdg_out .= '<a href="http://kdl.iiitb.ac.in/sdg-' . (string) $i . '-' . $SDG_URLS[$i] . '"> SDG ' . (string) $i . "</a>, ";
                    }
                }
                if ($sdg_out == "")
                    $sdg_out = 'None';
                return substr($sdg_out, 0, -2);
            }

            chdir(dirname(__FILE__));

            // Read the CSV file
            $file = 'dataset-catalog.csv';

            shell_exec("/usr/bin/python3 fetch_catalog.py " . $file);

            $csvData = file_get_contents($file);
            $lines = explode(PHP_EOL, $csvData);

            // Generate the table
            $table = '<table id="dataset-catalog" class="mx-auto w-100">';
            $table .= '<tr>';
            $table .= '<th>S.No</th>';
            $table .= '<th>Name</th>';
            $table .= '<th>Year</th>';
            $table .= '<th>Dataset Description (optional)</th>';
            $table .= '<th>Relevant SDGs</th>';
            $table .= '<th>Filename</th>';
            $table .= '<th>Uploaded On</th>';
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
                        } else if ($k == 3) {
                            // Parse SDGs
                            $sdgs = parse_sdgs((int) $cellData);
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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.usebootstrap.com/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
    <script>
        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

        const comparer = (idx, asc) => (a, b) => ((v1, v2) =>
            v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
        )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

        $("th").append('&nbsp; <i class="fas fa-sort"></i>');
        // do the work...
        document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
            const table = th.closest('table');
            Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
                .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
                .forEach(tr => table.appendChild(tr));
        })));

        function searchTable() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("search_table_name");
            filter = input.value.toUpperCase();
            table = document.getElementById("dataset-catalog");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>

</html>