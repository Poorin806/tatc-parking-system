<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    //Logged in checking
    include_once('import/LoginChecking.php');
    
    //Vehicle Searching
    if (isset($_POST['Search'])) {
        if (isset($_POST['VehicleID'])) {
            $VehicleID = $_POST['VehicleID'];
            echo "<script>window.location = 'status.php?VehicleID=$VehicleID';</script>";
        }
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

    <!-- Status css -->
    <link rel="stylesheet" href="css/status.css">

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

            <?php
                if ($_SESSION['UserType'] == "Student") {
                    $sql = "SELECT * FROM registration WHERE std_id = '" . $_SESSION['UserID'] . "'";
                }
                else {
                    $sql = "SELECT * FROM registration WHERE personnel_id = '" . $_SESSION['UserID'] . "'";
                }
                $num_regis_status = SQLSelectNumRow($con, $sql);
            ?>

            <!-- Title -->
            <div class="my-card">
                <div class="d-flex flex-column row-gap-4 flex-sm-row justify-content-sm-between align-items-start align-items-sm-center">
                    <div class="">
                        <p class="fs-5 fw-bold m-0 p-0">ตรวจสอบสถานะ</p>
                    </div>
                    <div class="align-self-center">
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <div class="d-flex flex-column justify-content-start flex-sm-row gap-3">
                                <div class="d-flex align-items-center column-gap-2">
                                    ค้นหา
                                    <input type="text" class="form-control" placeholder="ทะเบียนรถ" name="VehicleID">
                                </div>
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <button type="submit" name="Search" class="btn btn-maintheme">
                                        <i class="bi bi-search"></i>
                                    </button>
                                    <!-- Clear Filter -->
                                    <a href="status.php" class="btn btn-danger">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </a>
                                    <!-- Back -->
                                    <a href="index.php" class="btn btn-maintheme">
                                        <i class="bi bi-arrow-left-circle-fill"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Card content -->
            <?php

                if ($num_regis_status == 0) {
                    //If registration is not found
                    ?>
                    <div class="mt-4 my-card px-3 py-4">
                        <div class="alert alert-warning m-0 p-3" role="alert">
                            ไมีมีข้อมูลการลงทะเบียน ณ ขณะนี้
                        </div>
                    </div>
                    <?php
                }
                else {
                    if ($_SESSION['UserType'] == "Student") {
                        if (isset($_GET['VehicleID'])) {
                            $VehicleID = $_GET['VehicleID'];
                            $sql = "SELECT 
                                        registration_status_detail.regis_id,
                                        registration_status_detail.regisStatus_id,
                                        registration_status.regisStatus_name,
                                        registration_status_detail.rsd_date,
                                        registration_status_detail.rsd_time,
                                        registration.std_id,
                                        registration.personnel_id,
                                        registration.vehicle_id,
                                        vehicle.vehiType_id,
                                        vehicle_type.vehiType_name,
                                        registration_status_detail.comment
                                    FROM 
                                        registration_status_detail, registration, registration_status, vehicle, vehicle_type,
                                        (SELECT regis_id, MAX(regisStatus_id) AS regisStatus_id FROM registration_status_detail GROUP BY regis_id) AS Temp
                                    WHERE
                                        (registration_status_detail.regis_id = Temp.regis_id) AND
                                        (registration_status_detail.regisStatus_id = Temp.regisStatus_id) AND
                                        (registration_status_detail.regis_id = registration.regis_id) AND
                                        (registration_status_detail.regisStatus_id = registration_status.regisStatus_id) AND
                                        (registration.vehicle_id = vehicle.vehicle_id) AND
                                        (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                        (registration.std_id = '$UserID') AND
                                        (registration.vehicle_id LIKE '%$VehicleID%')
                                    ORDER BY registration_status_detail.regis_id DESC
                                ";
                        }
                        else {
                            $sql = "SELECT 
                                        registration_status_detail.regis_id,
                                        registration_status_detail.regisStatus_id,
                                        registration_status.regisStatus_name,
                                        registration_status_detail.rsd_date,
                                        registration_status_detail.rsd_time,
                                        registration.std_id,
                                        registration.personnel_id,
                                        registration.vehicle_id,
                                        vehicle.vehiType_id,
                                        vehicle_type.vehiType_name,
                                        registration_status_detail.comment
                                    FROM 
                                        registration_status_detail, registration, registration_status, vehicle, vehicle_type,
                                        (SELECT regis_id, MAX(regisStatus_id) AS regisStatus_id FROM registration_status_detail GROUP BY regis_id) AS Temp
                                    WHERE
                                        (registration_status_detail.regis_id = Temp.regis_id) AND
                                        (registration_status_detail.regisStatus_id = Temp.regisStatus_id) AND
                                        (registration_status_detail.regis_id = registration.regis_id) AND
                                        (registration_status_detail.regisStatus_id = registration_status.regisStatus_id) AND
                                        (registration.vehicle_id = vehicle.vehicle_id) AND
                                        (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                        (registration.std_id = '$UserID')
                                    ORDER BY registration_status_detail.regis_id DESC
                                ";
                        }
                    }
                    else {
                        if (isset($_GET['VehicleID'])) {
                            $VehicleID = $_GET['VehicleID'];
                            $sql = "SELECT 
                                        registration_status_detail.regis_id,
                                        registration_status_detail.regisStatus_id,
                                        registration_status.regisStatus_name,
                                        registration_status_detail.rsd_date,
                                        registration_status_detail.rsd_time,
                                        registration.std_id,
                                        registration.personnel_id,
                                        registration.vehicle_id,
                                        vehicle.vehiType_id,
                                        vehicle_type.vehiType_name,
                                        registration_status_detail.comment
                                    FROM 
                                        registration_status_detail, registration, registration_status, vehicle, vehicle_type,
                                        (SELECT regis_id, MAX(regisStatus_id) AS regisStatus_id_latest FROM registration_status_detail GROUP BY regis_id) AS Temp
                                    WHERE
                                        (registration_status_detail.regis_id = Temp.regis_id) AND
                                        (registration_status_detail.regisStatus_id = Temp.regisStatus_id_latest) AND
                                        (registration_status_detail.regis_id = registration.regis_id) AND
                                        (registration_status_detail.regisStatus_id = registration_status.regisStatus_id) AND
                                        (registration.vehicle_id = vehicle.vehicle_id) AND
                                        (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                        (registration.personnel_id = '$UserID') AND
                                        (registration.vehicle_id LIKE '%$VehicleID%')
                                    ORDER BY registration_status_detail.regis_id DESC
                            ";
                        }
                        else {
                            $sql = "SELECT 
                                        registration_status_detail.regis_id,
                                        registration_status_detail.regisStatus_id,
                                        registration_status.regisStatus_name,
                                        registration_status_detail.rsd_date,
                                        registration_status_detail.rsd_time,
                                        registration.std_id,
                                        registration.personnel_id,
                                        registration.vehicle_id,
                                        vehicle.vehiType_id,
                                        vehicle_type.vehiType_name,
                                        registration_status_detail.comment
                                    FROM 
                                        registration_status_detail, registration, registration_status, vehicle, vehicle_type,
                                        (SELECT regis_id, MAX(regisStatus_id) AS regisStatus_id_latest FROM registration_status_detail GROUP BY regis_id) AS Temp
                                    WHERE
                                        (registration_status_detail.regis_id = Temp.regis_id) AND
                                        (registration_status_detail.regisStatus_id = Temp.regisStatus_id_latest) AND
                                        (registration_status_detail.regis_id = registration.regis_id) AND
                                        (registration_status_detail.regisStatus_id = registration_status.regisStatus_id) AND
                                        (registration.vehicle_id = vehicle.vehicle_id) AND
                                        (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                        (registration.personnel_id = '$UserID')
                                    ORDER BY registration_status_detail.regis_id DESC
                            ";
                        }
                    }

                    $Search_num_rows = SQLSelectNumRow($con, $sql);

                    if ($Search_num_rows > 0) {
                        foreach (SQLSelectLoop($con, $sql) as $data) {
                            $VehicleID = $data['vehicle_id'];
                            ?>
                            <div class="mt-4 my-card">
                                <div class="card my-2" style="background-color: #f6f6f6; box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px; border: none;">
                                    <div class="card-body">
                                        <h4 class="fw-bold fs-5">คำขอลงทะเบียนที่ <?php echo $data['regis_id'] ?></h4>
                                        <div class="d-flex flex-column align-items-center flex-lg-row align-items-lg-end gap-3">
                                            <!-- Left Side -->
                                            <div class="d-flex flex-column flex-lg-row column-gap-3 align-items-start w-100 w-lg-50">
                                                <div class="text-center">
                                                    <img src="image/<?php echo ($data['vehiType_id'] == 1) ? "car.png" : "motorbike.png"; ?>" alt="Vehicle IMG" class="status-img">
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <div><b>ประเภทรถ :</b> <?php echo $data['vehiType_name'] ?></div>
                                                    <div><b>ทะเบียนรถ :</b> <?php echo $data['vehicle_id'] ?></div>
                                                    <div><b>สถานะ :</b> 
                                                        <span class="fw-bold <?php
                                                            switch ($data['regisStatus_id']) {
                                                                case 1:
                                                                case 2:
                                                                case 3:
                                                                    echo "text-warning";
                                                                    break;
                                                                case 4:
                                                                    echo "text-primary";
                                                                    break;
                                                                case 5:
                                                                    // Check expiration date
                                                                    $expiration_sql = "SELECT parking_card.* 
                                                                                        FROM parking_card
                                                                                        JOIN registration ON parking_card.regis_id = registration.regis_id
                                                                                        WHERE
                                                                                            (parking_card.issue_date <= parking_card.expiration_date) AND
                                                                                            (registration.vehicle_id = '$VehicleID')
                                                                    ";
                                                                    $expiration_num = SQLSelectNumRow($con, $expiration_sql);
                                                                    if ($expiration_num == 0) {
                                                                        echo "text-danger";
                                                                    }
                                                                    else {
                                                                        echo "text-success";
                                                                    }
                                                                    break;
                                                                case 6:
                                                                case 7:
                                                                case 8:
                                                                case 9:
                                                                    echo "text-danger";
                                                                    break;
                                                            }
                                                        ?>">
                                                        <?php
                                                            switch ($data['regisStatus_id']) {
                                                                case 1:
                                                                    echo "อยู่ระหว่างการส่งคำขอ";
                                                                    break;
                                                                case 2:
                                                                    echo "รอการตรวจสอบ";
                                                                    break;
                                                                case 3:
                                                                    echo "อยู่ระหว่างการตรวจสอบ";
                                                                    break;
                                                                case 4:
                                                                    echo "ผ่านการตรวจสอบ, กรุณารับเอกสาร";
                                                                    break;
                                                                case 5:
                                                                    // Check expiration date
                                                                    if ($expiration_num == 0) {
                                                                        echo "หมดอายุการลงทะเบียน";
                                                                    }
                                                                    else {
                                                                        echo "ลงทะเบียนเสร็จสิ้น";
                                                                    }
                                                                    break;
                                                                case 6:
                                                                case 7:
                                                                case 8:
                                                                case 9:
                                                                    echo "ไม่ผ่านการตรวจสอบ";
                                                                    break;
                                                            }
                                                        ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Right Side -->
                                            <div class="d-flex flex-column flex-lg-row column-gap-3 align-items-start align-items-lg-end w-100 w-lg-50 justify-content-end">
                                                <div class="d-flex flex-row flex-lg-column align-items-center column-gap-3 mt-lg-0">
                                                    <!-- Icon Checking (Sync with database) -->
                                                    <div><i class="fs-1
                                                        <?php
                                                            switch ($data['regisStatus_id']) {
                                                                case 1 :    //Inputing
                                                                case 2:     //Sending Successfully
                                                                case 3:     //Checking
                                                                case 4:     //Result
                                                                    echo "bi bi-exclamation-circle-fill text-warning";
                                                                    break;
                                                                case 5:
                                                                    if ($expiration_num != 0) {
                                                                        echo "bi-check-circle-fill text-success";
                                                                    }
                                                                    else {
                                                                        echo "bi bi-exclamation-circle-fill text-danger";
                                                                    }
                                                                    break;
                                                                case 6:
                                                                case 7:
                                                                case 8:
                                                                case 9:
                                                                    echo "bi bi-x-circle-fill text-danger";
                                                                    break;
                                                            }
                                                        ?>
                                                    "></i></div>
    
                                                    <!-- More Detail -->
                                                    <div><a href="status-detail.php?ID=<?php echo $data['regis_id'] ?>" class="btn btn-dark">รายละเอียด</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    else {
                        ?>
                        <div class="mt-4 my-card px-3 py-4">
                            <div class="alert alert-warning m-0 p-3" role="alert">
                                ไม่พบข้อมูลที่ค้นหา
                            </div>
                        </div>
                        <?php
                    }
                }

            ?>
            
        </div>
    </div>

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

</body>
</html>