<!DOCTYPE html>
<html>

<head>
    <title>Retrieve Data</title>
    <link href="styles/tables.css" rel=stylesheet type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            padding: 0% 3% 3% 3%;
        }

        .tab-content {
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 10px;
        }

        .nav-tabs {
            margin-bottom: 0;
        }

        .ace_editor {
            border: 2px solid lightgray;
            margin: auto;
            height: 100px;
            width: 100%;
        }

        .ace_editor:hover {
            filter: drop-shadow(0 0 2px black);
        }

        .table-responsive {
            max-height: 600px;
        }
    </style>
</head>

<body>
    <h3 class="text-muted"><b>Data Cluster Retrieval Page</b></h3>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab1">Retrieve by SDG</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab2">Custom Query</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab3">See Available Ontologies</button>
        </li>
    </ul>

    <div class="container-fluid px-0 py-0">

        <div class="alert alert-danger" id="nocsv-alert">
            <strong>Error:</strong> There's no CSV loaded to download! Query something.
        </div>

        <div class="tab-content">
            <!--- SDG QUERY TAB -->
            <div class="tab-pane fade show active" id="tab1">
                <div class="row">
                    <div class="col-3">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="card-title">Select SDGs</h5>
                            </div>
                            <div class="card-body">
                                <em>I'm looking for the datasets which are relevant to:</em>
                                <small>
                                    <form id="sdg_query_form" class="py-2">
                                        <div class="form-group row">
                                            <div class="sdg_checkboxes">
                                            </div>
                                        </div>

                                        <div class="py-3">
                                            <button class="btn btn-primary" type="submit">Filter</button>
                                        </div>
                                    </form>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-9">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="card-title">Query Result</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" id="sdg_query_results">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- CUSTOM QUERY TAB -->
            <div class="tab-pane fade" id="tab2">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="card-title">Enter your SQL query</h5>
                            </div>
                            <div class="card-body px-0 py-0">
                                <div id="custom_query_input">SELECT * FROM dataset;</div>
                            </div>
                            <div class="card-footer d-flex flex-row-reverse">
                                <button class="btn btn-success" id="custom_sql_query_button"
                                    onClick="executeSQLQueryFromEditor()"><i class="fa fa-play"></i>&nbsp;Execute
                                    SQL</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row py-3">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="card-title">Query Results</h5>
                            </div>
                            <div class="card-body">

                                <label for="show_entry_limit">Show entries:</label>

                                <select onchange="changeNumberOfEntries()" name="show_entry_limit"
                                    id="show_entry_limit">
                                    <option selected="selected" value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>

                                <div class="table-responsive" id="custom_query_results_table">
                                    <em>Preview of the results will be shown here</em>.
                                </div>
                            </div>
                            <div class="card-footer d-flex flex-row-reverse">
                                <button class="btn btn-sm btn-success" onClick="downloadCustomQueryCSV()"><span
                                        class="fa fa-download"></span>&nbsp;
                                    Download CSV</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab3">
                <div class="row">
                    <div class="col-12">
                        No ontologies available yet.
                    </div>
                </div>
            </div>

            <script src="https://code.jquery.com/jquery-3.7.0.min.js"
                integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
            <script src="https://cdn.usebootstrap.com/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js"
                integrity="sha512-csNcFYJniKjJxRWRV1R7fvnXrycHP6qDR21mgz1ZP55xY5d+aHLfo9/FcGDQLfn2IfngbAHd8LdfsagcCqgTcQ=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="js/ace-1.5.0/ace.js"></script>
            <script src="js/generate-checkboxes.js"></script>
            <script src="js/query-table-builder.js"></script>
            <script src="js/retrieval.js"></script>
</body>

</html>