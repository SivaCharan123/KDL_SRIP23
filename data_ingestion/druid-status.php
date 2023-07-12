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
    <style>
    td
    {
        max-width: 300px;
    }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h2 class="text-muted"><b>Apache&reg; Druid Status</b></h2>

        <div class="row">
            <div class="col-3 py-5">
                <div class="card shadow" id="server_info">
                    <div class="card-header">
                        Druid Server Info
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
                                    echo '<tr><td>Druid Status</td><td style="color:red;font-weight:bold;">NOT OK</td></tr>';
                                } else {
                                    $obj = json_decode($content);
                                    $maxMem = ceil($obj->memory->maxMemory / (1024 * 1024));
                                    $freeMem = ceil($obj->memory->freeMemory / (1024 * 1024));
                                    $percentFree = ceil(($freeMem / $maxMem) * 100.0);
                                    echo '<tr><td>Druid Status</td><td style="color:green;font-weight:bold;">OK</td></tr>';
                                    echo "<tr><td>Druid version</td><td>" . $obj->version . "</td><tr>";
                                    echo "<tr><td>Max Memory</td><td>" . $maxMem . " MB" . '</td></tr>';
                                    echo "<tr><td>Free Memory</td><td>" . $freeMem . " MB (" . $percentFree . '%)' . '</td></tr>';
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9 py-5">
                <div class="card shadow">
                    <div class="card-header">Druid Task Log</div>
                    <div class="card-body mx-auto">
                        <?php 
                            $content = shell_exec("/usr/bin/python3 get_tasks.py");
                            $allOk = true;
                            if($content == false)
                            {
                                $allOk = false;
                            }
                            $json_data = json_decode($content);
                            switch(json_last_error()) {
                                case JSON_ERROR_DEPTH:
                                case JSON_ERROR_CTRL_CHAR:
                                case JSON_ERROR_SYNTAX:
                                    $allOk = false;
                                    break;
                                case JSON_ERROR_NONE:
                                    break;
                            }
                            if($allOk == false)
                            {
                                echo '<p class="text-muted">No Druid Task data available.</p>';
                            }
                            else
                            {
                                echo '<div class="table-responsive">';
                                echo "<table>";
                                echo "<tr>";
                                echo '<th>Task ID</th>';
                                echo '<th>Datasource</th>';
                                echo "<th>Error Message</th>";
                                echo "<th>Task Status</th>";
                                echo "</tr>";
                                foreach($json_data as $task) { //foreach element in $arr
                                    echo "<tr>";
                                    echo '<td>' . $task->task_id . '</td>';
                                    echo '<td>' . $task->datasource . '</td>';

                                    $color_args = "";
                                    echo '<td>' . $task->error_msg . '</td>';
                                    if($task->status == 'FAILED')
                                    {
                                        $color_args = 'style="background-color: #A15050;color: #FFFFFF"';
                                    }
                                    else
                                    {
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
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.usebootstrap.com/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>