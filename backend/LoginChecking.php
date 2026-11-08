<?php

    //Logged in checking
    if (!isset($_SESSION['AdminID'])) {
        echo "<script>window.location='login.php';</script>";
    }
    else {
        $sql = "SELECT * FROM admin WHERE admin_id = " . $_SESSION['AdminID'];
        $UserData = SQLSelect($con, $sql);
        $UserID = $_SESSION['AdminID'];
    }

?>