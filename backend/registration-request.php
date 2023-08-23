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
                                <!-- Row Record -->
                                <select id="RowRecord" class="form-select" onchange="Filter()">
                                    <option value="0" selected>- จำนวนแถว -</option>
                                    <option value="50">50 แถว</option>
                                    <option value="100">100 แถว</option>
                                    <option value="500">500 แถว</option>
                                    <option value="1000">1,000 แถว</option>
                                </select>
                                <!-- Clear Filter -->
                                <a href="registration-request.php" class="btn btn-danger">
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
                            <th>
                                <input type="checkbox" id="AllCheck" class="form-check-input" onclick="Std_CheckAll()">
                                ทั้งหมด
                            </th>
                            <th>คำขอที่</th>
                            <th>ชื่อ - สกุล</th>
                            <th>แผนกวิชา (ชั้นปี)</th>
                            <th>ประเภทยานพาหนะ</th>
                            <th>เวลาที่ส่งคำขอ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            if (isset($_GET['RowRecord'])) {
                                $RowRecord = $_GET['RowRecord'];
                                if ($RowRecord == "All") {
                                    $RowRecord = 99999;
                                }
                            }
                            else {
                                $RowRecord = 99999;
                            }

                            if (isset($_GET['VehicleType']) && $_GET['VehicleType'] == "Car") {
                                $sql = "SELECT
                                            registration.regis_id, prefix.prefix_name, student.name, student.lastname, 
                                            department.dept_name, class_level.level_name, vehicle.vehiType_id, vehicle_type.vehiType_name,
                                            registration_status_detail.rsd_date, registration_status_detail.rsd_time
                                        FROM
                                            registration_status_detail, registration, 
                                            student, prefix, department, class_level,
                                            vehicle, vehicle_type
                                        WHERE
                                            (registration_status_detail.regis_id = registration.regis_id) AND
                                            (registration.std_id = student.std_id) AND
                                            (registration.std_id IS NOT NULL) AND
                                            (student.prefix_id = prefix.prefix_id) AND
                                            (student.dept_id = department.dept_id) AND
                                            (student.level_id = class_level.level_id) AND
                                            (registration.vehicle_id = vehicle.vehicle_id) AND
                                            (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                            (registration_status_detail.regisStatus_id = 2) AND
                                            (vehicle.vehiType_id = 1) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id 
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING MAX(regisStatus_id) <= 2
                                                                                    )
                                            )
                                        LIMIT $RowRecord
                                ";
                            }
                            else if (isset($_GET['VehicleType']) && $_GET['VehicleType'] == "Motorbike") {
                                $sql = "SELECT
                                            registration.regis_id, prefix.prefix_name, student.name, student.lastname, 
                                            department.dept_name, class_level.level_name, vehicle.vehiType_id, vehicle_type.vehiType_name,
                                            registration_status_detail.rsd_date, registration_status_detail.rsd_time
                                        FROM
                                            registration_status_detail, registration, 
                                            student, prefix, department, class_level,
                                            vehicle, vehicle_type
                                        WHERE
                                            (registration_status_detail.regis_id = registration.regis_id) AND
                                            (registration.std_id = student.std_id) AND
                                            (registration.std_id IS NOT NULL) AND
                                            (student.prefix_id = prefix.prefix_id) AND
                                            (student.dept_id = department.dept_id) AND
                                            (student.level_id = class_level.level_id) AND
                                            (registration.vehicle_id = vehicle.vehicle_id) AND
                                            (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                            (registration_status_detail.regisStatus_id = 2) AND
                                            (vehicle.vehiType_id = 2) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id 
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING MAX(regisStatus_id) <= 2
                                                                                    )
                                            )
                                        LIMIT $RowRecord
                                ";
                            }
                            else {
                                $sql = "SELECT
                                            registration.regis_id, prefix.prefix_name, student.name, student.lastname, 
                                            department.dept_name, class_level.level_name, vehicle.vehiType_id, vehicle_type.vehiType_name,
                                            registration_status_detail.rsd_date, registration_status_detail.rsd_time
                                        FROM
                                            registration_status_detail, registration, 
                                            student, prefix, department, class_level,
                                            vehicle, vehicle_type
                                        WHERE
                                            (registration_status_detail.regis_id = registration.regis_id) AND
                                            (registration.std_id = student.std_id) AND
                                            (registration.std_id IS NOT NULL) AND
                                            (student.prefix_id = prefix.prefix_id) AND
                                            (student.dept_id = department.dept_id) AND
                                            (student.level_id = class_level.level_id) AND
                                            (registration.vehicle_id = vehicle.vehicle_id) AND
                                            (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                            (registration_status_detail.regisStatus_id = 2) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id 
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING MAX(regisStatus_id) <= 2
                                                                                    )
                                            )
                                        LIMIT $RowRecord
                                ";
                            }

                            $regis_id_arr = array();
                            $rows = SQLSelectNumRow($con, $sql);

                            if ($rows > 0) {
                                foreach (SQLSelectLoop($con, $sql) as $data) {
                                    $regis_id = $data['regis_id'];
                                    array_push($regis_id_arr, $data['regis_id']);
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" id="<?php echo $regis_id ?>" class="form-check-input" onclick="Std_regis_check()">
                                        </td>
                                        <td><?php echo $data['regis_id'] ?></td>
                                        <td><?php echo $data['prefix_name'] . $data['name'] . " " . $data['lastname'] ?></td>
                                        <td><?php echo $data['dept_name'] . " (" . $data['level_name'] . ")" ?></td>
                                        <td><?php echo $data['vehiType_name'] ?></td>
                                        <td><?php echo formatdatethai($data['rsd_date']) . " (" . $data['rsd_time'] . ")" ?></td>
                                        <td>
                                            <a href="#" class="btn btn-maintheme" onclick="SingleApprove(<?php echo $regis_id ?>)">รับเรื่อง</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else {
                                ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="m-0 alert alert-warning" role="alert">ไม่มีข้อมูลคำขอ</div>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>

                <div class="my-4" id="btn_approve" style="display: none;">
                    <button class="btn btn-primary" onclick="Std_MultipleApprove()">รับเรื่องคำขอที่เลือกทั้งหมด</button>
                </div>
            </div>

            <!-- Personnel Table -->
            <div class="mt-4 my-card p-4" id="PersonnelTable">
                <p class="fs-5 fw-bold mb-3">คำขอ - บุคลากร</p>
                <table class="table table-stripped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="Personnel_AllCheck" class="form-check-input" onclick="Personnel_CheckAll()">
                                ทั้งหมด
                            </th>
                            <th>คำขอที่</th>
                            <th>ชื่อ - สกุล</th>
                            <th>ประเภทยานพาหนะ</th>
                            <th>เวลาที่ส่งคำขอ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (isset($_GET['VehicleType']) && $_GET['VehicleType'] == "Car") {
                                $sql = "SELECT
                                            registration.regis_id, personnel.name, personnel.lastname,
                                            vehicle.vehiType_id, vehicle_type.vehiType_name, 
                                            registration_status_detail.rsd_date, registration_status_detail.rsd_time
                                        FROM
                                            registration_status_detail, registration,
                                            personnel, vehicle, vehicle_type
                                        WHERE
                                            (registration_status_detail.regis_id = registration.regis_id) AND
                                            (registration.personnel_id = personnel.personnel_id) AND
                                            (registration.personnel_id IS NOT NULL) AND
                                            (registration.vehicle_id = vehicle.vehicle_id) AND
                                            (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                            (registration_status_detail.regisStatus_id = 2) AND
                                            (vehicle.vehiType_id = 1) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id 
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING MAX(regisStatus_id) <= 2
                                                                                    )
                                            )
                                        LIMIT $RowRecord
                                ";
                            }
                            else if (isset($_GET['VehicleType']) && $_GET['VehicleType'] == "Motorbike") {
                                $sql = "SELECT
                                            registration.regis_id, personnel.name, personnel.lastname,
                                            vehicle.vehiType_id, vehicle_type.vehiType_name, 
                                            registration_status_detail.rsd_date, registration_status_detail.rsd_time
                                        FROM
                                            registration_status_detail, registration,
                                            personnel, vehicle, vehicle_type
                                        WHERE
                                            (registration_status_detail.regis_id = registration.regis_id) AND
                                            (registration.personnel_id = personnel.personnel_id) AND
                                            (registration.personnel_id IS NOT NULL) AND
                                            (registration.vehicle_id = vehicle.vehicle_id) AND
                                            (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                            (registration_status_detail.regisStatus_id = 2) AND
                                            (vehicle.vehiType_id = 2) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id 
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING MAX(regisStatus_id) <= 2
                                                                                    )
                                            )
                                        LIMIT $RowRecord
                                ";
                            }
                            else {
                                $sql = "SELECT
                                            registration.regis_id, personnel.name, personnel.lastname,
                                            vehicle.vehiType_id, vehicle_type.vehiType_name, 
                                            registration_status_detail.rsd_date, registration_status_detail.rsd_time
                                        FROM
                                            registration_status_detail, registration,
                                            personnel, vehicle, vehicle_type
                                        WHERE
                                            (registration_status_detail.regis_id = registration.regis_id) AND
                                            (registration.personnel_id = personnel.personnel_id) AND
                                            (registration.personnel_id IS NOT NULL) AND
                                            (registration.vehicle_id = vehicle.vehicle_id) AND
                                            (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                            (registration_status_detail.regisStatus_id = 2) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id 
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING MAX(regisStatus_id) <= 2
                                                                                    )
                                            )
                                        LIMIT $RowRecord
                                ";
                            }

                            $personnel_regis_id_arr = array();
                            $rows = SQLSelectNumRow($con, $sql);

                            if ($rows > 0) {
                                foreach (SQLSelectLoop($con, $sql) as $data) {
                                    $personnel_regis_id = $data['regis_id'];
                                    array_push($personnel_regis_id_arr, $data['regis_id']);
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" id="<?php echo $personnel_regis_id ?>" class="form-check-input" onclick="Personnel_regis_check()">
                                        </td>
                                        <td><?php echo $data['regis_id'] ?></td>
                                        <td><?php echo $data['name'] . " " . $data['lastname'] ?></td>
                                        <td><?php echo $data['vehiType_name'] ?></td>
                                        <td><?php echo formatdatethai($data['rsd_date']) . " (" . $data['rsd_time'] . ")" ?></td>
                                        <td>
                                            <a href="#" class="btn btn-maintheme" onclick="SingleApprove(<?php echo $personnel_regis_id ?>)">รับเรื่อง</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else {
                                ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="m-0 alert alert-warning" role="alert">ไม่มีข้อมูลคำขอ</div>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>

                <div class="my-4" id="personnel_btn_approve" style="display: none;">
                    <button class="btn btn-primary" onclick="Personnel_MultipleApprove()">รับเรื่องคำขอที่เลือกทั้งหมด</button>
                </div>
            </div>

            <!-- Filter Checking -->
            <script>
                var Filter = new URLSearchParams(window.location.search);
                var Filter_UserType = Filter.get('UserType');
                var Filter_VehicleType = Filter.get('VehicleType');
                var Filter_RowRecord = Filter.get('RowRecord');

                var Select_UserType = document.getElementById('UserType');
                var Select_VehicleType = document.getElementById('VehicleType');
                var Select_RowRecord = document.getElementById('RowRecord');

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
                }
            </script>

            <!-- Totoal Summarize -->
            <div class="container fixed-bottom mb-5">
                <p class="bg-primary text-light p-2 m-0 rounded fw-bolder fs-6 position-absolute end-0 bottom-50" style="width: fit-content;">
                    คำขอทั้งหมด <?php echo count($regis_id_arr) + count($personnel_regis_id_arr) ?> คำขอ
                </p>
            </div>

        </div>

    </div>

    <!-- Essentials JavaScript -->
    <?php include_once '../import/js.php'; ?>

    <script>
        // ------------ Student ------------
        function Std_CheckAll() {

            var AllCheck = document.getElementById('AllCheck');
            var all_checkbox = <?php echo json_encode($regis_id_arr) ?>;

            if (AllCheck.checked == true) {
                for (var i = 0; i < all_checkbox.length; i++) {
                    var temp_checkbox_id = document.getElementById(all_checkbox[i]);
                    temp_checkbox_id.checked = true;
                    Std_ToggleApproveButton_On();
                }
            }
            else {
                for (var i = 0; i < all_checkbox.length; i++) {
                    var temp_checkbox_id = document.getElementById(all_checkbox[i]);
                    temp_checkbox_id.checked = false;
                    Std_ToggleApproveButton_Off();
                }
            }

        }

        function Std_regis_check() {
            var AllCheck = <?php echo json_encode($regis_id_arr) ?>;
            var CheckCount = 0;

            for (var i = 0; i < AllCheck.length; i++) {
                var temp_checkbox_id = document.getElementById(AllCheck[i]);
                if (temp_checkbox_id.checked === true) {
                    CheckCount++;
                }
            }

            if (AllCheck.length == CheckCount) {
                document.getElementById('AllCheck').checked = true;
            }
            else {
                document.getElementById('AllCheck').checked = false;
            }

            if (CheckCount > 0) {
                Std_ToggleApproveButton_On();
            }
            else  {
                Std_ToggleApproveButton_Off();
            }
        }

        function Std_ToggleApproveButton() {
            var btn_approve = document.getElementById('btn_approve');

            if (btn_approve.style.display === 'none') {
                btn_approve.style.display = 'block';
            }
            else {
                btn_approve.style.display = 'none';
            }
        }

        function Std_ToggleApproveButton_On() {
            var btn_approve = document.getElementById('btn_approve');

            btn_approve.style.display = 'block';
        }

        function Std_ToggleApproveButton_Off() {
            var btn_approve = document.getElementById('btn_approve');

            btn_approve.style.display = 'none';
        }

        function Std_MultipleApprove() {
            var regis_checkbox_id = <?php echo json_encode($regis_id_arr) ?>;
            var checked_id = [];
            for (var i = 0; i < regis_checkbox_id.length; i++) {
                var temp_checkbox_id = document.getElementById(regis_checkbox_id[i]);
                if (temp_checkbox_id.checked) {
                    checked_id.push(regis_checkbox_id[i]);
                }
            }
            if (!(checked_id.length === 0)) {
                Swal.fire({
                    title: 'ยืนยันการรับเรื่อง',
                    text: "คำขอทั้งหมด " + checked_id.length + " คำขอ",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../ajax_db.php',
                            method: 'POST',
                            data: {BackendFunction: 'RegistrationRequestApprove', data: checked_id},
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'รับเรื่องทั้งหมดเสร็จสิ้น',
                                    confirmButtonText: 'ตกลง'
                                }).then((result) => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error(error); // การจัดการกับข้อผิดพลาด (error)
                            }
                        });
                    }
                });
            }
        }

        // ------------ Personnel ------------
        function Personnel_CheckAll() {

            var AllCheck = document.getElementById('Personnel_AllCheck');
            var all_checkbox = <?php echo json_encode($personnel_regis_id_arr) ?>;

            if (AllCheck.checked == true) {
                for (var i = 0; i < all_checkbox.length; i++) {
                    var temp_checkbox_id = document.getElementById(all_checkbox[i]);
                    temp_checkbox_id.checked = true;
                    Personnel_ToggleApproveButton_On();
                }
            }
            else {
                for (var i = 0; i < all_checkbox.length; i++) {
                    var temp_checkbox_id = document.getElementById(all_checkbox[i]);
                    temp_checkbox_id.checked = false;
                    Personnel_ToggleApproveButton_Off();
                }
            }

        }

        function Personnel_ToggleApproveButton_On() {
            var btn_approve = document.getElementById('personnel_btn_approve');

            btn_approve.style.display = 'block';
        }

        function Personnel_ToggleApproveButton_Off() {
            var btn_approve = document.getElementById('personnel_btn_approve');

            btn_approve.style.display = 'none';
        }

        function Personnel_regis_check() {
            var AllCheck = <?php echo json_encode($personnel_regis_id_arr) ?>;
            var CheckCount = 0;

            for (var i = 0; i < AllCheck.length; i++) {
                var temp_checkbox_id = document.getElementById(AllCheck[i]);
                if (temp_checkbox_id.checked === true) {
                    CheckCount++;
                }
            }

            if (AllCheck.length == CheckCount) {
                document.getElementById('Personnel_AllCheck').checked = true;
            }
            else {
                document.getElementById('Personnel_AllCheck').checked = false;
            }

            if (CheckCount > 0) {
                Personnel_ToggleApproveButton_On();
            }
            else  {
                Personnel_ToggleApproveButton_Off();
            }
        }

        function Personnel_MultipleApprove() {
            var regis_checkbox_id = <?php echo json_encode($personnel_regis_id_arr) ?>;
            var checked_id = [];
            for (var i = 0; i < regis_checkbox_id.length; i++) {
                var temp_checkbox_id = document.getElementById(regis_checkbox_id[i]);
                if (temp_checkbox_id.checked) {
                    checked_id.push(regis_checkbox_id[i]);
                }
            }
            if (!(checked_id.length === 0)) {
                Swal.fire({
                    title: 'ยืนยันการรับเรื่อง',
                    text: "คำขอทั้งหมด " + checked_id.length + " คำขอ",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../ajax_db.php',
                            method: 'POST',
                            data: {BackendFunction: 'RegistrationRequestApprove', data: checked_id},
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'รับเรื่องทั้งหมดเสร็จสิ้น',
                                    confirmButtonText: 'ตกลง'
                                }).then((result) => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error(error); // การจัดการกับข้อผิดพลาด (error)
                            }
                        });
                    }
                });
            }
        }

        // ---------------- Both ----------------
        function SingleApprove(approve_id) {
            Swal.fire({
                title: 'ยืนยันการรับเรื่อง',
                text: "คำขอที่ " + approve_id,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../ajax_db.php',
                        method: 'POST',
                        data: {BackendFunction: 'RegistrationRequestApprove_Single', data: approve_id},
                        success: function(data) {
                            Swal.fire({
                                icon: 'success',
                                title: 'รับเรื่องทั้งหมดเสร็จสิ้น',
                                confirmButtonText: 'ตกลง'
                            }).then((result) => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(error); // การจัดการกับข้อผิดพลาด (error)
                        }
                    });
                }
            });
        }

        function Filter() {
            var UserType = document.getElementById('UserType').value;
            var VehicleType = document.getElementById('VehicleType').value;
            var RowRecord = document.getElementById('RowRecord').value;

            var UserTypeURL = "";
            var VehicleTypeURL = "";
            var RowRecordURL = "";

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

            window.location = "registration-request.php?UserType=" + UserTypeURL + "&VehicleType=" + VehicleTypeURL + "&RowRecord=" + RowRecordURL;
        }
    </script>
    

</body>
</html>