<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    //Logged in checking
    include_once('import/LoginChecking.php');

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

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

</head>
<body class="main-bg">

    <div class="my-container">

        <?php

            //Navbar
            include_once 'import/navbar.php';

            //Sidebar
            include_once 'import/sidebar.php';

            // Get Server Settings for checking permissions
            $sql = "SELECT * FROM system_settings WHERE system = 'vehicle_registration'";
            $data = SQLSelect($con, $sql);
            $SystemSettings_isRegistrationEnable = $data['is_enable'];

            if ($SystemSettings_isRegistrationEnable == "0") {
                ?>
                <script>
                    swal.fire({
                        icon: 'error',
                        title: 'ไม่สามารถลงทะเบียนได้',
                        text: 'ระบบการลงทะเบียนจอดรถฯ ไม่ได้เปิดอยู่ ณ ขณะนี้'
                    }).then((result) => {
                        window.location = 'index.php';
                    })
                </script>
                <?php
            }
        ?>

        <div class="content">

            <!-- Title -->
            <div class="my-card">
                <p class="fs-5 fw-bold">การลงทะเบียน</p>
            </div>

            <!-- Card content -->
            <div class="mt-4 my-card py-4 px-4">
                <h2 class="text-center fw-bold fs-3">กรุณาเลือกประเภทยานพาหนะ</h2>
                <div class="mt-5">
                    <div class="container mb-3">
                        <div class="row align-items-center gy-4">
                            <!-- Left Card -->
                            <div class="col-12 col-md-6">
                                <div class="card py-4" style="background-color: #f6f6f6; box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px; border: none;">
                                    <div class="d-flex flex-column text-center">
                                        <div><h3 class="fw-bold">รถจักรยานยนต์</h3></div>
                                        <div class="my-2"><img src="image/motorbike.png" width="120" class="img-fluid"></div>
                                        <div><a href="registration-form.php?VehicleType=Motorbike" class="btn btn-outline-dark border-2 py-2 px-4 fs-4 fw-bold">ลงทะเบียน</a></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Right Card -->
                            <div class="col-12 col-md-6">
                                <div class="card py-4" style="background-color: #f6f6f6; box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px; border: none;">
                                    <div class="d-flex flex-column text-center">
                                        <div><h3 class="fw-bold">รถยนต์</h3></div>
                                        <div class="my-2"><img src="image/car.png" width="120" class="img-fluid"></div>
                                        <div><a href="registration-form.php?VehicleType=Car" class="btn btn-outline-dark border-2 py-2 px-4 fs-4 fw-bold">ลงทะเบียน</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="mt-4 my-card p-3">
                <div class="alert alert-primary" role="alert">
                    <p class="fs-5 fw-bold">เอกสารที่ต้องเตรียม</p>
                    <ul class="fs-6">
                        <li>สำเนาทะเบียนยานพาหนะ</li>
                        <li>สำเนาบัตรประชาชน (ของผู้สมัคร)</li>
                        <li>สำเนาบัตรประชาชน (ของเจ้าของยานพาหนะ)</li>
                        <li>รูปถ่ายยานพาหนะ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</body>
</html>