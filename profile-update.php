<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    //Logged in checking
    if (!isset($_SESSION['UserID'])) {
        echo "<script>window.location='login.php';</script>";
    }
    else {
        $UserID = $_SESSION['UserID'];
        $UserType = $_SESSION['UserType'];
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Uploading</title>

    <!-- Essentials css -->
    <?php include_once 'import/css.php'; ?>

    <!-- Parking Card css -->
    <link rel="stylesheet" href="css/parkingcard.css">

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>
</head>
<body>
    
    <?php

        if (isset($_POST['Submit'])) {

            $OldUserID = $_POST['OldUserID'];

            // Student Editing Profile
            if ($_POST['UserType'] == "Student") {
                $Std_id = $_POST['std_ID'];
                $Password = $_POST['Password'];
                $Prefix = $_POST['Prefix'];
                $Firstname = $_POST['Firstname'];
                $Lastname = $_POST['Lastname'];
                $Birthday = $_POST['Birthday'];
                $Class_level = $_POST['Level'];
                $Department = $_POST['Department'];
                $Major = $_POST['Major'];
                $Address = $_POST['Address'];
                $Province = $_POST['Province'];
                $District = $_POST['District'];
                $SubDistrict = $_POST['SubDistrict'];
                $Phone = $_POST['Phone'];
                $IsUseOldImage = $_POST['IsUseOldImage'];

                if ($IsUseOldImage == "true") {
                    $sql = "UPDATE `student` 
                            SET `std_id`='$Std_id', 
                                `prefix_id`='$Prefix', 
                                `name`='$Firstname', 
                                `lastname`='$Lastname', 
                                `birthday`='$Birthday', 
                                `level_id`='$Class_level', 
                                `dept_id`='$Department', 
                                `major_id`='$Major', 
                                `tel`='$Phone', 
                                `address`='$Address', 
                                `password`='$Password', 
                                `province_id`='$Province', 
                                `district_id`='$District', 
                                `subdistrict_id`='$SubDistrict' 
                            WHERE std_id = '$OldUserID'
                    ";
                }
                else {
                    if (isset($_FILES['Image']) && $_FILES['Image']['tmp_name'] != null) {
                        $file_name = $_FILES['Image']['name'];
                        $file_data = file_get_contents($_FILES['Image']['tmp_name']);
                        $file_upload = base64_encode($file_data);
                    }
                    $sql = "UPDATE `student` 
                            SET `std_id`='$Std_id', 
                                `prefix_id`='$Prefix', 
                                `name`='$Firstname', 
                                `lastname`='$Lastname', 
                                `birthday`='$Birthday', 
                                `level_id`='$Class_level', 
                                `dept_id`='$Department', 
                                `major_id`='$Major', 
                                `tel`='$Phone', 
                                `address`='$Address', 
                                `password`='$Password',
                                `img`='$file_upload',
                                `province_id`='$Province', 
                                `district_id`='$District', 
                                `subdistrict_id`='$SubDistrict' 
                            WHERE std_id = '$OldUserID'
                    ";
                }

                SQLQuery($con, $sql);
                ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'แก้ไขข้อมูลส่วนตัวสำเร็จ',
                        // text: 'กรุณาเข้าสู่ระบบอีกครั้ง',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        window.location = 'profile.php';
                    })
                </script>
                <?php

            }
            // Personnel Editing Profile
            else {
                $Personnel_id = $_POST['Personnel_ID'];
                $Password = $_POST['Password'];
                $Firstname = $_POST['Firstname'];
                $Lastname = $_POST['Lastname'];
                $Phone = $_POST['Phone'];
                $Email = $_POST['Email'];
                $IsUseOldImage = $_POST['IsUseOldImage'];

                if ($IsUseOldImage == "true") {
                    $sql = "UPDATE `personnel` 
                            SET `personnel_id`='$Personnel_id',
                                `name`='$Firstname',
                                `lastname`='$Lastname',
                                `tel`='$Phone',
                                `password`='$Password',
                                `email`='$Email'
                            WHERE 
                                personnel_id = '$OldUserID'
                    ";
                }
                else {
                    if (isset($_FILES['Image']) && $_FILES['Image']['tmp_name'] != null) {
                        $file_name = $_FILES['Image']['name'];
                        $file_data = file_get_contents($_FILES['Image']['tmp_name']);
                        $file_upload = base64_encode($file_data);
                    }
                    $sql = "UPDATE `personnel` 
                            SET `personnel_id`='$Personnel_id',
                                `name`='$Firstname',
                                `lastname`='$Lastname',
                                `tel`='$Phone',
                                `password`='$Password',
                                `email`='$Email',
                                `img`='$file_upload'
                            WHERE 
                                personnel_id = '$OldUserID'
                    ";
                }

                SQLQuery($con, $sql);
                ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'แก้ไขข้อมูลส่วนตัวสำเร็จ',
                        // text: 'กรุณาเข้าสู่ระบบอีกครั้ง',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        window.location = 'profile.php';
                    })
                </script>
                <?php
            }
        }

    ?>

</body>
</html>