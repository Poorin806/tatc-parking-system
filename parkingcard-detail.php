<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    // If Login and come to this page for comfirm parking registration
    if (isset($_SESSION['LoginToContinueCompleteParkingCard'])) {
        unset($_SESSION['LoginToContinueCompleteParkingCard']);
    }

    //Logged in checking
    if (!isset($_SESSION['UserID'])) {
        if (isset($_GET['vehicle_id'])) {
            $VehicleID  = $_GET['vehicle_id'];
            $_SESSION['LoginToContinueCompleteParkingCard'] = $VehicleID;
            echo "<script>window.location='login.php';</script>";
        }
        else {
            echo "<script>window.location='login.php';</script>";
        }
    }
    else {
        $UserID = $_SESSION['UserID'];
    }

    // Parking Card Detail Checking
    if (isset($_GET['vehicle_id'])) {
        $vehicle_id  = $_GET['vehicle_id'];

        $sql = "SELECT 
                    parking_card.*,
                    
                    CASE
                        WHEN registration.std_id IS NOT NULL THEN 'Student'
                        ELSE 'Personnel'
                    END AS UserType,
                    
                    CASE
                        WHEN registration.std_id IS NOT NULL THEN registration.std_id
                        ELSE registration.personnel_id
                    END AS UserID,
                    
                    CASE
                        WHEN registration.std_id IS NOT NULL THEN student.img
                        ELSE personnel.img
                    END AS img,

                    CASE
                        WHEN registration.std_id IS NOT NULL THEN prefix.prefix_name
                        ELSE ''
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
                        ELSE '-'
                    END AS department,
                    
                    CASE
                        WHEN registration.std_id IS NOT NULL THEN class_level.level_name
                        ELSE '-'
                    END AS class_level,
                    
                    registration.vehicle_id,
                    vehicle_type.vehiType_name,
                    province.province_name
                    
                FROM parking_card
                JOIN
                    registration ON parking_card.regis_id = registration.regis_id
                LEFT JOIN
                    vehicle ON registration.vehicle_id = vehicle.vehicle_id
                LEFT JOIN
                    province ON vehicle.province_id = province.province_id
                LEFT JOIN
                    vehicle_type ON vehicle.vehiType_id = vehicle_type.vehiType_id
                LEFT JOIN
                    student ON registration.std_id = student.std_id
                LEFT JOIN
                    prefix ON student.prefix_id = prefix.prefix_id
                LEFT JOIN
                    department ON student.dept_id = department.dept_id
                LEFT JOIN
                    class_level ON student.level_id = class_level.level_id
                LEFT JOIN
                    personnel ON registration.personnel_id = personnel.personnel_id
                    
                WHERE
                    (registration.vehicle_id = '$vehicle_id')
        ";

        // Check if parking card has already printed
        if (SQLSelectNumRow($con, $sql) == 0) {
            echo "<script>window.location = 'parkingcard.php'</script>";
        }

        $data = SQLSelect($con, $sql);

        // Checking if image file is base64_encode or not
        $Decoded_Data = base64_decode($data['img'], true);
        if ($Decoded_Data !== false) {
            // It's encode to base64
            $realIMG = $data['img'];
        }
        else {
            // It's not
            $realIMG = base64_encode($data['img']);
        }
    }
    else {
        echo "<script>window.location = 'parkingcard.php'</script>";
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

    <!-- Parking Card css -->
    <link rel="stylesheet" href="css/parkingcard.css">

    <!-- QR-Code -->
    <script type="text/javascript" src="<?php echo $root_folder ?>js/qrcode.js"></script>

</head>
<body class="main-bg">

    <div>
        <?php
            //Navbar
            include_once 'import/navbar.php';

            //Sidebar
            include_once 'import/sidebar.php';
        ?>

        <div class="content">
            <!-- Title -->
            <div class="my-card d-flex align-items-center flex-row justify-content-between">
                <div><p class="fs-5 fw-bold m-0 p-0">บัตรจอดรถ</p></div>
                <div><a href="parkingcard.php" class="btn btn-maintheme">ย้อนกลับ</a></div>
            </div>

            <div class="container-fluid" style="position: relative; width: 100%; height: auto;">
                <!-- Parking Card lists -->
                <div class="mt-4 p-5 my-card">
                    <!-- Body -->
                    <div>
                        <!-- Title -->
                        <div class="d-flex align-items-center justify-content-center">
                            <div><img src="image/logo.png" alt="TATC Logo" width="75" class="img-fluid"></div>
                            <div>
                                <h1 class="parking-title">TATC Parking</h1>
                            </div>
                        </div>
                        <!-- SubTitle -->
                        <p class="mt-3 text-center fw-bold parking-subtitle">
                            บัตรประจำตัวครอบครองรถจักรยานยนต์ส่วนบุคคล
                            <br>
                            เลขที่ <?php echo str_pad($data['regis_id'], 5, '0', STR_PAD_LEFT) ?>
                        </p>
                        <!-- QR Code -->
                        <div class="my-5 text-center d-flex align-items-center justify-content-center" style="position: relaive;">
                            <div class="bg-danger" id="QRCode" style="width: 150px; height: 150px;">
                                <!-- QR-Code Auto Generated -->
                            </div>
                            <script type="text/javascript">
                                var qrcode = new QRCode(document.getElementById("QRCode"), {
                                    width: 150,
                                    height: 150,
                                });

                                // url = window.location.host + "<?php //echo $root_folder ?>report-detail.php?ID=<?php //echo $data['vehicle_id'] ?>";
                                url = window.location.href;

                                qrcode.makeCode(url);
                            </script>
                        </div>
                        <!-- Person Data -->
                        <div class="mt-4 d-flex align-items-center justify-content-center column-gap-3">
                            <div><img src="data:image/jpeg;base64,<?php echo $realIMG ?>" alt="UserProfile" class="img-fluid rounded" width="75"></div>
                            <div>
                                <div><b>ชื่อ</b> <?php echo $data['prefix'] . $data['name'] . " " . $data['lastname'] ?></div>
                                <div><b>แผนก</b> <?php echo $data['department'] ?></div>
                                <div><b>ระดับชั้น</b> <?php echo $data['class_level'] ?></div>
                            </div>
                        </div>
                        <!-- Vechicle Data -->
                        <?php
                            if (($data['isReceived'] == '1') && ($_SESSION['UserID'] == $user_id)) {
                                ?>
                                <div class="mt-4">
                                    <div><b>ทะเบียนรถ</b> <?php echo $data['vehicle_id'] . " (" . $data['province_name'] . ")" ?></div>
                                    <div><b>วันออกบัตร</b> <?php echo formatDateThai($data['issue_date']) ?></div>
                                    <div><b>วันหมดอายุ</b> <?php echo formatDateThai($data['expiration_date']) ?></div>
                                </div>
                                <?php
                            }
                            else if (($data['isReceived'] == '0') && ($_SESSION['UserID'] == $data['UserID'])) {
                                ?>
                                <div class="my-5 mx-0 mx-sm-4">
                                    <div class="alert alert-success" role="alert">
                                        <b>ยืนยันการรับเอกสาร เพื่อเสร็จสิ้นการลงทะเบียนฯ</b>
                                    </div>
                                    <button class="btn btn-maintheme" onclick="ConfirmCompleteRegister(<?php echo $data['regis_id'] ?>)">ยืนยันการรับเอกสาร</button>
                                </div>
                                <?php
                            }
                            else if (($data['isReceived'] == '0') && ($_SESSION['UserID'] != $data['UserID'])) {
                                ?>
                                <div class="my-5 mx-0 mx-sm-4">
                                    <div class="alert alert-warning" role="alert">
                                        <b>เจ้าของยานพาหนะยังไม่ได้รับเอกสาร หรือ เปิดใช้งานบัตร</b>
                                    </div>
                                </div>
                                <?php
                            }
                        ?>
                        <div class="mt-3 text-end">
                            <p class="m-0 p-0"><b>งานปกครอง วิทยาลัยเทคนิคสัตหีบ</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

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

</body>
</html>