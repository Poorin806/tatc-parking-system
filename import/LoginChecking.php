<?php

    //Logged in checking
    if (!isset($_SESSION['UserID'])) {
        echo "<script>window.location='login.php';</script>";
    }
    else {
        if ($_SESSION['UserType'] == "Student") {
            $sql = "SELECT * FROM student WHERE std_id = " . $_SESSION['UserID'];
            $UserData = SQLSelect($con, $sql);
        }
        else {
            $sql = "SELECT * FROM personnel WHERE personnel_id = " . $_SESSION['UserID'];
            $UserData = SQLSelect($con, $sql);
        }
        $UserID = $_SESSION['UserID'];
        $UserType = $_SESSION['UserType'];
}

?>