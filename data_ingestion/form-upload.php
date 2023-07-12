<!DOCTYPE html>
<html>

<head>
    <title>Upload a File</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="js/generate-checkboxes.js"></script>
    <script src="js/upload-batch.js"></script>

    <style>
        body {
            background-image: linear-gradient(rgb(2, 98, 128), rgb(0, 92, 57));
            width: 100%;
            height: 100%;

        }

        .container {
            padding-top: 2%;
            padding-bottom: 5%;
        }

        textarea:focus,
        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: rgba(239, 126, 104, 0.8);
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(239, 126, 104, 0.6);
            outline: 0 none;
        }
    </style>
</head>

<body>
    <h1 style="text-align:center;padding-top:1%;color:white;">Upload Dataset</h1>
    <div class='container'>
        <div class="card shadow w-50 mx-auto">
            <div class="card-header">
                <nav>
                    <ul class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="nav-upload-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-upload" type="button" role="tab" aria-controls="nav-upload"
                                aria-selected="true">Upload Single</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="nav-upload-data-dump-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-upload-data-dump" type="button" role="tab"
                                aria-controls="nav-upload-data-dump" aria-selected="false">Upload Multiple</button>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="card-body tab-content mx-auto">
                <!--- UPLOAD DATA FORM --->
                <div class="tab-pane fade show active" id="nav-upload" role="tabpanel" aria-labelledby="nav-upload-tab">
                    <form class="form-horizontal" action="upload.php" method="post" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label for="csv_name" class=" col-form-label">Name</label>
                            <input type="text" id="csv_name" name="csv_name" placeholder="KT_Health_Data">
                        </div>
                        <div class="form-group row">
                            <label for="csv_year" class=" col-form-label">Year</label>
                            <input type="number" id="csv_year" name="csv_year" min="2000" max="2100" placeholder="2023">
                        </div>
                        <div class="form-group row">
                            <label for="csv_description" class=" col-form-label">Description</label>
                            <textarea name="csv_description" clas="form-control"
                                placeholder="Enter a short description to help users and programs classify this dataset..."
                                cols="50" rows="5"></textarea>
                        </div>
                        <div class="form-group row">
                            <div class="sdg-checkboxes">
                                <label for="csv_sdg" class="col-sm-4 col-form-label">Select relevant
                                    SDGs</label>
                                <script>
                                    generate_checkboxes("sdg-checkboxes", "csv_sdg");
                                </script>
                            </div>
                        </div>
                        <div class="form-group">
                            <br>
                            <input class="form-control" type="file" id="csv_file" name="csv_file">
                        </div>
                        <div class="form-group">
                            <br>
                            <button type="submit" class="btn btn-success w-100">Upload File</button>
                        </div>
                    </form>
                </div>
                <!--- DUMP DATA FORM -->
                <div class="tab-pane fade" id="nav-upload-data-dump" role="tabpanel"
                    aria-labelledby="nav-upload-data-dump-tab">
                    <form class="form-horizontal" action="upload-batch.php" method="post" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label for="batch_name" class=" col-form-label">Name</label>
                            <input type="text" id="batch_name" name="batch_name" placeholder="KT_MOE_DATADUMP">
                        </div>
                        <div class="form-group row">
                            <label for="batch_year" class=" col-form-label">Year</label>
                            <input type="number" id="batch_year" name="batch_year" placeholder="2023">
                        </div>
                        <div class="form-group row">
                            <label for="batch_description" class=" col-form-label">Description</label>
                            <textarea name="batch_description" id="batch_description" clas="form-control"
                                placeholder="Enter a short description to help users and programs classify this data dump..."
                                cols="50" rows="5"></textarea>
                        </div>
                        <div class="batch-checkboxes">
                            <label for="batch_sdg" class="col-sm-4 col-form-label">Select relevant
                                SDGs</label>
                            <script>
                                generate_checkboxes("batch-checkboxes", "batch_sdg")
                            </script>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-12 my-5">
                                <div class="control-group" id="fields">
                                    <div class="controls">
                                        <div class="entry input-group upload-input-group">
                                            <input class="form-control" name="batch_files[]" id="batch_files[]"
                                                type="file">
                                            <button class="btn btn-upload btn-success btn-add" type="button">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <button type="submit" class="btn btn-success w-100">Upload Dump</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
        crossorigin="anonymous"></script>
</body>

</html>