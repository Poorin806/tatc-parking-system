<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    //Announcements File Downloading
    if (isset($_GET['DownloadID'])) {
        $an_id = $_GET['DownloadID'];
        $sql = "SELECT * FROM announcement WHERE an_id = '$an_id'";
        $result = SQLSelectNumRow($con, $sql);

        if ($result > 0) {
            $data = SQLSelect($con, $sql);
            $file = base64_decode($data['file']);
            $filename = $data['filename'];

            // ตั้งค่า HTTP Response
            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            echo $file;
        }
        else {
            echo "<script>window.location = 'index.php';</script>";
        }     
    }


?>