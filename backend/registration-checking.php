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

    <!-- Essentials JavaScript -->
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
                <div class="row align-items-center justify-content-between row-gap-3">
                    <div class="col-sm-3">
                        <h1 class="m-0 p-0 fs-5 fw-bold">การตรวจสอบสภาพรถ</h1>
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
                                <a href="registration-checking.php" class="btn btn-danger">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Table -->
            <div class="mt-4 my-card p-4" id="StudentTable" style="display: block;">
                <p class="fs-5 fw-bold mb-3">การลงทะเบียนฯ - นักศึกษา</p>
                <table class="table table-stripped table-hover align-middle">
                    <thead>
                        <tr>
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
                                switch ($RowRecord) {
                                    case '50' :
                                        $RowRecord = 50;
                                        break;
                                    case '100' :
                                        $RowRecord = 100;
                                        break;
                                    case '500' :
                                        $RowRecord = 500;
                                        break;
                                    case '1000' :
                                        $RowRecord = 1000;
                                        break;
                                    default :
                                        $RowRecord = 99999;
                                        break;
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
                                            (registration_status_detail.regisStatus_id = 3) AND
                                            (vehicle.vehiType_id = 1) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id 
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING MAX(regisStatus_id) <= 3
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
                                            (registration_status_detail.regisStatus_id = 3) AND
                                            (vehicle.vehiType_id = 2) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id 
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING MAX(regisStatus_id) <= 3
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
                                            (registration_status_detail.regisStatus_id = 3) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id 
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING MAX(regisStatus_id) <= 3
                                                                                    )
                                            )
                                        LIMIT $RowRecord
                                ";
                            }

                            $rows = SQLSelectNumRow($con, $sql);
                            $student_total_registration = 0;

                            if ($rows > 0) {
                                foreach (SQLSelectLoop($con, $sql) as $data) {
                                    $regis_id = $data['regis_id'];
                                    $student_total_registration++;
                                    ?>
                                    <tr>
                                        <td><?php echo $data['regis_id'] ?></td>
                                        <td><?php echo $data['prefix_name'] . $data['name'] . " " . $data['lastname'] ?></td>
                                        <td><?php echo $data['dept_name'] . " (" . $data['level_name'] . ")" ?></td>
                                        <td><?php echo $data['vehiType_name'] ?></td>
                                        <td><?php echo formatdatethai($data['rsd_date']) . " (" . $data['rsd_time'] . "น.)" ?></td>
                                        <td>
                                            <button type="button" class="btn btn-maintheme" onclick="RegistrationDetail(<?php echo $data['regis_id'] ?>, 'Student')">
                                                <i class="bi bi-sliders"></i> ตรวจสอบ
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else {
                                ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="m-0 alert alert-warning" role="alert">ไม่มีข้อมูลคำขอ</div>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Personnel Table -->
            <div class="mt-4 my-card p-4" id="PersonnelTable" style="display: block;">
                <p class="fs-5 fw-bold mb-3">การลงทะเบียนฯ - บุคลากร</p>
                <table class="table table-stripped table-hover align-middle">
                    <thead>
                        <tr>
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
                                            (registration_status_detail.regisStatus_id = 3) AND
                                            (vehicle.vehiType_id = 1) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id 
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING MAX(regisStatus_id) <= 3
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
                                            (registration_status_detail.regisStatus_id = 3) AND
                                            (vehicle.vehiType_id = 2) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id 
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING MAX(regisStatus_id) <= 3
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
                                            (registration_status_detail.regisStatus_id = 3) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id 
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING MAX(regisStatus_id) <= 3
                                                                                    )
                                            )
                                        LIMIT $RowRecord
                                ";
                            }

                            $rows = SQLSelectNumRow($con, $sql);
                            $personnel_total_registration = 0;

                            if ($rows > 0) {
                                foreach (SQLSelectLoop($con, $sql) as $data) {
                                    $personnel_regis_id = $data['regis_id'];
                                    $personnel_total_registration++;
                                    ?>
                                    <tr>
                                        <td><?php echo $data['regis_id'] ?></td>
                                        <td><?php echo $data['name'] . " " . $data['lastname'] ?></td>
                                        <td><?php echo $data['vehiType_name'] ?></td>
                                        <td><?php echo formatdatethai($data['rsd_date']) . " (" . $data['rsd_time'] . "น.)" ?></td>
                                        <td>
                                            <button type="button" class="btn btn-maintheme" onclick="RegistrationDetail(<?php echo $data['regis_id'] ?>, 'Personnel')">
                                                <i class="bi bi-sliders"></i> ตรวจสอบ
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else {
                                ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="m-0 alert alert-warning" role="alert">ไม่มีข้อมูลคำขอ</div>
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
                var Filter_Record = Filter.get('RowRecord');
                var Filter_VehicleType = Filter.get('VehicleType');
                var Filter_UserType = Filter.get('UserType');

                var Record = document.getElementById('RowRecord');
                var VehicleType = document.getElementById('VehicleType');
                var UserType = document.getElementById('UserType');

                //Filter Row Record
                if ((Filter_Record == "0") || (Filter_Record == null) || (Filter_Record == "") || (Filter_Record == "All")) {
                    Record.value = "0";
                }
                else {
                    switch (Filter_Record) {
                        case "50" :
                            Record.value = "50";
                            break;
                        case "100" :
                            Record.value = "100";
                            break;
                        case "500" :
                            Record.value = "500";
                            break;
                        case "1000" :
                            Record.value = "1000";
                            break;
                        default :
                        Record.value = "0";
                            break;
                    }
                }

                // Filter Vehicle Type
                if ((Filter_VehicleType == "All") || (Filter_VehicleType == "") || (Filter_VehicleType == null)) {
                    VehicleType.value = "0";
                }
                else if (Filter_VehicleType == "Car") {
                    VehicleType.value = "2";
                }
                else {
                    VehicleType.value = "1";
                }

                // Filter User Type
                var StudentTable = document.getElementById("StudentTable");
                var PersonnelTable = document.getElementById("PersonnelTable");

                if ((Filter_UserType == "All") || (Filter_UserType == "") || (Filter_UserType == null)) {
                    UserType.value = "0";
                    StudentTable.style.display = "block";
                    PersonnelTable.style.display = "block";
                }
                else if (Filter_UserType == "Student") {
                    UserType.value = "1";
                    StudentTable.style.display = "block";
                    PersonnelTable.style.display = "none";
                }
                else {
                    UserType.value = "2";
                    StudentTable.style.display = "none";
                    PersonnelTable.style.display = "block";
                }

            </script>

        </div>

        <!-- Totoal Summarize -->
        <div class="container fixed-bottom mb-5">
            <p class="bg-primary text-light p-2 m-0 rounded fw-bolder fs-6 position-absolute end-0 bottom-50" style="width: fit-content;">
                รายการตรวจสอบ <?php echo $student_total_registration + $personnel_total_registration; ?> รายการ
            </p>
        </div>

        <!-- Registration Document Details - Modal -->
        <div class="modal fade" id="Modal_RegistrationDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-10" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 fw-bolder" id="staticBackdropLabel">...</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="RegistrationDetails">
                            <!-- Auto Generate -->
                        </div>
                        <!-- Considerations Result -->
                        <div class="my-3">
                            <p class="fw-bold fs-5">ผลการพิจารณา</p>
                            <form action="considering.php" method="POST">
                                <div class="form-check">
                                    <div id="ConsiderResult_regis_id">
                                        <!-- Augo Generate -->
                                    </div>
                                    <input class="form-check-input" type="radio" name="Considering_radio" id="Approved" value="1" required onclick="ApprovedChecking()">
                                    <label class="form-check-label" for="Approved">
                                        ยืนยันอนุมัติการขอลงทะเบียน
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Considering_radio" id="NoApproved" value="2" required onclick="ApprovedChecking()">
                                    <label class="form-check-label" for="NoApproved">
                                        ไม่อนุมัติคำขอลงทะเบียน
                                    </label>
                                </div>

                                <!-- Not Permit Reason -->
                                <div class="my-3 mx-5" id="ResultOfDisapproval" style="display: none;">
                                    <p class="p-0 m-0 fw-bold fs-6">ไม่อนุมัติเนื่องจาก:</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="6" name="ResultOfDisApproval[]" id="NotPermitReason_6" onclick="NotPermitReason(6)">
                                        <label class="form-check-label">
                                            รถดัดแปลง
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="7" name="ResultOfDisApproval[]" id="NotPermitReason_7" onclick="NotPermitReason(7)">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            เอกสารไม่ครบ
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="8" name="ResultOfDisApproval[]" id="NotPermitReason_8" onclick="NotPermitReason(8)">
                                        <label class="form-check-label">
                                            เอกสารไม่ถูกต้อง
                                        </label>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-check-label">
                                            ข้อเสนอแนะอื่น ๆ (ไม่บังคับ)
                                        </label>     
                                        <textarea name="Comment" cols="30" rows="3" class="form-control" id="NotPermitReason_9" oninput="NotPermitReason(9)"></textarea>                                   
                                    </div>
                                </div>

                                <div class="my-3">
                                    <input type="submit" value="บันทึก" name="Considering" class="btn btn-maintheme" id="btn_Submit">
                                </div>

                                <script>

                                    // Permit or NotPermit
                                    var IsNoApproved = document.getElementById("NoApproved");
                                    var ResultOfDisapproval = document.getElementById("ResultOfDisapproval");
                                    var btn_Submit = document.getElementById("btn_Submit");

                                    function ApprovedChecking() {
                                        if (IsNoApproved.checked) {
                                            ResultOfDisapproval.style.display = "block";
                                            btn_Submit.disabled = true;
                                        }
                                        else {
                                            ResultOfDisapproval.style.display = "none";
                                            btn_Submit.disabled = false;
                                        }
                                    }

                                    // The reason of not permit
                                    var NotPermitReason6 = document.getElementById("NotPermitReason_6");
                                    var NotPermitReason7 = document.getElementById("NotPermitReason_7");
                                    var NotPermitReason8 = document.getElementById("NotPermitReason_8");
                                    var Total_NoPermit = 0;

                                    function NotPermitReason(id) {
                                        switch (id) {
                                            case 6 :
                                                if (NotPermitReason6.checked) {
                                                    Total_NoPermit = Total_NoPermit + 1;
                                                }
                                                else {
                                                    if (Total_NoPermit != 0) {
                                                        Total_NoPermit = Total_NoPermit - 1;
                                                    }
                                                }
                                                break;
                                            case 7 :
                                                if (NotPermitReason7.checked) {
                                                    Total_NoPermit = Total_NoPermit + 1;
                                                }
                                                else {
                                                    if (Total_NoPermit != 0) {
                                                        Total_NoPermit = Total_NoPermit - 1;
                                                    }
                                                }
                                                break;
                                            case 8 :
                                                if (NotPermitReason8.checked) {
                                                    Total_NoPermit = Total_NoPermit + 1;
                                                }
                                                else {
                                                    if (Total_NoPermit != 0) {
                                                        Total_NoPermit = Total_NoPermit - 1;
                                                    }
                                                }
                                                break;
                                            case 9 :
                                                var NotPermitReason9 = document.getElementById("NotPermitReason_9").value;
                                                if (Total_NoPermit == 0) {
                                                    if (NotPermitReason9.length != 0) {
                                                        btn_Submit.disabled = false;
                                                    }
                                                    else {
                                                        btn_Submit.disabled = true;
                                                    }
                                                }
                                                break;
                                        }

                                        var NotPermitReason9 = document.getElementById("NotPermitReason_9").value;
                                        if ((Total_NoPermit != 0) || (NotPermitReason9.length != 0)) {
                                            btn_Submit.disabled = false;
                                        }
                                        else {
                                            btn_Submit.disabled = true;
                                        }
                                    }

                                </script>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-maintheme" data-bs-dismiss="modal">
                            ปิด
                        </button>
                        <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Essentials JavaScript -->
    <?php include_once '../import/js.php'; ?>

    <script>

        // Filter
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

            window.location = "registration-checking.php?UserType=" + UserTypeURL + "&VehicleType=" + VehicleTypeURL + "&RowRecord=" + RowRecordURL;
        }

        // Registration Detail (Modal)
        function RegistrationDetail(regis_id, type) {

            var Title = document.getElementById('staticBackdropLabel');
            Title.innerHTML = "คำขอการลงทะเบียนที่ " + regis_id;

            $.ajax({
                url: '../ajax_db.php',
                method: 'POST',
                data: { 
                    BackendFunction: 'RegistrationDetail_Modal', 
                    id: regis_id, 
                    type: type 
                },
                dataType: 'json',
                success: function(data) {
                    ShowRegistrationData_Modal(data);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });



            $("#Modal_RegistrationDetails").modal('show');
        }

        function ShowRegistrationData_Modal(data) {
            var content = document.getElementById('RegistrationDetails');
            var ConsiderResult_regis_id = document.getElementById('ConsiderResult_regis_id');
            ConsiderResult_regis_id.innerHTML = "";

            ConsiderResult_regis_id.innerHTML = `
                <input type="text" name="regis_id" value="${ data.regis_id }" hidden>
            `;

            content.innerHTML = "";
            if (data.std_id != null) {
                content.innerHTML = `
                    <!-- User Info -->
                    <div class="mb-3">
                        <p class="fw-bold fs-5 p-0 m-0 mb-1">ข้อมูลผู้ลงทะเบียน</p>
                        <div class="mx-3 d-flex align-items-center column-gap-5">
                            <div>
                                <p class="m-0 p-0"><b>รหัสนักศึกษา: </b>${ data.std_id }</p>
                                <p class="m-0 p-0"><b>ขื่อ-นามสกุล: </b>${ data.prefix_name + data.name + " " + data.lastname }</p>
                                <p class="m-0 p-0"><b>แผนกวิชา: </b>${ data.dept_name }</p>
                                <p class="m-0 p-0"><b>ระดับชั้น: </b>${ data.level_name }</p>
                            </div>
                            <div class="ms-5">
                                <img src="data:image/jpeg;base64,${ data.img }" alt="User Profile (IMG)" width="120px" height="120px" class="rounded-circle object-fit-cover">
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Info -->
                    <div class="mb-3">
                        <p class="fw-bold fs-5 p-0 m-0 mb-1">ข้อมูลผู้ลงทะเบียน</p>
                        <div class="mx-3">
                            <p class="m-0 p-0"><b>ทะเบียนยานพาหนะ: </b>${ data.vehicle_id }</p>
                            <p class="m-0 p-0"><b>จังหวัด: </b>${ data.province_name }</p>
                            <p class="m-0 p-0"><b>ประเภทยานพาหนะ: </b>${ data.vehiType_name }</p>
                        </div>
                    </div>

                    <!-- Vehicle Picture -->
                    <div class="mb-3">
                        <p class="fw-bold fs-5 p-0 m-0 mb-1">รูปถ่ายยานพาหนะ</p>
                        <div class="mx-3">
                            <div class="d-flex flex-column align-items-center row-gap-5">
                                <div class="d-flex flex-column align-items-center">
                                    <a href="file-checking.php?regis_id=${ data.regis_id }&filename=img_front" class="text-decoration-none d-flex flex-column align-items-center" target="_blank">
                                        <img src="data:image/jpeg;base64,${ data.img_front }" alt="Vehicle Front (IMG)" class="img-fluid rounded img-zoom-hover bg-body-secondary" style="max-width: 250px;">
                                        รูปถ่ายด้านหน้า
                                    </a>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                <a href="file-checking.php?regis_id=${ data.regis_id }&filename=img_side" class="text-decoration-none d-flex flex-column align-items-center" target="_blank">
                                        <img src="data:image/jpeg;base64,${ data.img_side }" alt="Vehicle Front (IMG)" class="img-fluid rounded img-zoom-hover bg-body-secondary" style="max-width: 250px;">
                                        รูปถ่ายด้านข้าง
                                    </a>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <a href="file-checking.php?regis_id=${ data.regis_id }&filename=img_back" class="text-decoration-none d-flex flex-column align-items-center" target="_blank">
                                        <img src="data:image/jpeg;base64,${ data.img_back }" alt="Vehicle Front (IMG)" class="img-fluid rounded img-zoom-hover bg-body-secondary" style="max-width: 250px;">
                                        รูปถ่ายด้านหลัง
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Info -->
                    <div class="mb-3">
                        <p class="fw-bold fs-5 p-0 m-0 mb-1">เอกสาร</p>
                        <div class="mx-3">
                            <p class="m-0 p-0"><b>สำเนาทะเบียนยานพาหนะ: </b>
                                <a href="file-checking.php?regis_id=${ data.regis_id }&filename=copyOf_VehicleRegis" class="text-decoration-none" target="_blank">ตรวจสอบ</a>
                            </p>
                            <p class="m-0 p-0"><b>สำเนาบัตรประชาชนเจ้าของกรรมสิทธิ์: </b>
                                <a href="file-checking.php?regis_id=${ data.regis_id }&filename=copyOf_ownerIdCard" class="text-decoration-none" target="_blank">ตรวจสอบ</a>
                            </p>
                            <p class="m-0 p-0"><b>สำเนาบัตรประชาชน: </b>
                                <a href="file-checking.php?regis_id=${ data.regis_id }&filename=copyOf_userIdCard" class="text-decoration-none" target="_blank">ตรวจสอบ</a>
                            </p>
                        </div>
                    </div>

                    <!-- Considerations Result ->
                    <div class="mb-3"></div>
                `;
            }
            else {
                content.innerHTML = `
                    <!-- User Info -->
                    <div class="mb-3">
                        <p class="fw-bold fs-5 p-0 m-0 mb-1">ข้อมูลผู้ลงทะเบียน</p>
                        <div class="mx-3 d-flex align-items-center column-gap-5">
                            <div>
                                <p class="m-0 p-0"><b>รหัสบุคลากร: </b>${ data.personnel_id }</p>
                                <p class="m-0 p-0"><b>ขื่อ-นามสกุล: </b>${ data.name + " " + data.lastname }</p>
                            </div>
                            <div class="ms-5">
                                <img src="data:image/jpeg;base64,${ data.img }" alt="User Profile (IMG)" width="120px" height="120px" class="rounded-circle object-fit-cover">
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Info -->
                    <div class="mb-3">
                        <p class="fw-bold fs-5 p-0 m-0 mb-1">ข้อมูลผู้ลงทะเบียน</p>
                        <div class="mx-3">
                            <p class="m-0 p-0"><b>ทะเบียนยานพาหนะ: </b>${ data.vehicle_id }</p>
                            <p class="m-0 p-0"><b>จังหวัด: </b>${ data.province_name }</p>
                            <p class="m-0 p-0"><b>ประเภทยานพาหนะ: </b>${ data.vehiType_name }</p>
                        </div>
                    </div>

                    <!-- Vehicle Picture -->
                    <div class="mb-3">
                        <p class="fw-bold fs-5 p-0 m-0 mb-1">รูปถ่ายยานพาหนะ</p>
                        <div class="mx-3">
                            <div class="d-flex flex-column align-items-center row-gap-5">
                                <div class="d-flex flex-column align-items-center">
                                    <a href="file-checking.php?regis_id=${ data.regis_id }&filename=img_front" class="text-decoration-none d-flex flex-column align-items-center" target="_blank">
                                        <img src="data:image/jpeg;base64,${ data.img_front }" alt="Vehicle Front (IMG)" class="img-fluid rounded img-zoom-hover bg-body-secondary" style="max-width: 250px;">
                                        รูปถ่ายด้านหน้า
                                    </a>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                <a href="file-checking.php?regis_id=${ data.regis_id }&filename=img_side" class="text-decoration-none d-flex flex-column align-items-center" target="_blank">
                                        <img src="data:image/jpeg;base64,${ data.img_side }" alt="Vehicle Front (IMG)" class="img-fluid rounded img-zoom-hover bg-body-secondary" style="max-width: 250px;">
                                        รูปถ่ายด้านข้าง
                                    </a>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <a href="file-checking.php?regis_id=${ data.regis_id }&filename=img_back" class="text-decoration-none d-flex flex-column align-items-center" target="_blank">
                                        <img src="data:image/jpeg;base64,${ data.img_back }" alt="Vehicle Front (IMG)" class="img-fluid rounded img-zoom-hover bg-body-secondary" style="max-width: 250px;">
                                        รูปถ่ายด้านหลัง
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Info -->
                    <div class="mb-3">
                        <p class="fw-bold fs-5 p-0 m-0 mb-1">เอกสาร</p>
                        <div class="mx-3">
                            <p class="m-0 p-0"><b>สำเนาทะเบียนยานพาหนะ: </b>
                                <a href="file-checking.php?regis_id=${ data.regis_id }&filename=copyOf_VehicleRegis" class="text-decoration-none" target="_blank">ตรวจสอบ</a>
                            </p>
                            <p class="m-0 p-0"><b>สำเนาบัตรประชาชนเจ้าของกรรมสิทธิ์: </b>
                                <a href="file-checking.php?regis_id=${ data.regis_id }&filename=copyOf_ownerIdCard" class="text-decoration-none" target="_blank">ตรวจสอบ</a>
                            </p>
                            <p class="m-0 p-0"><b>สำเนาบัตรประชาชน: </b>
                                <a href="file-checking.php?regis_id=${ data.regis_id }&filename=copyOf_userIdCard" class="text-decoration-none" target="_blank">ตรวจสอบ</a>
                            </p>
                        </div>
                    </div>

                    <!-- Considerations Result ->
                    <div class="mb-3"></div>
                `;
            }

        }

    </script>

</body>
</html>