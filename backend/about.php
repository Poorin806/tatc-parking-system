<?php

    //Import connect.php file
    include_once('../import/connect.php');

    //Import SQL Query file
    include_once('../import/SQLQuery.php');

    //Logged in checking
    include_once('LoginChecking.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TATC Parking - ระบบลงทะเบียนจอดรถฯ วิทยาลัยเทคนิคสัตหีบ</title>

    <!-- Essentials css -->
    <?php include_once '../import/css.php'; ?>

    <!-- Homepage css -->
    <link rel="stylesheet" href="../css/homepage.css">

    <!-- Essentials JS -->
    <?php include_once '../import/js.php'; ?>
    
</head>
<body class="main-bg">

    <div class="my-container">

        <?php

            //Navbar
            include_once 'navbar.php';

            //Sidebar
            include_once 'sidebar.php';
        ?>

        <div class="content">

            <!-- Title -->
            <div class="my-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="fs-5 fw-bold m-0 p-0">เกี่ยวกับ</p>
                    </div>
                    <div>
                        <a href="index.php" class="btn btn-maintheme">ย้อนกลับ</a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="my-4 my-card p-4">
                <p class="fs-4 fw-bold">
                    การออกแบบและพัฒนาระบบการลงทะเบียนยานพาหนะ <span class="text-primary">วิทยาลัยเทคนิคสัตหีบ</span>
                </p>
                <p>
                    เว็บแอปพลิเคชั่นนี้คือโครงงานและเป็นส่วนหนึ่งของการศึกษาตามหลักสูตรประกาศนียบัตรวิชาชีพชั้นสูง (ปวส.) 
                    พ.ศ. 2563 สาขางานนักพัฒนาซอฟต์แวร์คอมพิวเตอร์สาขาวิชาเทคโนโลยีสารสนเทศ วิทยาลัยเทคนิคสัตหีบ ปีการศึกษา 2566
                </p>

                <hr>

                <!-- Our Team -->
                <div class="mt-4">
                    <h3 class="fs-4 fw-bolder">คณะผู้จัดทำ</h3>
                    <div class="d-flex flex-sm-row flex-column align-items-center align-items-sm-start justify-content-center justify-content-sm-around column-gap-5 row-gap-3">
                        <!-- Left Side -->
                        <div class="card w-sm-50 w-100">
                            <div class="card-body p-0" style="position: relative;">
                                <img src="../image/nattakhon.jpg" alt="Nattakhon Kretpadit (Profile)" class="img-fluid" style="width: 100%; height: 100%;">
                            </div>
                            <div class="card-footer">
                                <!-- Personal Info -->
                                <h4 class="mx-0 my-2 p-0 fw-bold">ณัฏฐากร เกษประดิษฐ</h4>
                                <p>
                                    นักศึกษาแผนกวิชาเทคโนโลยีสารสนเทศ สาขางานนักพัฒนาซอฟต์แวร์คอมพิวเตอร์ฯ วิทยาลัยเทคนิคสัตหีบ
                                </p>
                                <!-- Contact -->
                                <p class="m-0 p-0">
                                    <i class="bi bi-telephone-fill" style="color: #242729;"></i> (+66) 655420151
                                </p>
                                <p class="m-0 p-0">
                                    <i class="bi bi-envelope-fill"></i> nattakhonketpradit@gmail.com
                                </p>
                            </div>
                        </div>
                        <!-- Right Side -->
                        <div class="card w-sm-50 w-100">
                            <div class="card-body p-0" style="position: relative;">
                                <img src="../image/poorin.jpg" alt="Poorin Thatsakhon (Profile)" class="img-fluid" style="width: 100%; height: 100%;">
                            </div>
                            <div class="card-footer">
                                <!-- Personal Info -->
                                <h4 class="mx-0 my-2 p-0 fw-bold">ภูรินทร์ ทัศคร</h4>
                                <p>
                                    นักศึกษาแผนกวิชาเทคโนโลยีสารสนเทศ สาขางานนักพัฒนาซอฟต์แวร์คอมพิวเตอร์ฯ วิทยาลัยเทคนิคสัตหีบ
                                </p>
                                <!-- Contact -->
                                <p class="m-0 p-0">
                                    <i class="bi bi-telephone-fill" style="color: #242729;"></i> (+66) 942041773
                                </p>
                                <p class="m-0 p-0">
                                    <i class="bi bi-envelope-fill"></i> Poorin806@gmail.com
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</body>
</html>