<!DOCTYPE html>
<html>
    <head>
        <title>Upload a File</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">    
        <link href="styles/google-font.css" rel="stylesheet" type="text/css" />
        <link href="styles/styles.css" rel="stylesheet" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    </head>
    <body>
        <div class='section'>
            <div class='gfont'>
                <center><h1>UPLOAD DATASET</h1></center>
                <div class="container-lg">
                    <div class="row justify-content-center my-5">
                        <div class="col-lg-6">
                            <form class="form-horizontal" action="upload.php" method="post" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <label for="csv_name" class="col-sm-2 col-form-label">Name</label>
                                    <input type="text" id="csv_name" name="csv_name" placeholder="KT_Health_Data">
                                </div>
                                <div class="form-group row">
                                    <label for="csv_year" class="col-sm-2 col-form-label">Year</label>
                                    <input type="number" id="csv_year" name="csv_year" min="2000" max="2100" placeholder="2023">
                                </div>
                                <div class="form-group row">
                                    <label for="csv_description" class="col-sm-2 col-form-label">Description</label>
                                    <textarea name="csv_description" clas="form-control" placeholder="Enter a short description to help users and programs classify this dataset..." cols="50" rows="10"></textarea>
                                </div>
                                <div class="form-group row">
                                    <div class="sdg-checkboxes">
                                    <label for="csv_sdg[]" class="col-sm-4 col-form-label">Select relevant SDGs</label>
                                        <script src="js/generate-checkboxes.js"></script>
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
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    </body>
</html>