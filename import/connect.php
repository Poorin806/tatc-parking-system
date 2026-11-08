<?php

    $root_folder = "/TATCParking" . "/";
  
    $Username = "root";
    $Password = "";
    $db = "tatcparking";

    //MySQL Connection
    $con = mysqli_connect("localhost", $Username, $Password, $db);
    mysqli_set_charset($con, 'UTF8');
    date_default_timezone_set('Asia/Bangkok');

    //Database Connection Check
    if (mysqli_connect_errno()) {   //If Failed to connect
        echo "Failed to connect Database" . mysqli_connect_errno();
    }

    //Time zone Settings
    date_default_timezone_set('Asia/Bangkok');

    session_start();

?>