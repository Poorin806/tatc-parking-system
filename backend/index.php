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

    <!-- Default Essentials css -->
    <?php include_once '../import/css.php'; ?>
    
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
                <h1 class="m-0 p-0 fs-5 fw-bold">ภาพรวม</h1>
            </div>

            <!-- Summary Card -->
            <div class="mt-3 row align-items-center gy-4">
                <!-- Total Vehicle -->
                <div class="col-sm-3">
                    <div class="my-card">
                        <p class="m-0 p-0 fs-5 mb-2">รถที่ลงทะเบียนทั้งหมด</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <?php
                                    $sql = "SELECT * FROM parking_card WHERE issue_date <= expiration_date AND parking_card.isReceived = 1";
                                    $num = SQLSelectNumRow($con, $sql);
                                ?>
                                <p class="m-0 p-0 fs-4 fw-bolder mb-2"><?php echo $num ?> คัน</p>
                                <a href="registration-overall.php" class="btn btn-maintheme-outline">รายละเอียด</a>
                            </div>
                            <div>
                                <img src="../image/ParkingCard.png" alt="img" class="img-fluid" width="70">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Car -->
                <div class="col-sm-3">
                    <div class="my-card">
                        <p class="m-0 p-0 fs-5 mb-2">รถยนต์ทั้งหมด</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <?php
                                    $sql = "SELECT parking_card.parkingCard_id
                                            FROM parking_card, registration, vehicle, vehicle_type
                                            WHERE
                                                (parking_card.issue_date <= parking_card.expiration_date) AND
                                                (parking_card.regis_id = registration.regis_id) AND
                                                (registration.vehicle_id = vehicle.vehicle_id) AND
                                                (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                                (vehicle_type.vehiType_id = 1) AND 
                                                parking_card.isReceived = 1
                                        ";
                                    $num = SQLSelectNumRow($con, $sql);
                                ?>
                                <p class="m-0 p-0 fs-4 fw-bolder mb-2"><?php echo $num ?> คัน</p>
                                <a href="registration-overall.php?VehicleType=Car" class="btn btn-maintheme-outline">รายละเอียด</a>
                            </div>
                            <div>
                                <img src="../image/car.png" alt="img" class="img-fluid" width="70">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Motorbike -->
                <div class="col-sm-3">
                    <div class="my-card">
                        <p class="m-0 p-0 fs-5 mb-2">รถจักรยานยนต์ทั้งหมด</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                            <?php
                                    $sql = "SELECT parking_card.parkingCard_id
                                            FROM parking_card, registration, vehicle, vehicle_type
                                            WHERE
                                                (parking_card.issue_date <= parking_card.expiration_date) AND
                                                (parking_card.regis_id = registration.regis_id) AND
                                                (registration.vehicle_id = vehicle.vehicle_id) AND
                                                (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                                (vehicle_type.vehiType_id = 2) AND 
                                                parking_card.isReceived = 1
                                        ";
                                    $num = SQLSelectNumRow($con, $sql);
                                ?>
                                <p class="m-0 p-0 fs-4 fw-bolder mb-2"><?php echo $num ?> คัน</p>
                                <a href="registration-overall.php?VehicleType=Motorbike" class="btn btn-maintheme-outline">รายละเอียด</a>
                            </div>
                            <div>
                                <img src="../image/motorbike.png" alt="img" class="img-fluid" width="70">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Request -->
                <div class="col-sm-3">
                    <div class="my-card">
                        <p class="m-0 p-0 fs-5 mb-2">คำขอลงทะเบียนจอดรถฯ</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <?php
                                    $sql = "SELECT *
                                            FROM registration_status_detail
                                            WHERE
                                                (regisStatus_id = 2) AND
                                                (regis_id IN (SELECT regis_id 
                                                            FROM registration_status_detail
                                                            GROUP BY regis_id
                                                            HAVING MAX(regisStatus_id) <=2
                                                            )
                                                )
                                    ";
                                    $request_num = SQLSelectNumRow($con, $sql);
                                ?>
                                <p class="m-0 p-0 fs-4 fw-bolder mb-2"><?php echo number_format($request_num) ?> คำขอ</p>
                                <a href="registration-request.php" class="btn btn-maintheme-outline">รายละเอียด</a>
                            </div>
                            <div>
                                <img src="../image/document.png" alt="img" class="img-fluid" width="70">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Checking Request -->
                <div class="col-sm-3">
                    <div class="my-card">
                        <p class="m-0 p-0 fs-5 mb-2">คำขอรอการตรวจสภาพรถ</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <?php
                                    $sql = "SELECT *
                                            FROM registration_status_detail
                                            WHERE
                                                (regisStatus_id = 3) AND
                                                (regis_id IN (SELECT regis_id 
                                                            FROM registration_status_detail
                                                            GROUP BY regis_id
                                                            HAVING MAX(regisStatus_id) <=3
                                                            )
                                                )
                                    ";
                                    $request_num = SQLSelectNumRow($con, $sql);
                                ?>
                                <p class="m-0 p-0 fs-4 fw-bolder mb-2"><?php echo number_format($request_num) ?> คำขอ</p>
                                <a href="registration-checking.php" class="btn btn-maintheme-outline">รายละเอียด</a>
                            </div>
                            <div>
                                <img src="../image/document_checking.png" alt="img" class="img-fluid" width="70">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sticker Printing Request -->
                <div class="col-sm-3">
                    <div class="my-card">
                        <p class="m-0 p-0 fs-5 mb-2">คำขอรอการปริ้นสติ๊กเกอร์</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <?php
                                    $sql = "SELECT *
                                            FROM registration_status_detail
                                            WHERE
                                                (regisStatus_id = 4) AND
                                                (regis_id IN (SELECT regis_id 
                                                            FROM registration_status_detail
                                                            GROUP BY regis_id
                                                            HAVING MAX(regisStatus_id) <= 4
                                                            ) 
                                                ) AND
                                                (comment = 'prepare to print sticker')
                                    ";
                                    $request_num = SQLSelectNumRow($con, $sql);
                                ?>
                                <p class="m-0 p-0 fs-4 fw-bolder mb-2"><?php echo number_format($request_num) ?> คำขอ</p>
                                <a href="sticker.php" class="btn btn-maintheme-outline">รายละเอียด</a>
                            </div>
                            <div>
                                <img src="../image/parkingcard_2.png" alt="img" class="img-fluid" width="70">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No approve Request -->
                <div class="col-sm-3">
                    <div class="my-card">
                        <p class="m-0 p-0 fs-5 mb-2">คำขอที่ไม่ผ่าน</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <?php
                                    $sql = "SELECT *
                                            FROM registration_status_detail
                                            WHERE
                                                (regisStatus_id IN (6, 7, 8, 9))
                                            GROUP BY regis_id
                                    ";
                                    $request_num = SQLSelectNumRow($con, $sql);
                                ?>
                                <p class="m-0 p-0 fs-4 fw-bolder mb-2"><?php echo number_format($request_num) ?> คำขอ</p>
                                <a href="registration-overall.php?Status=Fail" class="btn btn-maintheme-outline">รายละเอียด</a>
                            </div>
                            <div>
                                <img src="../image/fail_document.png" alt="img" class="img-fluid" width="70">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Request -->
                <div class="col-sm-3">
                    <div class="my-card">
                        <p class="m-0 p-0 fs-5 mb-2">การรายงานปัญหา</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <?php
                                    $sql = "SELECT * FROM notification WHERE admin_receive_status = '0'";
                                    $request_num = SQLSelectNumRow($con, $sql);
                                ?>
                                <p class="m-0 p-0 fs-4 fw-bolder mb-2"><?php echo number_format($request_num) ?> ครั้ง</p>
                                <a href="report.php" class="btn btn-maintheme-outline">รายละเอียด</a>
                            </div>
                            <div>
                                <img src="../image/report.png" alt="img" class="img-fluid" width="70">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section #2 -->
            <div class="mt-3 row align-items-start justify-content-between gy-4">
                <!-- Section #2 - Left -->
                <!-- Shortcut Menu -->
                <div class="col-sm-6">
                    <div class="my-card mb-3">
                        <p class="m-0 p-0 fw-bold fs-5">ทางลัด</p>
                    </div>
                    <div class="my-card">
                        <div class="row align-items-center justify-content-between row-gap-3">
                            <!-- Registration Request -->
                            <div class="col-sm-3 text-center">
                                <a href="registration-request.php" class="text-decoration-none">
                                    <div class="btn btn-maintheme d-flex flex-column align-items-center justify-content-center m-0 p-0" style="width: 100px; height: 100px;">
                                        <i class="bi bi-file-earmark-richtext-fill fs-2"></i>
                                        <p class="m-0 p-0" style="font-size: 14px;">จัดการคำขอ</p>
                                    </div>
                                </a>
                            </div>
                            <!-- Vehicle Checking -->
                            <div class="col-sm-3 text-center">
                                <a href="registration-checking.php" class="text-decoration-none">
                                    <div class="btn btn-maintheme d-flex flex-column align-items-center justify-content-center m-0 p-0" style="width: 100px; height: 100px;">
                                        <i class="bi bi-car-front-fill fs-2"></i>
                                        <p class="m-0 p-0" style="font-size: 14px;">ตรวจสภาพรถ</p>
                                    </div>
                                </a>
                            </div>
                            <!-- Parking Card -->
                            <div class="col-sm-3 text-center">
                                <a href="sticker.php" class="text-decoration-none">
                                    <div class="btn btn-maintheme d-flex flex-column align-items-center justify-content-center m-0 p-0" style="width: 100px; height: 100px;">
                                        <i class="bi bi-person-vcard-fill fs-2"></i>
                                        <p class="m-0 p-0" style="font-size: 14px;">สติ๊กเกอร์</p>
                                    </div>
                                </a>
                            </div>
                            <!-- announcement -->
                            <div class="col-sm-3 text-center">
                                <a href="announcement.php" class="text-decoration-none">
                                    <div class="btn btn-maintheme d-flex flex-column align-items-center justify-content-center m-0 p-0" style="width: 100px; height: 100px;">
                                        <i class="bi bi-chat-square-text fs-2"></i>
                                        <p class="m-0 p-0" style="font-size: 14px;">ประชาสัมพันธ์</p>
                                    </div>
                                </a>
                            </div>
                            <!-- Report -->
                            <div class="col-sm-3 text-center">
                                <a href="report.php" class="text-decoration-none">
                                    <div class="btn btn-maintheme d-flex flex-column align-items-center justify-content-center m-0 p-0" style="width: 100px; height: 100px;">
                                        <i class="bi bi-megaphone-fill fs-2"></i>
                                        <p class="m-0 p-0" style="font-size: 14px;">รายงานปัญหา</p>
                                    </div>
                                </a>
                            </div>

                            <!-- User Management -->
                            <div class="col-sm-3 text-center">
                                <a href="user-management.php" class="text-decoration-none">
                                    <div class="btn btn-maintheme d-flex flex-column align-items-center justify-content-center m-0 p-0" style="width: 100px; height: 100px;">
                                        <i class="bi bi-person-circle fs-2"></i>
                                        <p class="m-0 p-0" style="font-size: 14px;">จัดการข้อมูลผู้ใช้</p>
                                    </div>
                                </a>
                            </div>
                            <!-- Personnel Management -->
                            <div class="col-sm-3 text-center">
                                <a href="admin.php" class="text-decoration-none">
                                    <div class="btn btn-maintheme d-flex flex-column align-items-center justify-content-center m-0 p-0" style="width: 100px; height: 100px;">
                                        <i class="bi bi-person-gear fs-2"></i>
                                        <p class="m-0 p-0" style="font-size: 14px;">ผู้ดูแลระบบ</p>
                                    </div>
                                </a>
                            </div>
                            <!-- System Settings -->
                            <div class="col-sm-3 text-center">
                                <a href="system.php" class="text-decoration-none">
                                    <div class="btn btn-maintheme d-flex flex-column align-items-center justify-content-center m-0 p-0" style="width: 100px; height: 100px;">
                                        <i class="bi bi-gear-fill fs-2"></i>
                                        <p class="m-0 p-0" style="font-size: 14px;">ตั้งค่าระบบ</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Section #2 - Right -->
                <div class="col-sm-6">
                    <div class="my-card mb-3">
                        <p class="m-0 p-0 fw-bold fs-5">สรุปข้อมูล</p>
                    </div>
                    <div class="my-card">
                        <table class="table table-stripped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 60%;">หัวข้อ</th>
                                    <th style="width: 40%;">ข้อมูลสรุป</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Percentage of Successful Registration and Inprogress Registration -->
                                <?php
                                    $sql = "SELECT
                                                (SELECT COUNT(regis_id) FROM parking_card) AS Successful_Registration,
                                                
                                                (SELECT COUNT(DISTINCT regis_id)
                                                    FROM registration_status_detail
                                                    WHERE
                                                        (registration_status_detail.regisStatus_id IN (1, 2, 3, 4)) AND
                                                        (registration_status_detail.regisStatus_id NOT IN (5, 6, 7, 8, 9))
                                                ) AS InProgress_Registration,
                                                
                                                (
                                                    ((SELECT COUNT(regis_id) FROM parking_card) * 100) / (SELECT COUNT(DISTINCT regis_id)
                                                        FROM registration_status_detail
                                                        WHERE
                                                            (registration_status_detail.regisStatus_id IN (1, 2, 3, 4)) AND
                                                            (registration_status_detail.regisStatus_id NOT IN (5, 6, 7, 8, 9))
                                                    )
                                                ) AS Percentage
                                    ";
                                    $data = SQLSelect($con, $sql);
                                ?>
                                <!-- Row 1 -->
                                <tr>
                                    <td>
                                        ข้อมูลผู้ใช้ที่อยู่ระหว่างการลงทะเบียนทั้งหมด <br> (<?php echo $data['Successful_Registration'] . " จาก " . $data['InProgress_Registration'] ?> คำขอ)
                                    </td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                            <div class="fw-bold progress-bar progress-bar-striped progress-bar-animated" style="width: <?php echo number_format($data['Percentage'], 0) ?>%">
                                                <?php echo number_format($data['Percentage'], 2) . "%" ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Percentage of Successful Registration and Failed Registration -->
                                <?php
                                    $sql = "SELECT
                                                (SELECT COUNT(regis_id) FROM parking_card) AS Successful_Registration,
                                                
                                                (SELECT COUNT(DISTINCT regis_id)
                                                    FROM registration_status_detail
                                                    WHERE
                                                        (registration_status_detail.regisStatus_id NOT IN (1, 2, 3)) AND
                                                        (registration_status_detail.regisStatus_id IN (6, 7, 8, 9))
                                                ) AS Failed_Registration,
                                                
                                                (
                                                    (SELECT COUNT(DISTINCT regis_id)
                                                        FROM registration_status_detail
                                                        WHERE
                                                            (registration_status_detail.regisStatus_id NOT IN (1, 2, 3)) AND
                                                            (registration_status_detail.regisStatus_id IN (6, 7, 8, 9))
                                                    ) * 100 / (SELECT COUNT(regis_id) FROM parking_card)
                                                ) AS Percentage
                                    ";
                                    $data = SQLSelect($con, $sql);
                                ?>
                                <!-- Row 2 -->
                                <tr>
                                    <td>
                                        ข้อมูลผู้ใช้ที่ไม่ผ่านการละทะเบียนทั้งหมด <br> (<?php echo $data['Failed_Registration'] . " จาก " . $data['Successful_Registration'] ?> คำขอ)
                                    </td>
                                    <td>
                                        <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                            <div class="fw-bold progress-bar progress-bar-striped progress-bar-animated" style="width: <?php echo number_format($data['Percentage'], 0) ?>%">
                                                <?php echo number_format($data['Percentage'], 2) . "%" ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Essentials JavaScript -->
    <?php include_once '../import/js.php'; ?>
    

</body>
</html>