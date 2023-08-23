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

                <h2 class="fs-5 m-0 p-0">ค้นหาทะเบียนรถ <b><?php echo $_GET['ID'] ?></b></h2>

                <?php
                    if ($_GET['Type'] == "Student") {
                        $sql = "SELECT 
                                registration.vehicle_id, province.province_name, prefix.prefix_name, student.name,
                                student.lastname, department.dept_name, class_level.level_name, student.img
                            FROM 
                                registration, prefix, vehicle, province, student, department, class_level
                            WHERE
                                (registration.std_id = student.std_id) AND
                                (student.prefix_id = prefix.prefix_id) AND
                                (student.dept_id = department.dept_id) AND
                                (student.level_id = class_level.level_id) AND
                                (registration.vehicle_id = vehicle.vehicle_id) AND
                                (vehicle.province_id = province.province_id) AND
                                (registration.vehicle_id = '$VehicleID')
                        ";

                        $data = SQLSelect($con, $sql);

                        ?>
                        <div class="mt-5 mx-4 d-flex flex-column flex-sm-row align-items-center row-gap-3 column-gap-5">
                            <div>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($data['img']) ?>" alt="Report Profile IMG" width="120" height="120" class="rounded-circle object-fit-cover">
                            </div>
                            <div class="row align-items-top w-100 gy-3">
                                <div class="col-sm-6 d-flex flex-column">
                                    <span class="fs-5"><b>ชื่อ</b> <?php echo $data['prefix_name'] . $data['name'] . " " . $data['lastname'] ?></span>
                                    <span class="fs-5"><b>แผนก</b> <?php echo $data['dept_name'] ?></span>
                                    <span class="fs-5"><b>ระดับชั้น</b> <?php echo $data['level_name'] ?></span>
                                </div>
                                <div class="col-sm-6 d-flex flex-column">
                                    <span class="fs-5"><b>ทะเบียนรถ</b> <?php echo $data['vehicle_id'] ?></span>
                                    <span class="fs-5"><b>จังหวัด</b> <?php echo $data['province_name'] ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    else {
                        $sql = "SELECT 
                                    registration.vehicle_id, province.province_name, prefix.prefix_name, personnel.name,
                                    personnel.lastname, personnel.img
                                FROM 
                                    registration, prefix, vehicle, province, personnel
                                WHERE
                                    (registration.personnel_id = personnel.personnel_id) AND
                                    (registration.vehicle_id = vehicle.vehicle_id) AND
                                    (vehicle.province_id = province.province_id) AND
                                    (registration.vehicle_id = '$VehicleID')
                        ";

                        $data = SQLSelect($con, $sql);

                        ?>
                        <div class="mt-5 mx-4 d-flex flex-column flex-sm-row align-items-center row-gap-3 column-gap-5">
                            <div>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($data['img']) ?>" alt="Report Profile IMG" width="120" height="120" class="rounded-circle object-fit-cover">
                            </div>
                            <div class="d-flex flex-column row-gap-1">
                                <span class="fs-5"><b>ชื่อ</b> <?php echo $data['prefix_name'] . $data['name'] . " " . $data['lastname'] ?></span>
                                <span class="fs-5"><b>ทะเบียนรถ</b> <?php echo $data['vehicle_id'] ?></span>
                                <span class="fs-5"><b>จังหวัด</b> <?php echo $data['province_name'] ?></span>
                            </div>
                        </div>
                        <?php
                    }

                    
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
            </div>

        </div>

        <!-- End of content -->

    </div>

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

</body>
</html>