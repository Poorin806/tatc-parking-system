<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    //Logged in checking
    if (!isset($_SESSION['UserID'])) {
        echo "<script>window.location='login.php';</script>";
    }
    else {
        $sql = "SELECT * FROM student WHERE std_id = " . $_SESSION['UserID'];
        $UserData = SQLSelect($con, $sql);
    }

    //Status Detail Data Checking
    if (!isset($_GET['ID'])) {
        echo "<script>window.location='status.php';</script>";
    }
    else {
        if ($_SESSION['UserType'] == "Student") {
            $sql = "SELECT * FROM registration WHERE (std_id = '" . $_SESSION['UserID'] . "') AND (regis_id = " . $_GET['ID'] . ")";
        }
        else {
            $sql = "SELECT * FROM registration WHERE (personnel_id = '" . $_SESSION['UserID'] . "') AND (regis_id = " . $_GET['ID'] . ")";
        }
        if (SQLSelectNumRow($con, $sql) == 0) {
            echo "<script>window.location='status.php';</script>";
        }
        else {
            $UserID = $_SESSION['UserID'];
            $ID = $_GET['ID'];
            if ($_SESSION['UserType'] == "Student") {
                $sql = "SELECT 
                        registration_status_detail.regis_id,
                        vehicle_type.vehiType_name,
                        registration.vehicle_id,
                        registration_status_detail.regisStatus_id,
                        registration_status.regisStatus_name,
                        registration_status_detail.rsd_date,
                        registration_status_detail.rsd_time,
                        registration_status_detail.comment
                    FROM
                        registration_status_detail, registration_status, registration, vehicle, vehicle_type
                    WHERE
                        (registration_status_detail.regis_id = registration.regis_id) AND
                        (registration_status_detail.regisStatus_id = registration_status.regisStatus_id) AND
                        (registration.vehicle_id = vehicle.vehicle_id) AND
                        (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                        (registration.regis_id = $ID) AND
                        (registration.std_id = '$UserID')
                    ORDER BY registration_status_detail.regisStatus_id DESC LIMIT 1
                ";
            }
            else {
                $sql = "SELECT 
                        registration_status_detail.regis_id,
                        vehicle_type.vehiType_name,
                        registration.vehicle_id,
                        registration_status_detail.regisStatus_id,
                        registration_status.regisStatus_name,
                        registration_status_detail.rsd_date,
                        registration_status_detail.rsd_time,
                        registration_status_detail.comment
                    FROM
                        registration_status_detail, registration_status, registration, vehicle, vehicle_type
                    WHERE
                        (registration_status_detail.regis_id = registration.regis_id) AND
                        (registration_status_detail.regisStatus_id = registration_status.regisStatus_id) AND
                        (registration.vehicle_id = vehicle.vehicle_id) AND
                        (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                        (registration.regis_id = $ID) AND
                        (registration.personnel_id = '$UserID')
                    ORDER BY registration_status_detail.regisStatus_id DESC LIMIT 1
                ";
            }
            $data = SQLSelect($con, $sql);
            $LastestStatus = $data['regisStatus_id'];
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

    <!-- status detail css -->
    <link rel="stylesheet" href="css/status_detail.css">

    <!-- Essential JS -->
    <?php include_once 'import/js.php'; ?>

</head>
<body class="main-bg">

    <div class="my-container">

        <?php

            //Navbar
            include_once 'import/navbar.php';

            //Sidebar
            include_once 'import/sidebar.php';

            //PDF Documents Updating
            if (isset($_POST['PDFSubmit'])) {

                $CurrentDate = date("Y-m-d");
                $CurrentTime = date("H:i:s");

                $ID = $data['regis_id'];

                if ($_FILES['PDFDocument']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['PDFDocument'];
                    $fileName = $file['name'];
                    $fileTmpName = $file["tmp_name"];

                    // เปิดไฟล์เพื่ออ่านข้อมูล
                    $fileData = fopen($fileTmpName, 'rb');
                    $fileContents = fread($fileData, filesize($fileTmpName));
                    fclose($fileData);

                    // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                    $escapedFileName1 = $con->real_escape_string($fileName);
                    $escapedFileContents1 = $con->real_escape_string($fileContents);

                    //Update PDFSignature
                    $sql = "UPDATE registration SET pdfSignature = '$escapedFileContents1', pdfSign_filename = '$escapedFileName1' WHERE regis_id = $ID";
                    SQLQuery($con, $sql);

                    //Update registration_status Detail (to State 2)
                    $sql = "INSERT INTO registration_status_detail VALUES ($ID, 2, null, '$CurrentDate', '$CurrentTime', null)";
                    SQLQuery($con, $sql);

                    // Success Message
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'ส่งเอกสารสำเร็จ',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            window.location='ajax_db.php?PDFUpdateID=$ID'
                        });
                    </script>";
                }
            }
        ?>

        <div class="content">

            <!-- Title -->
            <div class="my-card">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="fs-5 fw-bold m-0 p-0">ตรวจสอบสถานะ > รายละเอียด</p>
                    </div>
                    <div class="col text-end">
                        <a href="status.php" class="btn btn-maintheme">ย้อนกลับ</a>
                    </div>
                </div>
            </div>

            <!-- Card content -->
            <div class="mt-4 my-card p-5">
                <h3 class="fw-bold">คำขอลงทะเบียนที่ <?php echo $data['regis_id'] ?></h3>
                <div class="d-flex flex-lg-row flex-column align-items-center column-gap-5 row-gap-3">
                    <div><img src="image/<?php echo ($data['vehiType_name'] == "รถยนต์") ? "car.png" : "motorbike.png" ?>" width="100" class="img-fluid"></div>
                    <div>
                        <p class="m-0 p-0"><b>ประเภทรถ:</b> <?php echo $data['vehiType_name'] ?></p>
                        <p class="m-0 p-0"><b>ทะเบียนรถ:</b> <?php echo $data['vehicle_id'] ?></p>
                        <p class="m-0 p-0"><b>สถานะ:</b>
                            <b class="
                                <?php
                                    switch ($data['regisStatus_id']) {
                                        case 1:
                                        case 2:
                                        case 3:
                                            echo "text-warning";
                                            break;
                                        case 4:
                                        case 5:
                                            echo "text-success";
                                            break;
                                        case 6:
                                        case 7:
                                        case 8:
                                        case 9:
                                            echo "text-danger";
                                            break;
                                    }
                                ?>
                            ">
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
                                            echo "ลงทะเบียนเสร็จสิ้น";
                                            break;
                                        case 6:
                                        case 7:
                                        case 8:
                                        case 9:
                                            echo "ไม่ผ่านการตรวจสอบ";
                                            break;
                                    }
                                ?>
                            </b>
                        </p>
                    </div>
                </div>
                <!-- Progress Bar -->
                <?php
                    $sql = "SELECT 
                                registration_status_detail.regis_id,
                                vehicle_type.vehiType_name,
                                registration.vehicle_id,
                                registration_status_detail.regisStatus_id,
                                registration_status.regisStatus_name,
                                registration_status_detail.rsd_date,
                                registration_status_detail.rsd_time,
                                registration_status_detail.comment
                            FROM
                                registration_status_detail, registration_status, registration, vehicle, vehicle_type
                            WHERE
                                (registration_status_detail.regis_id = registration.regis_id) AND
                                (registration_status_detail.regisStatus_id = registration_status.regisStatus_id) AND
                                (registration.vehicle_id = vehicle.vehicle_id) AND
                                (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                                (registration.regis_id = $ID) AND
                                (registration.std_id = '$UserID')
                    ";
                ?>
                <div class="mt-5">
                    <?php
                        function ProgressBarCheck($Progress, $LastestStatus) {
                            if ($Progress == 1) {
                                switch ($LastestStatus) {
                                    case 1:
                                        return "bi-exclamation-circle-fill text-warning";
                                        break;
                                    case 2:
                                        return "bi-check-circle-fill text-success";
                                        break;
                                    case 3:
                                    case 4:
                                    case 5:
                                    case 6:
                                    case 7:
                                    case 8:
                                    case 9:
                                        return "bi-check-circle-fill text-success";
                                        break;
                                }
                            }
                            else if ($Progress == 2) {
                                switch ($LastestStatus) {
                                    case 1:
                                    case 2:
                                        return "bi-2-circle-fill text-secondary";
                                        break;
                                    case 3:
                                        return "bi-check-circle-fill text-success";
                                        break;
                                    default:
                                        return "bi-check-circle-fill text-success";
                                        break;
                                }
                            }
                            else if ($Progress == 3) {
                                switch ($LastestStatus) {
                                    case 1:
                                    case 2:
                                    case 3:
                                        return "bi-3-circle-fill text-secondary";
                                        break;
                                    case 4:
                                    case 5:
                                        return "bi-check-circle-fill text-success";
                                        break;
                                    default:
                                        return "bi-x-circle-fill text-danger";
                                        break;
                                }
                            }
                            else if ($Progress == 4) {
                                switch ($LastestStatus) {
                                    case 1:
                                    case 2:
                                    case 3:
                                    case 6:
                                    case 7:
                                    case 8:
                                    case 9:
                                        return "bi-4-circle-fill text-secondary";
                                        break;
                                    case 4:
                                        return "bi-exclamation-circle-fill text-warning";
                                        break;
                                    case 5:
                                        return "bi-check-circle-fill text-success";
                                        break;

                                }
                            }
                            else {
                                switch ($Progress) {
                                    case 2:
                                        return "bi-2-circle-fill text-secondary";
                                        break;
                                    case 3:
                                        return "bi-3-circle-fill text-secondary";
                                        break;
                                    case 4:
                                        return "bi-4-circle-fill text-secondary";
                                        break;
                                }
                            }
                        }

                        function ProgressBarCheck_Date($con, $ID, $Progress) {
                            $sql = "SELECT * FROM registration_status_detail WHERE regis_id = $ID AND regisStatus_id = $Progress";
                            $data = SQLSelect($con, $sql);

                            if ($data !== null && isset($data['rsd_time'])) {
                                return formatDateThai($data['rsd_date']) . " (" . $data['rsd_time'] . " น.)";
                            } else {
                                return null; // หรือค่าที่ต้องการให้ส่งกลับเมื่อไม่พบข้อมูล
                            }
                        }

                    ?>
                    <!-- Progress Title -->
                    <div class="d-flex align-items-start justify-content-around mb-1">
                        <div class="text-center">
                            <i class="fs-2 bi <?php echo ProgressBarCheck(1, $LastestStatus) ?>"></i>
                            <p class="m-0 p-0 fw-bold progress-responsive-show">ส่งคำขอ</p>
                            <p class="m-0 p-0 progress-responsive-show"><?php echo ProgressBarCheck_Date($con, $ID, 2) ?></p>
                        </div>
                        <div class="text-center">
                            <i class="bi fs-2 <?php echo ProgressBarCheck(2, $LastestStatus) ?>"></i>
                            <p class="m-0 p-0 fw-bold progress-responsive-show">อยู่ระหว่างการตรวจสอบ</p>
                            <p class="m-0 p-0 progress-responsive-show"><?php echo ProgressBarCheck_Date($con, $ID, 3) ?></p>
                        </div>
                        <div class="text-center">
                            <i class="fs-2 bi <?php echo ProgressBarCheck(3, $LastestStatus) ?>"></i>
                            <p class="m-0 p-0 fw-bold progress-responsive-show">ผลการตรวจสอบ</p>
                            <p class="m-0 p-0 progress-responsive-show"><?php echo ProgressBarCheck_Date($con, $ID, 4) ?></p>
                        </div>
                        <div class="text-center">
                            <i class="fs-2 bi <?php echo ProgressBarCheck(4, $LastestStatus) ?>"></i>
                            <p class="m-0 p-0 fw-bold progress-responsive-show">รับเอกสาร</p>
                            <p class="m-0 p-0 progress-responsive-show"><?php echo ProgressBarCheck_Date($con, $ID, 5) ?></p>
                        </div>
                    </div>
                    <!-- Progress Bar -->
                    <div class="progress" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style=" 
                        <?php
                            switch ($LastestStatus) {
                                case 1:
                                    echo "width: 5%; background-color: green;";
                                    break;
                                case 2:
                                    echo "width: 30%; background-color: green;";
                                    break;
                                case 3:
                                    echo "width: 57%; background-color: green;";
                                    break;
                                case 4:
                                    echo "width: 80%; background-color: green;";
                                    break;
                                case 5:
                                    echo "width: 100%; background-color: green;";
                                    break;
                                case 6:
                                case 7:
                                case 8:
                                case 9:
                                    echo "width: 70%; background-color: red;";
                                    break;
                            }
                        ?>
                        "></div>
                    </div>
                </div>

                <!-- Progress Detail Responsive -->
                <div class="mt-5 progress-responsive-hide">
                    <div class="d-flex flex-column align-items-center">
                        <div class="w-100"><i class="bi <?php echo ProgressBarCheck(1, $LastestStatus) ?> fs-5"> ส่งคำขอ <?php echo ProgressBarCheck_Date($con, $ID, 2) ?></i></div>
                        <div class="w-100"><i class="bi <?php echo ProgressBarCheck(2, $LastestStatus) ?> fs-5"> อยู่ระหว่างการตรวจสอบ <?php echo ProgressBarCheck_Date($con, $ID, 3) ?></i></div>
                        <div class="w-100"><i class="bi <?php echo ProgressBarCheck(3, $LastestStatus) ?> fs-5"> ผลการตรวจสอบ <?php echo ProgressBarCheck_Date($con, $ID, 4) ?></i></div>
                        <div class="w-100"><i class="bi <?php echo ProgressBarCheck(4, $LastestStatus) ?> fs-5"> รับเอกสาร <?php echo ProgressBarCheck_Date($con, $ID, 5) ?></i></div>
                    </div>
                </div>

                <!-- Feedback -->
                <div class="mt-5 mb-3">
                    <h5>ข้อเสนอแนะ</h5>
                    <div class="card">
                        <div class="card-body">
                            <?php
                                switch ($LastestStatus) {
                                    case 1:
                                        echo "อยู่ระหว่างการกรอกข้อมูล, กรุณากรอกข้อมูล - ลงชื่อให้ครบถ้วน (สามารถดาวน์โหลดเอกสาร เพื่อเซ็นต์ในรูปแบบ Digital แล้วแนบไฟล์ส่งใหม่ หรือ ปริ้นเป็นเอกสารแล้วส่งกับทางปกครองได้)";
                                        break;
                                    case 2:
                                        echo "ส่งคำข้อเสร็จสิ้น, โปรดรอทางงานปกครองรับเรื่อง...";
                                        break;
                                    case 3:
                                        echo "งานปกครองรับเรื่องแล้ว, อยู่ในระหว่างการตรวจสอบ...";
                                        break;
                                    case 4:
                                        echo "ยานพาหนะของคุณผ่านการตรวจสอบแล้ว, กรุณาไปรับเอกสารและสติ๊กเกอร์จอดรถที่งานปกครอง <br>
                                        สแกน QR-Code ที่สติ๊กเกอร์เพื่อเปิดใช้งานบัตรจอดรถฯ
                                        ";
                                        break;
                                    case 5:
                                        echo "การลงทะเบียนฯ เสร็จสิ้น";
                                        break;
                                    case 6:
                                    case 7:
                                    case 8:
                                    case 9:
                                        $sql = "SELECT 
                                                    registration_status_detail.*, registration_status.regisStatus_name 
                                                FROM 
                                                    registration_status_detail, registration_status
                                                WHERE
                                                    (registration_status_detail.regisStatus_id = registration_status.regisStatus_id) AND
                                                    (registration_status_detail.regisStatus_id IN (6,7,8,9)) AND
                                                    (registration_status_detail.regis_id = " . $data['regis_id'] . ")
                                        ";
                                        echo "<b>ยานพาหนะของคุณไม่ผ่านการตรวจสอบ, เนื่องจาก:</b> <br>";
                                        $HaveComment = false;
                                        foreach (SQLSelectLoop($con, $sql) as $data) {
                                            if (!$HaveComment) {
                                                if ($data['comment'] != null) {
                                                    $HaveComment = true;
                                                }
                                            }
                                            echo "- " . $data['regisStatus_name'] . "<br>";
                                        }
                                        if ($HaveComment) {
                                            echo "<br><b>ความเห็นจากงานปกครอง:</b> <br>" . $data['comment'];
                                        }
                                        echo "<br><br><span class='text-danger'>*การลงทะเบียนนี้ถูกยกเลิก หากต้องการลงทะเบียนใหม่ โปรดทำตั้งแต่ขั้นตอนแรกใหม่อีกครั้ง*</span>";
                                        break;
                                }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Document Downloading -->
                <?php
                    if ($LastestStatus == 1) {
                        ?>
                        <div class="alert alert-info" role="alert">
                            <h3 class="m-0 p-0 fw-bolder">
                                ดาวน์โหลดเอกสาร
                                <a href="registration-form-preview.php?ID=<?php echo $data['regis_id'] ?>" class="btn btn-primary fw-bold">คลิกที่นี่ !</a>
                            </h3>
                            <div class="mt-2 card">
                                <div class="card-body">
                                    <p><span class="text-primary fw-bold">กรอกข้อมูลเอกสารสมบูรณ์แล้ว?</span> สามารถส่งเอกสารฉบับสมบูรณ์พร้อมแนบที่มีลายเซ็นต์ได้ที่นี่!</p>
                                    <form id="PDFForm" action="<?php echo $_SERVER['PHP_SELF'] . "?ID=" . $data['regis_id'] ?>" method="post" enctype="multipart/form-data">
                                        <input type="text" name="PDFSubmit" id="PDFSubmit" value="PDFSubmit" hidden>
                                        <div class="d-flex flex-column flex-sm-row align-items-start gap-3">
                                            <div><input type="file" name="PDFDocument" id="PDFDocument" class="form-control" required></div>
                                            <div>
                                                <!-- <input type="submit" class="btn btn-success" name="PDFSubmit" value="ส่งเอกสาร"> -->
                                                <a href="#" class="btn btn-success" onclick="PDFSubmit()">ส่งเอกสาร</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                ?>

                <!-- Buttons -->
                <div class="text-end">
                    <?php
                        if ($LastestStatus == 1) {
                            ?>
                            <a href="#" class="btn btn-danger" onclick="Unregister(<?php echo $data['regis_id'] ?>)">ยกเลิกการลงทะเบียน</a>
                            <?php
                        }
                    ?>
                    <a href="status.php" class="btn btn-maintheme">ย้อนกลับ</a>
                </div>

            </div>
        </div>
    </div>

    <!-- Essential JS -->
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        function Unregister(id) {
            Swal.fire({
                title: 'ยกเลิกการลงทะเบียน?',
                text: "หากยกเลิกแล้วไม่สามารถเรียกข้อมลคืนได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "ajax_db.php",
                        method: "POST",
                        data: {id: id, function: 'RegistrationDelete'},
                        success: function(data) {
                            Swal.fire({
                                icon: 'success',
                                title: 'ยกเลิกการลงทะเบียนสำเร็จ',
                                confirmButtonText: 'ตกลง'
                            }).then((result) => {
                                window.location='status.php'
                            });
                        },
                        error: function(xhr, status, error) {
                            // ทำคำสั่งเมื่อเกิดข้อผิดพลาด
                            alert("เกิดข้อผิดพลาด: " + error);
                        }
                    });
                }
            })
        }

        function PDFSubmit() {
            Swal.fire({
                title: 'ยืนยันการส่งเอกสาร',
                text: "หากดำเนินขั้นตอนนี้แล้ว จะไม่สามารถยกเลิกได้",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    var PDFDocument = document.getElementById("PDFDocument");
                    if (PDFDocument.files.length != 0) {
                        document.getElementById("PDFForm").submit();
                    }
                    else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ไม่พบเอกสาร',
                            text: 'กรุณาตรวจสอบและแนบไฟล์ส่งใหม่อีกครั้ง',
                            confirmButtonText: 'ตกลง'
                        });
                    }
                }
            })
        }
    </script>

</body>
</html>