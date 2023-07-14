<!DOCTYPE html>
<html>

<head>
    <title>Druid Status</title>
    <meta charset="UTF-8">
    <link rel="apple-touch-icon" sizes="180x180" href="resources/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="resources/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="resources/favicons/favicon-16x16.png">
    <link rel="manifest" href="resources/favicons/site.webmanifest">
    
    <link href="styles/tables.css" rel=stylesheet type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        td {
            max-width: 150px;
        }

        .table-responsive {
            max-height: 950px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h2 class="text-muted"><b>Apache&reg; Druid Status</b></h2>

        <div class="row">
            <div class="col-5 py-5">
                <div class="col-12">
                    <div class="card shadow" id="server_info">
                        <div class="card-header">
                            <h5 class="card-title">Druid Server Info</h5>
                            Server information and status of Druid on the machine.
                            <em>This assumes Druid is running on default local gateway 8888.</em>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
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
                                        echo '<tr><td>Druid Status</td><td style="color:red;font-weight:bold;"><span class="fa fa-ban"></span>&nbsp;NOT OK</td></tr>';
                                    } else {
                                        $obj = json_decode($content);
                                        $maxMem = ceil($obj->memory->maxMemory / (1024 * 1024));
                                        $freeMem = ceil($obj->memory->freeMemory / (1024 * 1024));
                                        $percentFree = ceil(($freeMem / $maxMem) * 100.0);
                                        echo '<tr><td>Druid Status</td><td style="color:green;font-weight:bold;"><span class="fa fa-signal"></span>&nbsp;OK</td></tr>';
                                        echo "<tr><td>Druid version</td><td>" . $obj->version . "</td></tr>";
                                        echo "<tr><td>Max Memory</td><td>" . $maxMem . " MB" . '</td></tr>';
                                        echo "<tr><td>Free Memory</td><td>" . $freeMem . " MB (" . $percentFree . '%)' . '</td></tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 py-5">
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="card-title">Upload Log</h5>
                            The log file of the Python utilities.
                        </div>
                        <div class="card-body">
                            <?php
                            $logfile = fopen("log.txt", "r") or 0;
                            if ($logfile)
                                echo '<pre style="max-height: 300px">' . escapeshellarg(fread($logfile, filesize("log.txt"))) . "</pre>";
                            else
                                echo "Log file not found."
                                    ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="card-title">Druid Monitor</h5>
                                Execute SQL queries to the Druid instance right from the browser itself.
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-11">
                                        <input type="text" id="druid_query" style="width:100%;"
                                            placeholder="Enter SQL query..." />
                                    </div>
                                    <div class="col-1">
                                        <button id="monitor_button" onClick="sendQueryToPHP()"
                                            class="btn btn-sm rounded-pill btn-primary"><i class="fa fa-play"></i></button>
                                    </div>
                                </div>
                                <div class="row py-2">
                                    <div style="text-align:center;" id="spinner_query">
                                    </div>
                                </div>
                                <div class="row py-2">
                                    <div class="result" id="query_results">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-7 py-5">
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="card-title">Druid Task Log</h5>
                            See previous tasks, their status and associated error messages.
                        </div>
                        <div class="card-body">
                            <?php
                            $content = shell_exec("/usr/bin/python3 get_tasks.py");
                            $allOk = true;
                            if ($content == false) {
                                $allOk = false;
                            }
                            $json_data = json_decode($content);
                            switch (json_last_error()) {
                                case JSON_ERROR_DEPTH:
                                case JSON_ERROR_CTRL_CHAR:
                                case JSON_ERROR_SYNTAX:
                                    $allOk = false;
                                    break;
                                case JSON_ERROR_NONE:
                                    break;
                            }
                            if ($allOk == false) {
                                echo '<p class="text-muted">No Druid Task data available.</p>';
                            } else {
                                echo '<div class="table-responsive">';
                                echo '<table class="table">';
                                echo "<tr>";
                                echo '<th>Task ID</th>';
                                echo '<th>Datasource</th>';
                                echo '<th>Started</th>';
                                echo "<th>Error Message</th>";
                                echo "<th>Task Status</th>";
                                echo "</tr>";
                                foreach ($json_data as $task) { //foreach element in $arr
                                    echo "<tr>";
                                    echo '<td>' . $task->task_id . '</td>';
                                    echo '<td>' . $task->datasource . '</td>';
                                    // Explode time into date and time fractions (this is ensured by Druid that the format is T Z)
                                    $datetime = explode("T", $task->created_time);
                                    $datetime[1] = str_replace("Z", "", $datetime[1]);
                                    echo '<td>' . $datetime[0] . "<br>" . $datetime[1] . '</td>';
                                    echo '<td style="max-width:50px">' . $task->error_msg . '</td>';
                                    $color_args = "";
                                    if ($task->status == 'FAILED') {
                                        $color_args = 'style="background-color: #A15050;color: #FFFFFF"';
                                    } else {
                                        $color_args = 'style="background-color: #50A150;color: #FFFFFF"';
                                    }
                                    echo '<td ' . $color_args . '>' . $task->status . '</td>';
                                    echo "</tr>";
                                }
                                echo "</table>";
                                echo "</div>";
                            }

                            ?>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="https://cdn.usebootstrap.com/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
        <script src="js/query-table-builder.js"></script>
        <script src="js/druid-query.js"></script>
</body>

</html>