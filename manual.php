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
    <link rel="stylesheet" href="<?php echo $root_folder ?>css/manual.css">

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
                <div>
                    <p class="fs-5 fw-bold m-0 p-0">คู่มือการลงทะเบียน</p>
                </div>
                
                <div class="d-flex align-items-center column-gap-3">
                    <div class="fs-5 dropdown">
                        <a class="btn btn-maintheme dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            ขั้นตอนต่าง ๆ
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">ขั้นตอนการการลงทะเบียน</a></li>
                            <li><a class="dropdown-item" href="#">ขั้นตอนการตรวจสอบสถานะการลงทะเบียน</a></li>
                        </ul>
                    </div>
                    <a href="index.php" class="btn btn-maintheme">ย้อนกลับ</a>
                </div>
            </div>

            <!-- Card content -->
            <div class="mt-4 my-card px-3 py-4">
              <div class="manual">
                <!-- ขั้นตอนการลงทะเบียน -->
                <div class="manualRegistration">
                    <div><h4 class="fw-bold">ขั้นตอนการการลงทะเบียน</h4></div>
                        <div class="mx-5">
                            <p class="fs-5 mt-4">1. เลือกแท็บทางซ้าย แล้วคลิกไปที่ลงทะเบียนจอดรถ<p>
                            <div class="regis01" align="center">
                                <img src="imagemanual/1menu.png" class="imgM01 img-fluid">
                            </div>

                            <p class="fs-5 mt-5">2. เลือกประเภทยานพาหนะที่ต้องการลงทะเบียนจอดรถ<p>
                            <div class="regis02" align="center">
                                <img src="imagemanual/2vehicleType.png" class="imgM01 img-fluid">
                            </div>

                            <p class="fs-5 mt-5">3. ลงทะเบียนยานพาหนะ<p>
                            <div class="regis03" align= "center">

                                <!-- Part 1 User Data -->
                                <p class="mt-4 ">ส่วนที่ 1 ข้อมูลส่วนตัว ในส่วนนี้จะมีข้อมูลให้กับผู้ใช้อยู่แล้ว
                                    <br><img src="imagemanual/3registration-1.png" class="imgM01 img-fluid mt-2">
                                </p>
                                
                                <!-- Part 2 Vehicle-->
                                <p class="mt-4">ส่วนที่ 2 ยานพาหนะ กรอกข้อมูลยานพาหนะของผู้ใช้ (กรอกข้อมูลให้ครบทุกช่อง)
                                    <br><img src="imagemanual/4registration-2.png" class="imgM01 img-fluid mt-2">
                                </p>
                                
                                <!-- Part 3 File Upload-->
                                <p class="mt-4">ส่วนที่ 3 อัพโหลดไฟล์เอกสาร
                                    <br>อัพโหลดไฟล์สำเนาทะเบียนยานพาหนะ, อัพโหลดไฟล์สำเนาบัตรประชาชน(เจ้าของกรรมสิทธิ์ยานพาหนะ),
                                    <br>อัพโหลดไฟล์สำเนาบัตรประชาชน (ผู้ใช้ยานพาหนะ) ผู้ใช้ต้องอัพโหลดไฟล์ให้ครบถึงจะสามารถลงทะเบียนได้
                                    <br><img src="imagemanual/5registration-3.png" class="imgM01 img-fluid mt-2">
                                </p>
                                
                                <!-- Part 4 Image Upload-->
                                <p class="mt-4">ส่วนที่ 4 อัพโหลดรูปภาพยานพาหนะ (อัพโหลดรูปภาพให้ครบ)
                                    <br><img src="imagemanual/6registration-4.png" class="imgM01 img-fluid mt-2">
                                </p>

                                <!-- Part 5 Confirm -->
                                <p class="mt-4">ส่วนที่ 5 ปุ่มตกลง และยกเลิก 
                                    <br>ปุ่มตกลง ถ้าหากผู้ใช้กรอกข้อมูลครบ และตรวจสอบข้อมูลแล้ว ให้กดที่ปุ่มตกลงเพื่อทำการลงทะเบียน
                                    <br>ปุ่มยกเลิก ถ้าหากผู้ใช้ต้องการที่จะยกเลิกข้อมูลด้วยเหตุใดก็ตาม สามารถกดยกเลิกการทะเบียนได้ทันที
                                    <br><img src="imagemanual/7registration-confirm.png" class="imgConfirm img-fluid mt-2">  
                                </p>
                                
                            </div>
                        </div>    
                    </div>
                </div>
                
                <!-- ขั้นตอนการตรวจสอบสภาพรถ -->
                <div class="manualStatus mt-5">
                    <div><h4 class="fw-bold ">ขั้นตอนการตรวจเช็คสถานะรถที่ลงทะเบียน</h4></div>
                    <div class="mx-5">
                        <p class="fs-5 mt-5">1. เลือกแท็บทางซ้าย คลิกไปที่ตรวจสอบสถานะ<p>
                        <div class="regis02" align="center">
                            <img src="imagemanual/8menuStatus.png" class="imgM01 img-fluid">
                        </div>

                        <p class="fs-5 mt-5">2. เลือกรถที่ลงทะเบีบน คลิกไปที่รายละเอียด<p>
                        <div class="regis02" align="center">
                            <img src="imagemanual/8menuStatus-1.png" class="imgM01 img-fluid">
                        </div>

                        <p class="fs-5 mt-5">3. หน้าแสดงสถานะของการลงเบียน<p>
                        <div class="regis02" align="center">
                            <img src="imagemanual/8menuStatus-2.png" class="imgM01 img-fluid">
                        </div>

                        <p class="fs-5 mt-5">4. หลังจากกดปุ่มดาวน์โหลดเอกสาร จะเข้ามาที่หน้าตัวอย่างแบบฟอร์มการลงทะเบียน <p>
                        <div class="regis03" align= "center">

                                <!-- Part 1 -->
                                <p class="mt-4 ">ส่วนที่ 1 ตัวอย่างแบบฟอร์มลงทะเบียน กดปุ่มดาวน์โหลดเอกสารที่มุมขวาล่าง
                                    <br><img src="imagemanual/8menuStatus-3.png" class="imgM01 img-fluid mt-2">
                                </p>

                                <!-- Part 2 -->
                                <p class="mt-4 ">ส่วนที่ 2 หลังจากกดปุ่มดาวน์โหลดแล้ว เอกสารจะถูกดาวน์โหลดเข้าอุปกรณ์ของผู้ใช้ (คอมพิวเตอร์/โทรศัพท์)
                                    <br><img src="imagemanual/9-Document.png" class="imgM01 img-fluid mt-2">
                                </p>

                                <!-- Part 3 -->
                                <p class="mt-4 ">ส่วนที่ 3 เซ็นชื่อตรงช่องที่มีชื่อของผู้ขอลงทะเบียน
                                    <!-- <br><img src="imagemanual/..." class="imgM01 img-fluid mt-2"> -->
                                </p>

                                <!-- Part 4 -->
                                <p class="mt-4 ">ส่วนที่ 4 หน้ากระดาษจะถูกแบ่งออกเป็น 2 ส่วน 
                                    <br>ให้ผู้ใช้เซ็นชื่อในช่องที่มีชื่อของตัวเองเซ็นชื่อตรงช่องที่มีชื่อของผู้ขอลงทะเบียน
                                    <!-- <br><img src="imagemanual/..." class="imgM01 img-fluid mt-2"> -->
                                </p>

                                <!-- Part 5 -->
                                <p class="mt-4 ">ส่วนที่ 5 สามารถเซ็นชื่อได้ 2 แบบ ดังนี้
                                    <br>1. เซ็นชื่อในรูปแบบอิเล็กทรอนิกส์
                                    <br>2. ปริ้นเอกสารแล้วเซ็นชื่อ    
                                    <!-- <br><img src="imagemanual/..." class="imgM01 img-fluid mt-2"> -->
                                </p>
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