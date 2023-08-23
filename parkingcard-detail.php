<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    //Logged in checking
    if (!isset($_SESSION['UserID'])) {
        echo "<script>window.location='login.php';</script>";
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
            <div class="my-card d-flex align-items-center flex-row justify-content-between">
                <div><p class="fs-5 fw-bold m-0 p-0">บัตรจอดรถ</p></div>
                <div><a href="parkingcard.php" class="btn btn-maintheme">ย้อนกลับ</a></div>
            </div>

            <div class="container-fluid" style="position: relative; width: 100%; height: auto;">
                <!-- Parking Card lists -->
                <div class="mt-4 p-5 parking-card">
                    <!-- Body -->
                    <div>
                        <!-- Title -->
                        <div class="d-flex align-items-center">
                            <div><img src="image/Logo.png" alt="TATC Logo" width="75" class="img-fluid"></div>
                            <div class="ms-3 w-100">
                                <h1 class="parking-title">TATC Parking</h1>
                            </div>
                        </div>
                        <!-- SubTitle -->
                        <p class="mt-3 text-center fw-bold parking-subtitle">
                            บัตรประจำตัวครอบครองรถจักรยานยนต์ส่วนบุคคล
                            <br>
                            เลขที่ 0000
                        </p>
                        <!-- QR Code -->
                        <div class="mt-3 text-center">
                            <img src="image/Logo.png" alt="QR-Code" class="img-fluid" width="120">
                        </div>
                        <!-- Person Data -->
                        <div class="mt-4 d-flex gx-3">
                            <div class="w-25"><img src="image/Logo.png" alt="UserProfile" class="img-fluid" width="75"></div>
                            <div class="ms-3 w-75">
                                <div><b>ชื่อ</b> {ชื่อ}</div>
                                <div><b>แผนก</b> {แผนก}</div>
                                <div><b>ระดับชั้น</b> {ระดับชั้น}</div>
                            </div>
                        </div>
                        <!-- Vechicle Data -->
                        <div class="mt-4">
                            <div><b>ทะเบียนรถ</b> {กก1234}</div>
                            <div><b>วันออกบัตร</b> {DD/MM/YYYY}</div>
                            <div><b>วันหมดอายุ</b> {DD/MM/YYYY}</div>
                        </div>
                    </div>

                    <div class="parking-footer">
                        <p class="m-0 p-0"><b>งานปกครอง วิทยาลัยเทคนิคสัตหีบ</b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

</body>
</html>