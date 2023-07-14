<?php
    $content = file_get_contents("./metadata.json");
    header('Content-type: application/json');
    echo $content;
?>