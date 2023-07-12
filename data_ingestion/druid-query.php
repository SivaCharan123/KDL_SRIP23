<?php 
    $myjson = json_encode(shell_exec("/usr/bin/python3 query_exec.py " . escapeshellarg($_POST["query"])));
    header('Content-type: application/json');
    echo $myjson;
?>