<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');
    

    //Status Detail Data Checking
    if (!isset($_GET['ID'])) {
        echo "<script>window.location='report.php';</script>";
    }
    else {
        $VehicleID = $_GET['ID'];

        // Check expiration date
        $sql = "SELECT parking_card.* 
                FROM parking_card
                JOIN registration ON parking_card.regis_id = registration.regis_id
                WHERE
                    (parking_card.issue_date <= parking_card.expiration_date) AND
                    (registration.vehicle_id = '$VehicleID')
        ";

        //Logged in checking
        if (!isset($_SESSION['UserID'])) {

            $temp_data = SQLSelect($con, $sql);
            if ($temp_data['isReceived'] == '0') {
                $_SESSION['LoginToContinueCompleteParkingCard'] = $VehicleID;
            }

            echo "<script>window.location='login.php';</script>";
        }
        else {
            if (SQLSelectNumRow($con, $sql) == 0) {
                echo "<script>window.location = 'report.php'</script>";
            }
            else {
                $temp_data = SQLSelect($con, $sql);
                if ($temp_data['isReceived'] == '0') {
                    echo "<script>window.location = 'parkingcard-detail.php?vehicle_id=$VehicleID';</script>";
                }
            }
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

</head>
<body class="main-bg">

    <div class="my-container">

        <?php

            //Navbar
            include_once 'import/navbar.php';

            //Sidebar
            include_once 'import/sidebar.php';
        ?>

        <!-- Content -->

        <div class="content">

            <!-- Title -->
            <div class="my-card">
                <div class="d-flex justify-content-between align-items-center row-gap-3">
                    <div>
                        <p class="fs-5 fw-bold m-0 p-0">รายงานปัญหา</p>
                    </div>
                    <div>
                        <a href="report.php" class="btn btn-maintheme">ย้อนกลับ</a>
                    </div>
                </div>
            </div>


            <!-- Vehicle Data -->
            <div class="mt-4 my-card px-3 py-4">

                <h2 class="fs-5 m-0 p-0">ค้นหาทะเบียนรถ <b><?php echo $VehicleID ?></b></h2>

                <?php

                    // If this user has already approved and enable his card
                    $sql = "SELECT 
                                registration_status_detail.*,
                                
                                CASE 
                                    WHEN registration.std_id IS NOT NULL THEN registration.std_id
                                    ELSE registration.personnel_id
                                END AS user_id,

                                CASE 
                                    WHEN registration.std_id IS NOT NULL THEN 'Student'
                                    ELSE 'Personnel'
                                END AS UserType,
                                
                                CASE 
                                    WHEN registration.std_id IS NOT NULL THEN student.img
                                    ELSE personnel.img
                                END AS img,
                                
                                CASE 
                                    WHEN registration.std_id IS NOT NULL THEN prefix.prefix_name
                                END AS prefix,
                                
                                CASE 
                                    WHEN registration.std_id IS NOT NULL THEN student.name
                                    ELSE personnel.name
                                END AS name,
                                
                                CASE 
                                    WHEN registration.std_id IS NOT NULL THEN student.lastname
                                    ELSE personnel.lastname
                                END AS lastname,
                                
                                CASE 
                                    WHEN registration.std_id IS NOT NULL THEN department.dept_name
                                END AS department,
                                
                                CASE 
                                    WHEN registration.std_id IS NOT NULL THEN class_level.level_name
                                END AS class_level,
                                
                                registration.vehicle_id,
                                vehicle_type.vehiType_name,
                                province.province_name
                                
                            FROM 
                                registration_status_detail
                            JOIN
                                registration ON registration_status_detail.regis_id = registration.regis_id
                            LEFT JOIN
                                student ON registration.std_id = student.std_id
                            LEFT JOIN
                                personnel ON registration.personnel_id = personnel.personnel_id
                            LEFT JOIN
                                prefix ON student.prefix_id = prefix.prefix_id
                            LEFT JOIN
                                department ON student.dept_id = department.dept_id
                            LEFT JOIN
                                class_level ON student.level_id = class_level.level_id
                            LEFT JOIN
                                vehicle ON registration.vehicle_id = vehicle.vehicle_id
                            LEFT JOIN
                                vehicle_type ON vehicle.vehiType_id = vehicle_type.vehiType_id
                            LEFT JOIN
                                province ON vehicle.province_id = province.province_id
                                
                            WHERE
                                (registration_status_detail.regisStatus_id = 5) AND
                                (registration_status_detail.regisStatus_id NOT IN (6, 7, 8, 9)) AND
                                (registration.vehicle_id = '$VehicleID')
                    ";

                    $data = SQLSelect($con, $sql);
                    $user_id = $data['user_id'];

                    // If it's a student data
                    if ($data['prefix'] != null) {
                        ?>
                        <div class="mt-5 mx-4 d-flex flex-column flex-sm-row align-items-center row-gap-3 column-gap-5">
                            <div>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($data['img']) ?>" alt="Report Profile IMG" width="120" height="120" class="rounded-circle object-fit-cover">
                            </div>
                            <div class="row align-items-top w-100 gy-3">
                                <div class="col-sm-6 d-flex flex-column">
                                    <span class="fs-5"><b>ชื่อ</b> <?php echo $data['prefix'] . $data['name'] . " " . $data['lastname'] ?></span>
                                    <span class="fs-5"><b>แผนก</b> <?php echo $data['department'] ?></span>
                                    <span class="fs-5"><b>ระดับชั้น</b> <?php echo $data['class_level'] ?></span>
                                </div>
                                <div class="col-sm-6 d-flex flex-column">
                                    <span class="fs-5"><b>ทะเบียนรถ</b> <?php echo $data['vehicle_id'] ?></span>
                                    <span class="fs-5"><b>จังหวัด</b> <?php echo $data['province_name'] ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    // If it's a personnel data
                    else {
                        ?>
                        <div class="mt-5 mx-4 d-flex flex-column flex-sm-row align-items-center row-gap-3 column-gap-5">
                            <div>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($data['img']) ?>" alt="Report Profile IMG" width="120" height="120" class="rounded-circle object-fit-cover">
                            </div>
                            <div class="d-flex flex-column row-gap-1">
                                <span class="fs-5"><b>ชื่อ</b> <?php echo $data['prefix'] . $data['name'] . " " . $data['lastname'] ?></span>
                                <span class="fs-5"><b>ทะเบียนรถ</b> <?php echo $data['vehicle_id'] ?></span>
                                <span class="fs-5"><b>จังหวัด</b> <?php echo $data['province_name'] ?></span>
                            </div>
                        </div>
                        <?php
                    }

                    $IsRegisterSuccessfully_sql = "SELECT 
                                                        registration_status_detail.regis_id,
                                                        
                                                        CASE
                                                            WHEN registration.std_id IS NOT NULL THEN registration.std_id
                                                            ELSE registration.personnel_id
                                                        END AS user_id
                                                        
                                                    FROM registration_status_detail 
                                                    JOIN 
                                                        registration ON registration_status_detail.regis_id = registration.regis_id 
                                                    WHERE 
                                                        (registration_status_detail.regisStatus_id = 5) AND 
                                                        (registration_status_detail.regisStatus_id NOT IN (1, 2, 3, 4, 6, 7, 8, 9)) AND 
                                                        ((registration.vehicle_id = '$VehicleID'))
                    ";

                    $IsRegisterSuccessfully = SQLSelectNumRow($con, $IsRegisterSuccessfully_sql);

                    // Report Details
                    // If this user has already approved and enable his card
                    if ($IsRegisterSuccessfully > 0 && $_SESSION['UserID'] != $user_id) {
                        ?>
                        <form action="test.php" method="POST" class="mx-5 mt-3" enctype="multipart/form-data">
                            <hr class="my-5">
                            <h2 class="fs-5">การรายงาน</h2>
                            <div class="mx-4">
                                <input type="text" name="vehicle_id" value="<?php echo $data['vehicle_id'] ?>" hidden>
                                <input type="text" name="UserType" value="<?php echo $_SESSION['UserType'] ?>" hidden>
                                <input type="text" name="UserID" value="<?php echo $_SESSION['UserID'] ?>" hidden>
                                <div class="row align-items-top gy-3">
                                    <div class="col-sm-6 form-check">
                                        <input class="form-check-input" type="radio" value="2" name="ResultOfReport[]" id="ResultOfReport_2" onclick="ReportReason(2)">
                                        <label class="form-check-label" for="ResultOfReport_2">
                                            จอดรถขวางทาง / ไม่ถูกที่
                                        </label>
                                    </div>
                                    <div class="col-sm-6 form-check">
                                        <input class="form-check-input" type="radio" value="3" name="ResultOfReport[]" id="ResultOfReport_3" onclick="ReportReason(3)">
                                        <label class="form-check-label" for="ResultOfReport_3">
                                            รถล้ม
                                        </label>
                                    </div>
                                    <div class="col-sm-6 form-check">
                                        <input class="form-check-input" type="radio" value="4" name="ResultOfReport[]" id="ResultOfReport_4" onclick="ReportReason(4)">
                                        <label class="form-check-label" for="ResultOfReport_4">
                                            รถไม่มีสติ๊กเกอร์
                                        </label>
                                    </div>
                                    <div class="col-sm-6 form-check">
                                        <input class="form-check-input" type="radio" value="5" name="ResultOfReport[]" id="ResultOfReport_5" onclick="ReportReason(5)">
                                        <label class="form-label" for="ResultOfReport_5">
                                            อื่น ๆ (กรณีมีกว่ากว่า 1 ข้อ หรือเพิ่มเติมสาเหตุ)
                                        </label>
                                        <textarea cols="30" rows="3" class="form-control" id="OtherReason" name="OtherReason" disabled></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- File Upload (Clue) -->
                            <div class="mt-5 card">
                                <div class="card-body">
                                    <p><span class="text-danger">*</span><span class="text-primary fw-bold">แนบรูปภาพ (หลักฐาน) ประกอบ</span></p>
                                    <div><input type="file" name="Image" id="Image" class="form-control" required></div>
                                </div>
                            </div>

                            <!-- Confirm Button -->
                            <div class="mt-5 text-end">
                                <a href="report.php" class="btn btn-danger">ยกเลิก</a>
                                <button type="button" class="btn btn-primary" onclick="ReportConfirm()">รายงาน</button>
                                <input type="submit" class="btn btn-primary" id="ReportSubmit" name="ReportSubmit" value="รายงาน" hidden>
                            </div>
                        </form>
                        <?php
                    }
                    else {
                        if ($_SESSION['UserID'] == $user_id) {
                            ?>
                            <div class="my-5 mx-4">
                                <div class="alert alert-warning" role="alert">
                                    <b>ไม่สามารถรายงานบัญชีตนเองได้</b>
                                </div>
                            </div>
                            <?php
                        }
                        else {
                            echo "<script>window.location = 'parkingcard-detail.php?vehicle_id=$VehicleID';</script>";
                        }
                    }
                ?>
            </div>

        </div>

        <!-- End of content -->

    </div>

    <!-- Essentials JavaScript -->
    <script>
        var ResultOfReport_2 = document.getElementById("ResultOfReport_2");
        var ResultOfReport_3 = document.getElementById("ResultOfReport_3");
        var ResultOfReport_4 = document.getElementById("ResultOfReport_4");
        var ResultOfReport_5 = document.getElementById("ResultOfReport_5");
        var OtherReason = document.getElementById("OtherReason");
        var ReportSubmit = document.getElementById("ReportSubmit");
        var fileInput = document.getElementById("Image");
        var Total_Report = 0;

        function ReportReason(id) {
            switch (id) {
                case 2 :
                    if (ResultOfReport_2.checked) {
                        Total_Report = Total_Report + 1;
                    }
                    else {
                        if (Total_Report != 0) {
                            Total_Report = Total_Report - 1;
                        }
                    }
                    OtherReason.disabled = true;
                    OtherReason.required = false;
                    OtherReason.value = "";
                    break;
                case 3 :
                    if (ResultOfReport_3.checked) {
                        Total_Report = Total_Report + 1;
                    }
                    else {
                        if (Total_Report != 0) {
                            Total_Report = Total_Report - 1;
                        }
                    }
                    OtherReason.disabled = true;
                    OtherReason.required = false;
                    OtherReason.value = "";
                    break;
                case 4 :
                    if (ResultOfReport_4.checked) {
                        Total_Report = Total_Report + 1;
                    }
                    else {
                        if (Total_Report != 0) {
                            Total_Report = Total_Report - 1;
                        }
                    }
                    OtherReason.disabled = true;
                    OtherReason.required = false;
                    OtherReason.value = "";
                    break;
                case 5 :
                    if (ResultOfReport_5.checked) {
                        Total_Report = Total_Report + 1;
                        OtherReason.disabled = false;
                        OtherReason.required = true;
                    }
                    else {
                        OtherReason.disabled = true;
                        OtherReason.required = false;
                        OtherReason.value = "";
                        if (Total_Report != 0) {
                            Total_Report = Total_Report - 1;
                        }
                    }
                    break;
            }
        }

        function ReportConfirm() {
            if ((Total_Report == 0) || (!fileInput.files.length > 0)) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ต้องเลือกเหตุผลในการรายงาน และรูปภาพหลักฐานก่อน',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#3172f5',
                })
            }
            else {
                Swal.fire({
                    icon: 'info',
                    title: 'ยืนยันการรายงาน',
                    text: 'เมื่อรายงานแล้วข้อมูลจะถูกส่งไปยังผู้รายงานและงานปกครอง',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#3172f5',
                    cancelButtonColor: '#c41a1a',
                }).then((result) => {
                    if (result.isConfirmed) {
                        ReportSubmit.click();
                    }
                });
            }
        }
    </script>

    <?php include_once 'import/js.php'; ?>

</body>
</html>