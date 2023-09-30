<?php
session_start();
if(!empty($_POST['file'])){

    if (isset($_SESSION["authed"]["regnskab"])) {
        echo " test 2 ";
        $tpath = "/data/regnskaber/transactions_" . $_SESSION["authed"]['regnskab'];

        $fn = $_POST['file'];

        $file = "$tpath/img/".$fn;

    //    $file = "/data/transactions_revi/img/20.pdf";
    //    $filename = basename($file);

    // Header content type
        header('Content-type: application/pdf');

        header('Content-Disposition: inline; filename="' . $fn . '"');

    //header('Content-Transfer-Encoding: binary');

    //header('Accept-Ranges: bytes');

    // Read the file
        @readfile($file);
    }

}





