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
                <div class="row align-items-center justify-content-between row-gap-3">
                    <div class="col-sm-3">
                        <p class="fs-5 fw-bold m-0 p-0">รายงานปัญหา</p>
                    </div>
                    <div class="col-sm-9">
                        <form action="#">
                            <div class="d-flex flex-column flex-sm-row align-items-center gap-2 justify-content-end">
                                <i class="bi bi-qr-code-scan p-0 m-0 align-self-start align-self-sm-center"> สแกน qr-code ที่สติ๊กเกอร์รถ หรือค้นหาด้วยทะเบียนรถ</i>
                                <div class="align-self-start align-self-sm-center d-flex align-items-center gap-2">
                                    <input type="text" class="form-control" placeholder="ทะเบียนรถ" style="width: 200px;">
                                    <input type="submit" value="ค้นหา" class="btn-maintheme" style="width: 70px; height: 35px;">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Vehicle Data -->
            <div class="mt-4 my-card px-3 py-4">
                <!-- Vehicle Data Title -->
                <div class="row align-items-center justify-content-between row-gap-2 mb-2">
                    <div class="col-sm-3">
                        <p class="fs-5 fw-bold m-0 p-0">ข้อมูลยานพาหนะ</p>
                    </div>
                    <div class="col-sm-9">
                        <form action="#">
                            <div class="d-flex align-items-center justify-content-sm-end gap-2">
                                <p class="m-0">รูปแบบการแสดงข้อมูล</p>
                                <select class="form-select" id="Vehicle-display-option" style="width: 100px;">
                                    <option value="1">การ์ด</option>
                                    <option value="2" selected>ตาราง</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Vehicle Data Card -->
                <div class="row align-items-center justify-content-start g-3" id="Vehicle-card" style="display: none;">
                    <div class="col-12"><p class="mt-3 m-0 p-0 fs-5">ยานพาหนะ - นักศึกษา</p></div>
                    <?php
                        $sql_std = "SELECT 
                                    registration_status_detail.regis_id,
                                    registration.vehicle_id,
                                    prefix.prefix_name,
                                    student.name,
                                    student.lastname,
                                    department.dept_name,
                                    student.img
                                FROM
                                    registration_status_detail, registration, vehicle, prefix, student, department, parking_card
                                WHERE
                                    (parking_card.regis_id = registration.regis_id) AND
                                    (parking_card.isReceived = 1) AND
                                    (parking_card.issue_date <= parking_card.expiration_date) AND
                                    (registration_status_detail.regis_id = registration.regis_id) AND
                                    (registration.vehicle_id = vehicle.vehicle_id) AND
                                    (registration.std_id = student.std_id) AND
                                    (student.prefix_id = prefix.prefix_id) AND
                                    (student.dept_id = department.dept_id) AND
                                    (registration_status_detail.regisStatus_id = 5)
                                    ";

                        $sql_personnel = "SELECT 
                                            registration_status_detail.regis_id,
                                            registration.vehicle_id,
                                            personnel.name,
                                            personnel.lastname,
                                            personnel.img
                                        FROM
                                            registration_status_detail, registration, vehicle, personnel, parking_card
                                        WHERE
                                            (parking_card.regis_id = registration.regis_id) AND
                                            (parking_card.isReceived = 1) AND
                                            (parking_card.issue_date <= parking_card.expiration_date) AND
                                            (registration_status_detail.regis_id = registration.regis_id) AND
                                            (registration.vehicle_id = vehicle.vehicle_id) AND
                                            (registration.personnel_id = personnel.personnel_id) AND
                                            (registration_status_detail.regisStatus_id = 5)
                        ";

                        foreach (SQLSelectLoop($con, $sql_std) as $data) {
                            ?>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center column-gap-4">
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($data['img']) ?>" alt="Profile img" width="100" height="100" class="rounded-circle">
                                            <div>
                                                <h3 class="m-0 p-0 fs-5 fw-bold"><?php echo $data['vehicle_id'] ?></h3>
                                                <p class="m-0 p-0"><?php echo $data['prefix_name'] . $data['name'] . " " . $data['lastname'] ?></p>
                                                <p class="m-0 p-0">แผนกวิชา <?php echo $data['dept_name'] ?></p>
                                                <a href="report-detail.php?ID=<?php echo $data['vehicle_id'] ?>&Type=Student" class="btn btn-maintheme mt-2">รายงาน</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }

                        ?>
                        <div class="col-12"><p class="mt-3 m-0 p-0 fs-5">ยานพาหนะ - บุคลากร</p></div>
                            <?php
                            foreach (SQLSelectLoop($con, $sql_personnel) as $data) {
                                ?>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center column-gap-4">
                                                <img src="data:image/jpeg;base64,<?php echo base64_encode($data['img']) ?>" alt="Profile img" width="100" height="100" class="rounded-circle">
                                                <div>
                                                    <h3 class="m-0 p-0 fs-5 fw-bold"><?php echo $data['vehicle_id'] ?></h3>
                                                    <p class="m-0 p-0"><?php echo $data['name'] . " " . $data['lastname'] ?></p>
                                                    <a href="report-detail.php?ID=<?php echo $data['vehicle_id'] ?>&Type=Personnel" class="btn btn-maintheme mt-2">รายงาน</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }

                    ?>
                </div>

                <!-- Vehicle Data Table (Student) -->
                <div class="w-100" id="Vehicle-table" style="display: table;">
                    <p class="m-0 p-0 mb-3 fs-5">ยานพาหนะ - นักศึกษา</p>
                    <table class="table align-middle mt-3">
                        <thead>
                            <tr>
                                <th scope="col">ทะเบียน</th>
                                <th scope="col">ชื่อ-สกุล</th>
                                <th scope="col">แผนกวิชา</th>
                                <th scope="col" class="text-end">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach (SQLSelectLoop($con, $sql_std) as $data) {
                                    ?>
                                    <tr>
                                        <th><?php echo $data['vehicle_id'] ?></td>
                                        <td><?php echo $data['prefix_name'] . $data['name'] . " " . $data['lastname'] ?></td>
                                        <td><?php echo $data['dept_name'] ?></td>
                                        <td class="text-end"><a href="report-detail.php?ID=<?php echo $data['vehicle_id'] ?>&Type=Student" class="btn btn-maintheme">รายงาน</a></td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Vehicle Data Table (Personnel) -->
                <div class="w-100" id="Vehicle-table-2" style="display: table;">
                    <p class="m-0 p-0 mb-3 fs-5">ยานพาหนะ - บุคลากร</p>
                    <table class="table align-middle mt-3">
                        <thead>
                            <tr>
                                <th scope="col">ทะเบียน</th>
                                <th scope="col">ชื่อ-สกุล</th>
                                <th scope="col" class="text-end">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach (SQLSelectLoop($con, $sql_personnel) as $data) {
                                    ?>
                                    <tr>
                                        <th><?php echo $data['vehicle_id'] ?></td>
                                        <td><?php echo $data['name'] . " " . $data['lastname'] ?></td>
                                        <td class="text-end"><a href="report-detail.php?ID=<?php echo $data['vehicle_id'] ?>&Type=Personnel" class="btn btn-maintheme">รายงาน</a></td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>  <!-- End of content -->
    </div>

    <script>
        const selectElement = document.getElementById('Vehicle-display-option');
        const cardDiv = document.getElementById('Vehicle-card');
        const tableDiv = document.getElementById('Vehicle-table');
        const tableDiv2 = document.getElementById('Vehicle-table-2');

        selectElement.addEventListener('change', (event) => {
            const selectedValue = event.target.value;
            if (selectedValue === '1') {
                cardDiv.style.display = 'flex';
                tableDiv.style.display = 'none';
                tableDiv2.style.display = 'none';
            } else if (selectedValue === '2') {
                cardDiv.style.display = 'none';
                tableDiv.style.display = 'table';
                tableDiv2.style.display = 'table';
            }
        });
    </script>

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

</body>
</html>