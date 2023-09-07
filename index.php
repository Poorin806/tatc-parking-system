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

    <!-- Homepage css -->
    <link rel="stylesheet" href="css/homepage.css">
    
</head>
<body class="main-bg">

    <div class="my-container">

        <?php

            //Navbar
            include_once 'import/navbar.php';

            //Sidebar
            include_once 'import/sidebar.php';
        ?>

        <div class="content">

            <!-- Title -->
            <div class="my-card">
                <p class="fs-5 fw-bold">ข้อมูลเบื้องต้น</p>
            </div>

            <!-- Card content -->
            <div class="mt-4">
                <div class="row align-items-center gy-3">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <!-- All Cars Registed -->
                        <div class="my-menucard">
                            <h1>รถที่ลงทะเบียนทั้งหมด</h1>
                            <?php
                                if ($UserType == "Student") {
                                    $sql = "SELECT * 
                                    FROM 
                                        parking_card, registration
                                    WHERE 
                                        parking_card.regis_id = registration.regis_id AND
                                        parking_card.isReceived = 1 AND
                                        parking_card.issue_date <= parking_card.expiration_date AND
                                        registration.std_id = '" . $_SESSION['UserID'] . "'";
                                }
                                else {
                                    $sql = "SELECT * 
                                    FROM 
                                        parking_card, registration
                                    WHERE 
                                        parking_card.regis_id = registration.regis_id AND
                                        parking_card.isReceived = 1 AND
                                        registration.personnel_id = '" . $_SESSION['UserID'] . "'";
                                }
                                $TotalVehicle = SQLSelectNumRow($con, $sql);
                            ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h1><?php echo $TotalVehicle ?> คัน</h1>
                                </div>
                                <div>
                                    <img src="image/Logo.png" width="70px" height="70px" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Motocycle Registed -->
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="my-menucard">
                            <h1>รถจักรยานยนต์ที่ลงทะเบียน</h1>
                            <?php
                                if ($UserType == "Student") {
                                    $sql = "SELECT *
                                    FROM 
                                        parking_card, registration, vehicle, vehicle_type
                                    WHERE
                                        parking_card.regis_id = registration.regis_id AND
                                        registration.vehicle_id = vehicle.vehicle_id AND
                                        vehicle.vehiType_id = vehicle_type.vehiType_id AND
                                        vehicle_type.vehiType_id = 2 AND
                                        parking_card.isReceived = 1 AND
                                        parking_card.issue_date <= parking_card.expiration_date AND
                                        registration.std_id = '" . $_SESSION['UserID'] . "'";
                                }
                                else {
                                    $sql = "SELECT *
                                    FROM 
                                        parking_card, registration, vehicle, vehicle_type
                                    WHERE
                                        parking_card.regis_id = registration.regis_id AND
                                        registration.vehicle_id = vehicle.vehicle_id AND
                                        vehicle.vehiType_id = vehicle_type.vehiType_id AND
                                        vehicle_type.vehiType_id = 2 AND
                                        parking_card.issue_date <= parking_card.expiration_date AND
                                        registration.personnel_id = '" . $_SESSION['UserID'] . "'";
                                }
                                $TotalMotorbike = SQLSelectNumRow($con, $sql);
                            ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h1><?php echo $TotalMotorbike ?> คัน</h1>
                                </div>
                                <div>
                                    <img src="image/motorbike.png" width="70px" height="70px" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Car Registed -->
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="my-menucard">
                            <h1>รถยนต์ที่ลงทะเบียน</h1>
                            <?php
                                if ($UserType == "Student") {
                                    $sql = "SELECT *
                                    FROM 
                                        parking_card, registration, vehicle, vehicle_type
                                    WHERE
                                        parking_card.regis_id = registration.regis_id AND
                                        registration.vehicle_id = vehicle.vehicle_id AND
                                        vehicle.vehiType_id = vehicle_type.vehiType_id AND
                                        vehicle_type.vehiType_id = 1 AND
                                        parking_card.isReceived = 1 AND
                                        parking_card.issue_date <= parking_card.expiration_date AND
                                        registration.std_id = '" . $_SESSION['UserID'] . "'";
                                }
                                else {
                                    $sql = "SELECT *
                                    FROM 
                                        parking_card, registration, vehicle, vehicle_type
                                    WHERE
                                        parking_card.regis_id = registration.regis_id AND
                                        registration.vehicle_id = vehicle.vehicle_id AND
                                        vehicle.vehiType_id = vehicle_type.vehiType_id AND
                                        vehicle_type.vehiType_id = 1 AND
                                        registration.personnel_id = '" . $_SESSION['UserID'] . "'";
                                }
                                $TotalCar = SQLSelectNumRow($con, $sql);
                            ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h1><?php echo $TotalCar ?> คัน</h1>
                                </div>
                                <div>
                                    <img src="image/car.png" width="70px" height="70px" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Reported Notification -->
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <a href="notification.php" class="text-decoration-none" style="color: #242729;">
                            <div class="my-menucard">
                                <h1>การถูกรายงานปัญหา</h1>
                                <?php
                                    if ($UserType == "Student") {
                                        $sql = "SELECT *
                                        FROM notification, notification_type
                                        WHERE
                                            notification.notiType_id = notification_type.notiType_id AND
                                            notification.std_id = '" . $_SESSION['UserID'] . "'";
                                    }
                                    else {
                                        $sql = "SELECT *
                                        FROM notification, notification_type
                                        WHERE
                                            notification.notiType_id = notification_type.notiType_id AND
                                            notification.personnel_id = '" . $_SESSION['UserID'] . "'";
                                    }
                                    $TotalReport = SQLSelectNumRow($con, $sql);
                                ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h1><?php echo $TotalReport ?> ครั้ง</h1>
                                    </div>
                                    <div>
                                        <img src="image/Report.png" width="70px" height="70px" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Parking Card -->
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="my-menucard">
                            <h1>บัตรอนุญาตจอดรถ</h1>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="parkingcard.php" class="btn btn-maintheme fs-5">แสดงบัตร</a>
                                </div>
                                <div>
                                    <img src="image/ParkingCard.png" width="70px" height="70px" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcements -->
            <div class="mt-4 my-card">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-0 p-0 fs-5 fw-bold">ประชาสัมพันธ์ - ข่าวสาร</p>
                    </div>
                    <div class="col text-end">
                        <a href="announcement.php" class="btn btn-maintheme">เพิ่มเติม</a>
                    </div>
                </div>
            </div>

            <div class="mt-4 my-card p-4">
                <?php
                    $sql = "SELECT * FROM announcement ORDER BY an_id DESC LIMIT 5";

                    $announcement_row = SQLSelectNumRow($con, $sql);
                    if ($announcement_row > 0) {
                        foreach (SQLSelectLoop($con, $sql) as $data) {
                            ?>
                            <div class="card mb-3">
                                <!-- Card Header -->
                                <div class="card-header">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <!-- Left Side (Title) -->
                                        <div>
                                            <p class="m-0 p-0 fs-6">
                                                <span class="fw-bold m-0 p-0"><?php echo formatDateTimeThai($data['an_date']) ?> : </span>
                                                <?php echo $data['title'] ?>
                                            </p>
                                        </div>
                                        <!-- Right Side (Detail BTN) -->
                                        <div>
                                            <?php
                                                if ($data['content'] != null) {
                                                    ?>
                                                    <button class="btn btn-maintheme" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_CardBody_<?php echo $data['an_id'] ?>" aria-expanded="false" aria-controls="collapse_CardBody_<?php echo $data['an_id'] ?>">
                                                        รายละเอียด
                                                    </button>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
    
                                <!-- Card Body (Content) -->
                                <?php
                                    if ($data['content'] != null) {
                                        ?>
                                        <div class="collapse" id="collapse_CardBody_<?php echo $data['an_id'] ?>">
                                            <div class="card-body">
                                                <pre class="m-0 p-0 fs-6" style="font-family: 'Noto Sans Thai', Arial, Helvetica, sans-serif;"><?php echo $data['content'] ?></pre>
                                                <?php
                                                    if ($data['file'] != null) {
                                                        ?>
                                                        <hr>
                                                        <div class="mt-3">
                                                            <a href="file_downloading.php?DownloadID=<?php echo $data['an_id'] ?>" class="m-0 btn btn-maintheme">ดาวน์โหลดเอกสาร</a>
                                                        </div>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                            <?php
                        }
                    }
                    else {
                        ?>
                        <div class="alert alert-warning m-0" role="alert">
                            ไม่มีข้อมูลการประกาศ
                        </div>
                        <?php
                    }

                    
                ?>
            </div>

        </div>

    </div>

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>
    

</body>
</html>