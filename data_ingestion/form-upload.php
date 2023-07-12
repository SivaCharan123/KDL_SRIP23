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
    <link href="styles/google-font.css" rel="stylesheet" type="text/css" />
    <link href="styles/styles.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="js/upload-batch.js"></script>
    <script src="js/generate-checkboxes.js"></script>
</head>

<body>
    <div class='container'>
        <div class='gfont'>
            <center>
                <h1>UPLOAD DATASET</h1>
            </center>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-upload-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-upload" type="button" role="tab" aria-controls="nav-upload"
                        aria-selected="true">Upload Single</button>
                    <button class="nav-link" id="nav-upload-data-dump-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-upload-data-dump" type="button" role="tab"
                        aria-controls="nav-upload-data-dump" aria-selected="false">Upload Batch (Dump)</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <!--- UPLOAD DATA FORM --->
                <div class="tab-pane fade show active" id="nav-upload" role="tabpanel" aria-labelledby="nav-upload-tab">
                    <div class="container-lg">
                        <div class="row justify-content-center my-5">
                            <div class="col-lg-6">
                                <form class="form-horizontal" action="upload.php" method="post"
                                    enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label for="csv_name" class="col-sm-2 col-form-label">Name</label>
                                        <input type="text" id="csv_name" name="csv_name" placeholder="KT_Health_Data">
                                    </div>
                                    <div class="form-group row">
                                        <label for="csv_year" class="col-sm-2 col-form-label">Year</label>
                                        <input type="number" id="csv_year" name="csv_year" min="2000" max="2100"
                                            placeholder="2023">
                                    </div>
                                    <div class="form-group row">
                                        <label for="csv_description" class="col-sm-2 col-form-label">Description</label>
                                        <textarea name="csv_description" clas="form-control"
                                            placeholder="Enter a short description to help users and programs classify this dataset..."
                                            cols="50" rows="5"></textarea>
                                    </div>
                                    <div class="form-group row">
                                        <div class="sdg-checkboxes">
                                            <label for="csv_sdg[]" class="col-sm-4 col-form-label">Select relevant
                                                SDGs</label>
                                            <script>
                                                generate_checkboxes("sdg-checkboxes", "csv_sdg")
                                            </script>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <br>
                                        <input class="form-control" type="file" id="csv_file" name="csv_file">
                                    </div>
                                    <div class="form-group">
                                        <br>
                                        <button type="submit" class="btn btn-primary mb-3">Upload to Druid</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--- DUMP DATA FORM -->
                <div class="tab-pane fade" id="nav-upload-data-dump" role="tabpanel"
                    aria-labelledby="nav-upload-data-dump-tab">
                    <div class="container-lg">
                        <div class="row justify-content-center my-5">
                            <div class="col-lg-6">
                                <form class="form-horizontal" action="upload-batch.php" method="post"
                                    enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label for="batch_name" class="col-sm-2 col-form-label">Name</label>
                                        <input type="text" id="batch_name" name="batch_name"
                                            placeholder="KT_MOE_DATADUMP">
                                    </div>
                                    <div class="form-group row">
                                        <label for="batch_year" class="col-sm-2 col-form-label">Year</label>
                                        <input type="number" id="batch_year" name="batch_year" placeholder="2023">
                                    </div>
                                    <div class="form-group row">
                                        <label for="batch_description"
                                            class="col-sm-2 col-form-label">Description</label>
                                        <textarea name="batch_description" id="batch_description" clas="form-control"
                                            placeholder="Enter a short description to help users and programs classify this data dump..."
                                            cols="50" rows="5"></textarea>
                                    </div>
                                    <div class="batch-checkboxes">
                                        <label for="batch_sdg[]" class="col-sm-4 col-form-label">Select relevant
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
                                                        <input class="form-control" name="batch_files[]"
                                                            id="batch_files[]" type="file">
                                                        <button class="btn btn-upload btn-success btn-add"
                                                            type="button">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <button type="submit" class="btn btn-primary mb-3">Upload Dump</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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