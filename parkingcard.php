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
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TATC Parking - ระบบลงทะเบียนจอดรถฯ วิทยาลัยเทคนิคสัตหีบ</title>

    <!-- Essentials css -->
    <?php include_once 'import/css.php'; ?>

    <!-- Parking Card css -->
    <link rel="stylesheet" href="css/parkingcard.css">

</head>
<body class="main-bg">

    <div>
        <?php
            //Navbar
            include_once 'import/navbar.php';

            //Sidebar
            include_once 'import/sidebar.php';
        ?>

        <div class="content">
            <!-- Title -->
            <!-- Title -->
            <div class="my-card d-flex align-items-center flex-row justify-content-between">
                <div><p class="fs-5 fw-bold m-0 p-0">บัตรจอดรถ</p></div>
                <div><a href="index.php" class="btn btn-maintheme">ย้อนกลับ</a></div>
            </div>

            <!-- Card content -->
            <div class="mt-4 my-card px-3 py-4">
                <div class="alert alert-warning m-0 p-3" role="alert">
                    ไมีมีข้อมูลของรถที่ผ่านการลงทะเบียน ณ ขณะนี้
                </div>
            </div>

            <div class="mt-4 row justify-content-between gy-4">
                <!-- Items -->
                <div class="col-sm-4">
                    <div class="my-card py-3">
                        <div class="d-flex align-items-center column-gap-3">
                            <div><img src="image/car.png" alt="Car Image" width="70" height="70"></div>
                            <div class="d-flex flex-column">
                                <p class="m-0 p-0 fw-bolder fs-5">กก1234</p>
                                <p class="m-0 p-0">ลงทะเบียนเมื่อ 01 มกราคม 2566</p>
                                <p class="m-0 p-0">สิ้นสุดเมื่อ 01 มกราคม 2566</p>
                                <div class="mt-2"><a href="parkingcard-detail.php" class="btn btn-maintheme">รายละเอียด</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

</body>
</html>