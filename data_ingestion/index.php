<!DOCTYPE html>
<html>

<head>
    <title>Karnataka Data Lake - Druid Instance</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <style>
        body {
            padding-top: 3%;
            padding-bottom: 3%;
        }

        .card {
            transition: transform 0.1s ease;
        }

        .card:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-2">
                <img src="resources/kdl_druid_logo.png" width=192px height=192px style="display:inline-block;">
            </div>
            <div class="col-6">
                <h2 class="text-muted" style="display:inline-block;"> Druid Cluster for KDL - Welcome Page</h2>
                <p>
                    <small>
                        <b class="text-muted">Python Version</b>: <?php echo shell_exec("/usr/bin/python3 --version"); ?> <br>
                        <b class="text-muted">Operating System Version</b>: <?php echo php_uname('s') . '_' . php_uname('r') . '-' . php_uname('m'); ?>
                    </small>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-2 d-flex align-items-stretch">
                <div class="card shadow">
                    <div class="card-header">Upload Data</div>
                    <img class="card-img-top" src="resources/upload_dataset_logo.png" alt="">
                    <div class="card-body">
                        Upload one XLSX/CSV or a batch of such files to the Druid cluster.
                    </div>
                    <div class="card-footer">
                        <form action="/form-upload.php">
                            <input type="submit" class="btn btn-primary" value="Go to Upload Page" />
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-2 d-flex align-items-stretch">
                <div class="card shadow">
                    <div class="card-header">View Datasets</div>
                    <img class="card-img-top" src="resources/data_catalog_logo.png" alt="">
                    <div class="card-body">
                        A list of the datasets online in the Druid cluster. Preview the dataset columns.
                    </div>
                    <div class="card-footer">
                        <form action="/data-catalog.php">
                            <input type="submit" class="btn btn-primary" value="Go to Data Catalog" />
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-2 d-flex align-items-stretch">
                <div class="card shadow">
                    <div class="card-header">Ontology Retrieval</div>
                    <img class="card-img-top" src="resources/owl_logo.png" alt="">
                    <div class="card-body">
                        View and query created ontologies.
                    </div>
                    <div class="card-footer">
                        <form action="/retrieval.php">
                            <input type="submit" class="btn btn-primary" value="Go to Ontology" />
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-2 d-flex align-items-stretch">
                <div class="card shadow">
                    <div class="card-header">Druid Status Page</div>
                    <img class="card-img-top" src="resources/druid_status_logo.png" alt="">
                    <div class="card-body">
                        View Druid Task Log, execute direct queries to the cluster, and check log files.
                    </div>
                    <div class="card-footer">
                        <form action="/druid-status.php">
                            <input type="submit" class="btn btn-primary" value="Go to Druid Status" />
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-2 d-flex align-items-stretch">
                <div class="card shadow">
                    <div class="card-header">Documentation</div>
                    <img class="card-img-top" src="resources/documentation_logo.png" alt="">
                    <div class="card-body">
                        View the documentation on this cluster.
                    </div>
                    <div class="card-footer">
                        <form action="/doc/index.html">
                            <input type="submit" class="btn btn-primary" value="View Docs" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
        crossorigin="anonymous"></script>
</body>

</html>