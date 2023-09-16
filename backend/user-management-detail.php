<?php

    //Import connect.php file
    include_once('../import/connect.php');

    //Import SQL Query file
    include_once('../import/SQLQuery.php');

    //Logged in checking
    include_once('LoginChecking.php');

    if (!((isset($_GET['UserID'])) && (isset($_GET['UserType'])))) {
        echo "<script>window.location = 'user-management.php';</script>";
    }
    else {
        $UserID = $_GET['UserID'];
        $UserType = $_GET['UserType'];
    }

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
                        <h1 class="m-0 p-0 fs-5 fw-bold">จัดการผู้ใช้ > รายละเอียด</h1>
                    </div>
                    <div class="col-sm-9 text-end">
                        <a href="user-management.php" class="btn btn-maintheme">ย้อนกลับ</a>
                    </div>
                </div>
            </div>

            <div class="mt-4 my-card p-5">
                <div class="d-flex align-items-center column-gap-5">
                    <?php
                    
                        // Get User Data
                        if ($UserType == "Student") {
                            $sql = "SELECT
                                        student.*,
                                        prefix.prefix_name,
                                        department.dept_name,
                                        class_level.level_name,
                                        province.province_name AS province,
                                        district.district_name_th AS district,
                                        subdistrict.subDistrictName_th AS subdistrict
                                    FROM student
                                    JOIN prefix ON student.prefix_id = prefix.prefix_id
                                    LEFT JOIN department ON student.dept_id = department.dept_id
                                    LEFT JOIN class_level ON student.level_id = class_level.level_id
                                    LEFT JOIN province ON student.province_id = province.province_id
                                    LEFT JOIN district ON student.district_id = district.district_id
                                    LEFT JOIN subdistrict ON student.subdistrict_id = subdistrict.subDistrict_id
                                    WHERE
                                        student.std_id = '$UserID'
                            ";
                        }
                        else {
                            $sql = "SELECT *, null AS prefix_name FROM personnel WHERE personnel.personnel_id = '$UserID'";
                        }

                        $data = SQLSelect($con, $sql);

                        $Decoded_Data = base64_decode($data['img'], true);
                        // Checking if image file is base64_encode or not
                        if ($Decoded_Data !== false) {
                            // It's encode to base64
                            $UserIMG = $data['img'];
                        }
                        else {
                            // It's not
                            $UserIMG = base64_encode($data['img']);
                        }
                    
                    ?>
                    <!-- Image -->
                    <div>
                        <img src="data:image/jpeg;base64,<?php echo $UserIMG ?>" alt="User Profile IMG" class="img-fluid rounded" width="150">
                    </div>
                    <!-- User Info -->
                    <div>
                        <p class="fs-4 m-0 p-0">
                            <span class="fw-bold">ชื่อ: </span>
                            <?php echo $data['prefix_name'] . $data['name'] . " " . $data['lastname'] ?>
                        </p>
                        <?php
                            if ($UserType == "Student") {
                                ?>
                                <p class="fs-4 m-0 p-0">
                                    <span class="fw-bold">แผนกวิชา: </span>
                                    <?php echo $data['dept_name'] . " (" . $data['level_name'] . ")" ?>
                                </p>
                                <?php
                            }
                        ?>

                        <hr>

                        <p class="fs-5 m-0 p-0">
                            <span class="fw-bold">เบอร์โทรศัพท์: </span>
                            <?php echo $data['tel'] ?>
                        </p>
                        <?php

                            if ($UserType == "Student") {
                                ?>
                                <p class="fs-5 m-0 p-0">
                                    <span class="fw-bold">วันเกิด: </span>
                                    <?php echo formatdatethai($data['birthday']) ?>
                                </p>
                                <p class="fs-5 m-0 p-0">
                                    <span class="fw-bold">ที่อยู่: </span>
                                    <?php echo $data['address'] . " ต." . $data['subdistrict'] . " อ." . $data['district'] . " จ." . $data['province'] ?>
                                </p>
                                <?php
                            }
                            else {
                                ?>
                                <p class="fs-5 m-0 p-0">
                                    <span class="fw-bold">Email: </span>
                                    <?php echo $data['email'] ?>
                                </p>
                                <?php
                            }
                        ?>
                    </div>
                </div>
                <!-- Vehicle Details -->
                <div class="mt-5">
                    <h3 class="fw-bold">ข้อมูลการลงทะเบียนยานพาหนะ</h3>
                    <?php
                        if ($UserType == "Student") {
                            $sql = "SELECT 
                                        registration_status_detail.regis_id,
                                        MAX(registration_status_detail.regisStatus_id) AS max_regisStatus_id,
                                        registration_status_detail.notpermitreason_id,
                                        registration_status_detail.rsd_date,
                                        registration_status_detail.rsd_time,
                                        registration_status_detail.comment
                                    FROM registration_status_detail
                                    JOIN registration ON registration_status_detail.regis_id = registration.regis_id
                                    WHERE registration.std_id = '$UserID'
                                    GROUP BY registration_status_detail.regis_id;
                            ";
                        }
                        else {
                            $sql = "SELECT 
                                        registration_status_detail.regis_id,
                                        MAX(registration_status_detail.regisStatus_id) AS max_regisStatus_id,
                                        registration_status_detail.notpermitreason_id,
                                        registration_status_detail.rsd_date,
                                        registration_status_detail.rsd_time,
                                        registration_status_detail.comment
                                    FROM registration_status_detail
                                    JOIN registration ON registration_status_detail.regis_id = registration.regis_id
                                    WHERE registration.personnel_id = '$UserID'
                                    GROUP BY registration_status_detail.regis_id;
                            ";
                        }

                        // If user has registration more than once,
                        if (SQLSelectNumRow($con, $sql) > 0) {
                            $regis_id_arr = [];
                            $regisStatus_id_arr = [];
                            foreach (SQLSelectLoop($con, $sql) as $data) {
                                $regis_id_arr[] = $data['regis_id'];
                                $regisStatus_id_arr[] = $data['max_regisStatus_id'];
                            }

                            // แปลงอาร์เรย์ $regis_id เป็นสตริงที่แยกแต่ละค่าด้วยเครื่องหมาย ,
                            $regis_id_str = implode(",", $regis_id_arr);
                            $regisStatus_id_str = implode(",", $regisStatus_id_arr);

                            $sql = "SELECT
                                        LastestStatus.regis_id,
                                        LastestStatus.lastest_regisStatus_id,
                                        registration_status.regisStatus_name,
                                        registration.vehicle_id,
                                        province.province_name,
                                        vehicle_type.vehiType_name
                                    FROM
                                        registration_status_detail,
                                        (SELECT regis_id, MAX(regisStatus_id) AS lastest_regisStatus_id FROM registration_status_detail GROUP BY regis_id) AS LastestStatus
                                        
                                    JOIN registration_status ON LastestStatus.lastest_regisStatus_id = registration_status.regisStatus_id
                                    LEFT JOIN registration ON LastestStatus.regis_id = registration.regis_id
                                    LEFT JOIN vehicle ON registration.vehicle_id = vehicle.vehicle_id
                                    LEFT JOIN province ON vehicle.province_id = province.province_id
                                    LEFT JOIN vehicle_type ON vehicle.vehiType_id = vehicle_type.vehiType_id
                                        
                                    WHERE
                                        (registration_status_detail.regis_id = LastestStatus.regis_id) AND
                                        (registration_status_detail.regisStatus_id = LastestStatus.lastest_regisStatus_id) AND
                                        (LastestStatus.regis_id IN ($regis_id_str))
                                    ORDER BY LastestStatus.regis_id DESC;
                            ";
                            ?>
                            <table class="table table-stripped table-hover table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th># รหัสการลงทะเบียน</th>
                                        <th>ทะเบียนรถ</th>
                                        <th>ประเภทรถ</th>
                                        <th>สถานะการลงทะเบียน</th>
                                        <th class="text-end">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php
                                        foreach (SQLSelectLoop($con, $sql) as $data) {
                                            ?>
                                            <tr>
                                                <td><?php echo $data['regis_id'] ?></td>
                                                <td><?php echo $data['vehicle_id'] . " (" . $data['province_name'] . ")" ?></td>
                                                <td><?php echo $data['vehiType_name'] ?></td>
                                                <td>
                                                    <?php
                                                        $temp_regisStatus_name = $data['regisStatus_name'];
                                                        $temp_regisStatus_id = $data['lastest_regisStatus_id'];
                                                        switch($temp_regisStatus_id) {
                                                            case '1':
                                                            case '2':
                                                            case '3':
                                                            case '4':
                                                                echo "<span class='btn btn-warning'>$temp_regisStatus_name</span>";
                                                                break;
                                                            case '5':
                                                                echo "<span class='btn btn-success'>ลงทะเบียนเสร็จสิ้น</span>";
                                                                break;
                                                            case '6':
                                                            case '7':
                                                            case '8':
                                                            case '9':
                                                                echo "<span class='btn btn-danger'>ไม่ผ่านการอนุมัติ</span>";
                                                                break;
                                                            default;
                                                                echo "<span class='btn btn-secondary'>$temp_regisStatus_name</span>";
                                                                break;
                                                        }
                                                    ?>
                                                </td>
                                                <td class="text-end"><a href="#" class="btn btn-maintheme">จัดการ</a></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        }
                        else {
                            ?>
                            <div class="alert alert-warning" role="alert">
                                ไม่มีข้อมูลการลงทะเบียน
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>

        </div>

    </div>
    

</body>
</html>