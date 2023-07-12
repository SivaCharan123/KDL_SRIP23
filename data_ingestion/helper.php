<?php
// Upload error codes
$uploadErrorCodes = array(
    "UPLOAD_ERR_OK",
    "UPLOAD_ERR_INI_SIZE",
    "UPLOAD_ERR_FORM_SIZE",
    "UPLOAD_ERR_PARTIAL",
    "UPLOAD_ERR_NO_FILE",
    "UPLOAD_ERR_NO_TMP_DIR",
    "UPLOAD_ERR_CANT_WRITE",
    "UPLOAD_ERR_EXTENSION"
);

function error($msg)
{
    echo '<div class="alert alert-danger shadow" role="alert">';
    echo '<b><i class="fa fa-x"></i>&nbsp;Error: </b>' . $msg;
    echo '</div>';
}

function notify($msg)
{
    echo '<div class="alert alert-warning shadow" roler="alert">';
    echo '<b><i class="fa fa-exclamation"></i>&nbsp;Notification: </b>' . $msg;
    echo '</div>';
}

function success($msg)
{
    echo '<div class="alert alert-success shadow" roler="alert">';
    echo '<b><i class="fa fa-exclamation"></i>&nbsp;Success: </b>' . $msg;
    echo '</div>';
}

function validate_data($DATA)
{
    $DATA = trim($DATA);
    $DATA = stripslashes($DATA);
    $DATA = htmlspecialchars($DATA);
    return $DATA;
}

// Helper function for SDG goals
function set_bit($NUMBER, $BIT)
{
    return ($NUMBER) | (1 << $BIT);
}

function get_SDG_flag($flg_data)
{
    if (!isset($_POST[$flg_data])) {
        return 0;
    }
    $FORM_SDG_DATA = $_POST[$flg_data];
    $SDG_FLAG = 0;
    foreach ($FORM_SDG_DATA as $SDG) {
        $SDG_FLAG = set_bit($SDG_FLAG, $SDG);
    }
    return $SDG_FLAG;
}

function isFileEmpty($filename)
{
    if ($_FILES[$filename]["name"] == "") {
        return true;
    }
    return false;
}

function isFileArrayEmpty($name)
{
    foreach ($_FILES[$name]['name'] as $filename) {
        if ($filename != "") {
            return false;
        }
    }
    return true;
}
?>