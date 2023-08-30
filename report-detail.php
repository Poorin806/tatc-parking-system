<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    //Logged in checking
    include_once('import/LoginChecking.php');

    //Status Detail Data Checking
    if (!isset($_GET['ID'])) {
        echo "<script>window.location='report.php';</script>";
    }
    else {
        $VehicleID = $_GET['ID'];
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
                                ((registration_status_detail.regisStatus_id = 4) OR (registration_status_detail.regisStatus_id = 5)) AND
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
                                                        registration_status_detail.regis_id
                                                    FROM 
                                                        registration_status_detail
                                                    JOIN
                                                        registration ON registration_status_detail.regis_id = registration.regis_id
                                                        
                                                    WHERE
                                                        (registration_status_detail.regisStatus_id = 5) AND
                                                        (registration_status_detail.regisStatus_id NOT IN (1, 2, 3, 4, 6, 7, 8, 9)) AND
                                                        ((registration.vehicle_id = '$VehicleID'))
                    ";

                    $IsRegisterSuccessfully = SQLSelectNumRow($con, $IsRegisterSuccessfully_sql);

                    // Is Register Successfully
                    if ($IsRegisterSuccessfully > 0) {
                        ?>
                        <div class="mx-4">
                            <hr class="my-5">
                            <h2 class="m-0 p-0 fs-5">การรายงาน</h2>
                            <form action="#" method="POST" class="mx-5 mt-3">
                                <div class="row align-items-top gy-3">
                                    <div class="col-sm-6 form-check">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label">
                                            จอดรถขวางทาง / ไม่ถูกที่
                                        </label>
                                    </div>
                                    <div class="col-sm-6 form-check">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label">
                                            รถล้ม
                                        </label>
                                    </div>
                                    <div class="col-sm-6 form-check">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label">
                                            รถไม่มีสติ๊กเกอร์
                                        </label>
                                    </div>
                                    <div class="col-sm-6 form-check">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-label">
                                            อื่น ๆ
                                        </label>
                                        <textarea name="" cols="30" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mt-5 mx-4 text-end">
                            <a href="report.php" class="btn btn-danger">ยกเลิก</a>
                            <input type="submit" class="btn btn-primary" value="รายงาน">
                        </div>
                        <?php
                    }
                    else {
                        if ($_SESSION['UserID'] == $user_id) {
                            ?>
                            <div class="my-5 mx-4">
                                    <div class="alert alert-success" role="alert">
                                        <b>ยืนยันการรับเอกสาร เพื่อเสร็จสิ้นการลงทะเบียนฯ</b>
                                    </div>
                                    <button class="btn btn-maintheme" onclick="ConfirmCompleteRegister(<?php echo $data['regis_id'] ?>)">ยืนยันการรับเอกสาร</button>
                                </div>
                            <?php
                        }
                        else {
                            echo "<script>window.location = 'index.php';</script>";
                        }
                    }
                ?>
            </div>

        </div>

        <!-- End of content -->

    </div>

    <script>
        function ConfirmCompleteRegister(id) {
            Swal.fire({
                    icon: 'warning',
                    title: 'ยืนยันการรับเอกสาร?',
                    text: "เมื่อยืนยันแล้ว จะถือว่าเป็นการลงทะเบียนฯ อย่างสมบูรณ์!",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: "ajax_db.php",
                        data: { function: 'ConfirmCompleteRegistration', id: id },
                        success: function(data){
                            Swal.fire({
                                icon: 'success',
                                title: 'การลงทะเบียนเสร็จสิ้น',
                                text: data,
                                confirmButtonText: 'ยืนยัน'
                            }).then((result) => {
                                window.location.reload();
                            });
                        }
                    });
                }
            })
        }
    </script>

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

</body>
</html>