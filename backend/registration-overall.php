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
                <div class="row align-items-center justify-content-between row-gap-3">
                    <div class="col-sm-3">
                        <h1 class="m-0 p-0 fs-5 fw-bold">คำขอการลงทะเบียน</h1>
                    </div>
                    <div class="col-sm-9">
                        <div class="d-flex flex-column flex-sm-row align-items-center gap-2 justify-content-end">
                            Filter
                            <div class="align-self-start align-self-sm-center d-flex align-items-center gap-2">
                                <!-- User Type -->
                                <select id="UserType" class="form-select" onchange="Filter()">
                                    <option value="0" selected>- กลุ่มผู้คน -</option>
                                    <option value="1">นักศึกษา</option>
                                    <option value="2">บุคลากร</option>
                                </select>
                                <!-- User Type -->
                                <select id="VehicleType" class="form-select" onchange="Filter()">
                                    <option value="0" selected>- ประเภทรถ -</option>
                                    <option value="1">รถจักรยานยนต์</option>
                                    <option value="2">รถยนต์</option>
                                </select>
                                <!-- Status -->
                                <select id="Status" class="form-select" onchange="Filter()">
                                    <option value="0" selected>- สถานะ -</option>
                                    <option value="1">ลงทะเบียนเสร็จสิ้น</option>
                                    <option value="2">ไม่ผ่านการอนุมัติ</option>
                                </select>
                                <!-- Row Record -->
                                <select id="RowRecord" class="form-select" onchange="Filter()">
                                    <option value="0" selected>- จำนวนแถว -</option>
                                    <option value="50">50 แถว</option>
                                    <option value="100">100 แถว</option>
                                    <option value="500">500 แถว</option>
                                    <option value="1000">1,000 แถว</option>
                                </select>
                                <!-- Clear Filter -->
                                <a href="registration-overall.php" class="btn btn-danger">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Table -->
            <div class="mt-4 my-card p-4" id="StudentTable" style="display: block;">
                <p class="fs-5 fw-bold mb-3">คำขอ - นักศึกษา</p>
                <table class="table table-stripped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>คำขอที่</th>
                            <th>ชื่อ - สกุล</th>
                            <th>แผนกวิชา (ชั้นปี)</th>
                            <th>ทะเบียน</th>
                            <th>ประเภทยานพาหนะ</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            // Row Record (SQL Limit)
                            if (isset($_GET['RowRecord'])) {
                                $RowRecord = $_GET['RowRecord'];
                                if ($RowRecord == "All") {
                                    $RowRecord = 99999;
                                }
                            }
                            else {
                                $RowRecord = 99999;
                            }

                            // Status Type (Registration Status)
                            if (isset($_GET['Status'])) {
                                if ($_GET['Status'] == "Success") {
                                    $Status = "5";
                                }
                                else if ($_GET['Status'] == "Fail") {
                                    $Status = "6, 7, 8, 9";
                                }
                                else {
                                    $Status = "5, 6, 7, 8, 9";
                                }
                            }
                            else {
                                $Status = "5, 6, 7, 8, 9";
                            }

                            if (isset($_GET['VehicleType']) && $_GET['VehicleType'] == "Car") {
                                $VehicleType = 1;
                            }
                            else if (isset($_GET['VehicleType']) && $_GET['VehicleType'] == "Motorbike") {
                                $VehicleType = 2;
                            }
                            else {
                                $VehicleType = "1, 2";
                            }

                            $sql = "SELECT
                                        registration.regis_id, prefix.prefix_name, student.name, student.lastname, student.std_id,
                                        department.dept_name, class_level.level_name, vehicle.vehiType_id, vehicle_type.vehiType_name,
                                        registration_status_detail.rsd_date, registration_status_detail.rsd_time, registration.vehicle_id, province.province_name, 
                                        registration_status.regisStatus_name, registration_status_detail.regisStatus_id
                                    FROM
                                        registration_status_detail, registration, 
                                        student, prefix, department, class_level,
                                        vehicle, vehicle_type, province, registration_status
                                    WHERE
                                        (registration_status_detail.regis_id = registration.regis_id) AND
                                        (registration_status_detail.regisStatus_id = registration_status.regisStatus_id) AND
                                        (registration.std_id = student.std_id) AND
                                        (registration.std_id IS NOT NULL) AND
                                        (student.prefix_id = prefix.prefix_id) AND
                                        (student.dept_id = department.dept_id) AND
                                        (student.level_id = class_level.level_id) AND
                                        (registration.vehicle_id = vehicle.vehicle_id) AND
                                        (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                        (vehicle.province_id = province.province_id) AND
                                        (registration_status_detail.regisStatus_id IN ($Status)) AND
                                        (vehicle.vehiType_id IN ($VehicleType))
                                    GROUP BY registration.regis_id
                                    LIMIT $RowRecord
                            ";

                            $regis_id_arr = array();
                            $rows = SQLSelectNumRow($con, $sql);

                            if ($rows > 0) {
                                foreach (SQLSelectLoop($con, $sql) as $data) {
                                    $regis_id = $data['regis_id'];
                                    array_push($regis_id_arr, $data['regis_id']);
                                    ?>
                                    <tr>
                                        <td><?php echo $data['regis_id'] ?></td>
                                        <td><?php echo $data['prefix_name'] . $data['name'] . " " . $data['lastname'] ?></td>
                                        <td><?php echo $data['dept_name'] . " (" . $data['level_name'] . ")" ?></td>
                                        <td><?php echo $data['vehicle_id'] . " (" . $data['province_name'] . ")" ?></td>
                                        <td><?php echo $data['vehiType_name'] ?></td>
                                        <td>
                                            <?php
                                                switch ($data['regisStatus_id']) {
                                                    case '5':
                                                        echo "<span class='btn btn-success'>ลงทะเบียนเสร็จสิ้น</span>";
                                                        break;
                                                    case '6':
                                                    case '7':
                                                    case '8':
                                                    case '9':
                                                        echo "<span class='btn btn-danger'>ไม่ผ่านการอนุมัติ</span>";
                                                        break;
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="user-management-detail.php?UserID=<?php echo $data['std_id'] ?>&UserType=Student" class="btn btn-maintheme">ดูข้อมูล</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else {
                                ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="m-0 alert alert-warning" role="alert">ไม่มีข้อมูล</div>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Personnel Table -->
            <div class="mt-4 my-card p-4" id="PersonnelTable">
                <p class="fs-5 fw-bold mb-3">คำขอ - บุคลากร</p>
                <table class="table table-stripped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>คำขอที่</th>
                            <th>ชื่อ - สกุล</th>
                            <th>ทะเบียน</th>
                            <th>ประเภทยานพาหนะ</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (isset($_GET['VehicleType']) && $_GET['VehicleType'] == "Car") {
                                $VehicleType = 1;
                            }
                            else if (isset($_GET['VehicleType']) && $_GET['VehicleType'] == "Motorbike") {
                                $VehicleType = 2;
                            }
                            else {
                                $VehicleType = "1, 2";
                            }

                            $sql = "SELECT
                                        registration.regis_id, personnel.name, personnel.lastname, personnel.personnel_id,
                                        vehicle.vehiType_id, vehicle_type.vehiType_name, 
                                        registration_status_detail.rsd_date, registration_status_detail.rsd_time, province.province_name, 
                                        registration_status.regisStatus_name, registration_status_detail.regisStatus_id, registration.vehicle_id
                                    FROM
                                        registration_status_detail, registration,
                                        personnel, vehicle, vehicle_type, province, registration_status
                                    WHERE
                                        (registration_status_detail.regis_id = registration.regis_id) AND
                                        (registration_status_detail.regisStatus_id = registration_status.regisStatus_id) AND
                                        (registration.personnel_id = personnel.personnel_id) AND
                                        (registration.personnel_id IS NOT NULL) AND
                                        (registration.vehicle_id = vehicle.vehicle_id) AND
                                        (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                        (vehicle.province_id = province.province_id) AND
                                        (registration_status_detail.regisStatus_id IN ($Status)) AND
                                        (vehicle.vehiType_id IN ($VehicleType))
                                    GROUP BY registration.regis_id
                                    LIMIT $RowRecord
                            ";

                            $personnel_regis_id_arr = array();
                            $rows = SQLSelectNumRow($con, $sql);

                            if ($rows > 0) {
                                foreach (SQLSelectLoop($con, $sql) as $data) {
                                    $personnel_regis_id = $data['regis_id'];
                                    array_push($personnel_regis_id_arr, $data['regis_id']);
                                    ?>
                                    <tr>
                                        <td><?php echo $data['regis_id'] ?></td>
                                        <td><?php echo $data['name'] . " " . $data['lastname'] ?></td>
                                        <td><?php echo $data['vehicle_id'] . " (" . $data['province_name'] . ")" ?></td>
                                        <td><?php echo $data['vehiType_name'] ?></td>
                                        <td>
                                            <?php
                                                switch ($data['regisStatus_id']) {
                                                    case '5':
                                                        echo "<span class='btn btn-success'>ลงทะเบียนเสร็จสิ้น</span>";
                                                        break;
                                                    case '6':
                                                    case '7':
                                                    case '8':
                                                    case '9':
                                                        echo "<span class='btn btn-danger'>ไม่ผ่านการอนุมัติ</span>";
                                                        break;
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="user-management-detail.php?UserID=<?php echo $data['personnel_id'] ?>&UserType=Personnel" class="btn btn-maintheme">ดูข้อมูล</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else {
                                ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="m-0 alert alert-warning" role="alert">ไม่มีข้อมูล</div>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Filter Checking -->
            <script>
                var Filter = new URLSearchParams(window.location.search);
                var Filter_UserType = Filter.get('UserType');
                var Filter_VehicleType = Filter.get('VehicleType');
                var Filter_RowRecord = Filter.get('RowRecord');
                var Filter_Status = Filter.get('Status');

                var Select_UserType = document.getElementById('UserType');
                var Select_VehicleType = document.getElementById('VehicleType');
                var Select_RowRecord = document.getElementById('RowRecord');
                var Select_Status = document.getElementById('Status');

                var StudentTable = document.getElementById('StudentTable');
                var PersonnelTable = document.getElementById('PersonnelTable');

                switch (Filter_UserType) {
                    case 'All' :
                        Select_UserType.value = "0";
                        PersonnelTable.style.display = 'block';
                        StudentTable.style.display = 'block';
                        break;
                    case 'Student' :
                        Select_UserType.value = "1";
                        PersonnelTable.style.display = 'none';
                        break;
                    case 'Personnel' :
                        Select_UserType.value = "2";
                        StudentTable.style.display = 'none';
                        break;
                    default :
                        Select_UserType.value = "0";
                        PersonnelTable.style.display = 'block';
                        StudentTable.style.display = 'block';
                        break;
                }
                switch (Filter_VehicleType) {
                    case 'All' :
                        Select_VehicleType.value = "0";
                        break;
                    case 'Motorbike' :
                        Select_VehicleType.value = "1";
                        break;
                    case 'Car' :
                        Select_VehicleType.value = "2";
                        break;
                    default :
                        Select_VehicleType.value = "0";
                        break;
                }
                switch (Filter_RowRecord) {
                    case 'All' :
                        Select_RowRecord.value = "0";
                        break;
                    default :
                        if (Filter_RowRecord === null) {
                            Select_RowRecord.value = "0";
                        }
                        else {
                            Select_RowRecord.value = Filter_RowRecord;
                        }
                        break;
                }
                switch (Filter_Status) {
                    case 'Success' :
                        Select_Status.value = "1";
                        break;
                    case 'Fail' :
                        Select_Status.value = "2";
                        break;
                    default :
                        Select_Status.value = "0";
                        break;
                }
            </script>

            <script>
                function Filter() {
                    var UserType = document.getElementById('UserType').value;
                    var VehicleType = document.getElementById('VehicleType').value;
                    var RowRecord = document.getElementById('RowRecord').value;
                    var Status = document.getElementById('Status').value;

                    var UserTypeURL = "";
                    var VehicleTypeURL = "";
                    var RowRecordURL = "";
                    var StatusURL = "";

                    switch (UserType) {
                        case '0':
                            UserTypeURL = "All";
                            break;
                        case '1':
                            UserTypeURL = "Student";
                            break;
                        case '2':
                            UserTypeURL = "Personnel";
                            break;
                    }
                    switch (VehicleType) {
                        case '0':
                            VehicleTypeURL = "All";
                            break;
                        case '1':
                            VehicleTypeURL = "Motorbike";
                            break;
                        case '2':
                            VehicleTypeURL = "Car";
                            break;
                    }
                    switch (RowRecord) {
                        case '0':
                            RowRecordURL = "All";
                            break;
                        default :
                            RowRecordURL = RowRecord;
                            break;
                    }
                    switch (Status) {
                        case '0':
                            StatusURL = "All";
                            break;
                        case '1':
                            StatusURL = "Success";
                            break;
                        case '2':
                            StatusURL = "Fail";
                            break;
                    }

                    window.location = "registration-overall.php?UserType=" + UserTypeURL + "&VehicleType=" + VehicleTypeURL + "&RowRecord=" + RowRecordURL + "&Status=" + StatusURL;
                }
            </script>

        </div>

    </div>

    <!-- Essentials JavaScript -->
    <?php include_once '../import/js.php'; ?>
    

</body>
</html>