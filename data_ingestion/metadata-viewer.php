<!DOCTYPE html>
<html>

<head>
    <title>Metadata Viewer</title>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <link rel="apple-touch-icon" sizes="180x180" href="resources/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="resources/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="resources/favicons/favicon-16x16.png">
    <link rel="manifest" href="resources/favicons/site.webmanifest">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <style>
        .json_object {
            all: unset;
        }

        ul,
        li {
            position: relative;
        }

        ul {
            list-style: none;
            padding-left: 32px;
        }

        li::before,
        li::after {
            content: "";
            position: absolute;
            left: -12px;
        }

        li::before {
            border-top: 1px solid #000;
            top: 9px;
            width: 8px;
            height: 0;
        }

        li::after {
            border-left: 1px solid #000;
            height: 100%;
            width: 0px;
            top: 2px;
        }

        ul>li:last-child::after {
            height: 8px;
        }

        h5 {
            display: inline;
        }
    </style>
</head>

<body>
    <div class="enclosed-metadata">
        <?php

        echo '<textarea hidden=true id="metadata">' . file_get_contents("metadata.json") . '</textarea>'
            ?>
    </div>

    <div class="container">
        <div class="card my-5">
            <div class="card-header">
                <h5 class="card-title" class="text-muted fw-bold"><span class="fa fa-eye"></span>&nbsp;Viewing
                    metadata.json</h5>
                <button class="btn btn-sm btn-primary" onClick="downloadMetadata()" style="float:right;"><span
                        class="fa fa-download"></span>&nbsp;Download</button>
            </div>
            <div class="card-body">
                <div class="root_tree json_tree" id="tree">
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js"
        integrity="sha512-csNcFYJniKjJxRWRV1R7fvnXrycHP6qDR21mgz1ZP55xY5d+aHLfo9/FcGDQLfn2IfngbAHd8LdfsagcCqgTcQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="js/metadata-tree.js"></script>
    <script>
        $(document).ready(() => { createJSONTree(JSON.parse($("#metadata").val()), "#tree"); })

        function downloadMetadata() {
            var metadata = $("#metadata").val();
            var blob = new Blob([metadata], { type: "text/plain;charset=utf-8" })
            saveAs(blob, "metadata.json");
        }
    </script>
</body>

</html>