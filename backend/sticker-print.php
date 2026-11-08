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

    <!-- Sticker css -->
    <link rel="stylesheet" href="<?php echo $root_folder ?>css/sticker.css">

    <!-- QR-Code -->
    <script type="text/javascript" src="<?php echo $root_folder ?>js/qrcode.js"></script>
    
</head>
<body class="main-bg">

    <!-- Download PDF File -->
    <div class="container fixed-bottom mb-5 text-end">
        <button class="btn btn-maintheme" onclick="DownloadPDF('Hi')">
            ดาวน์โหลด/ปริ้น สติ๊กเกอร์ <i class="bi bi-file-earmark-arrow-down-fill"></i>
        </button>
    </div>

    <div class="my-container">

        <?php

            //Navbar
            include_once 'navbar.php';

            //Sidebar
            include_once 'sidebar.php';

        ?>

        <div class="content">
            
            <!-- Title -->
            <div class="my-card mb-4">
                <div class="row align-items-center justify-content-between row-gap-3">
                    <div class="col-sm-3">
                        <h1 class="m-0 p-0 fs-5 fw-bold">สติ๊กเกอร์ - บัตรจอดรถ</h1>
                    </div>
                    <div class="col-sm-9 text-end">
                        <a href="sticker.php" class="btn btn-maintheme">ย้อนกลับ</a>
                    </div>
                </div>
            </div>

            <!-- Stickers -->
            <div class="my-card" style="position: relative;">
                <?php

                    $IssuedDate = date("Y-m-d");
                    $IssuedDateFormat = formatDateThai($IssuedDate);

                    // Exired Date
                    $ExipredDate = date("Y-m-d", strtotime("+1 year", strtotime($IssuedDate)));
                    $ExipredDateFormat = formatDateThai($ExipredDate);

                    if (isset($_POST['PrintSingle'])) {
                        $regis_id = $_POST['PrintID'];
                        $total_card = 1;

                        $sql = "SELECT 
                                    registration_status_detail.*,
                                    
                                    CASE 
                                        WHEN registration.std_id IS NOT NULL THEN registration.std_id
                                        ELSE registration.personnel_id
                                    END AS user_id,
                                    
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
                                    
                                    vehicle_type.vehiType_name,
                                    registration.vehicle_id
                                    
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
                                    vehicle ON registration.vehicle_id = vehicle.vehicle_id
                                LEFT JOIN
                                    vehicle_type ON vehicle.vehiType_id = vehicle_type.vehiType_id
                                    
                                WHERE
                                    (registration_status_detail.regis_id = $regis_id) AND
                                    (registration_status_detail.regisStatus_id = (SELECT MAX(regisStatus_id) AS regisStatus_id 
                                                                                FROM registration_status_detail
                                                                                WHERE regis_id = $regis_id
                                                                                )
                                    )
                        ";
                    }
                    else if (isset($_GET['MultiPrint'])) {
                        $PrintList = explode(',', $_GET['MultiPrint']);
                        $total_card = count($PrintList);
                        $PrintListString = implode(',', $PrintList);
                        $sql = "SELECT 
                                    registration_status_detail.*,
                                    
                                    CASE 
                                        WHEN registration.std_id IS NOT NULL THEN registration.std_id
                                        ELSE registration.personnel_id
                                    END AS user_id,
                                    
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
                                    
                                    vehicle_type.vehiType_name,
                                    registration.vehicle_id
                                    
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
                                    vehicle ON registration.vehicle_id = vehicle.vehicle_id
                                LEFT JOIN
                                    vehicle_type ON vehicle.vehiType_id = vehicle_type.vehiType_id
                                WHERE
                                    registration_status_detail.regis_id IN ($PrintListString)
                                GROUP BY
                                    registration_status_detail.regis_id
                                HAVING
                                    MAX(registration_status_detail.regisStatus_id) = 5 or MAX(registration_status_detail.regisStatus_id) = 4
                        ";

                    }
                ?>
                <!-- Hidden Form -->
                <input type="text" readonly value="<?php echo $IssuedDate ?>" id="IssuedDate" hidden>
                <input type="text" readonly value="<?php echo $ExipredDate ?>" id="ExipredDate" hidden>

                <!-- Sticker Area -->
                <!-- <button class="btn btn-danger" onclick="QrCodeGenerator()">QR-Code</button> -->
                <div class="w-100 d-flex justify-content-center">
                    <div class="my-3" style="border: 2px solid black;">
                        <div class="a4 my-4 p-2" style="width: 200mm; height: 255mm;" id="A4_Stickers">
                            <!-- List of Stickers -->
                            <div class="d-flex justify-content-between flex-wrap row-gap-3 m-0 p-0 mx-3">
                                <?php
                                    $total_regis_arr = [];
                                    foreach (SQLSelectLoop($con, $sql) as $data) {
                                        $vehicle_id = $data['vehicle_id'];
                                        array_push($total_regis_arr, $vehicle_id);

                                        // Check if sticker is already printed
                                        $isPrinted_sql = "SELECT parking_card.*
                                                            FROM
                                                                parking_card
                                                            JOIN
                                                                registration ON parking_card.regis_id = registration.regis_id
                                                            WHERE
                                                                (registration.vehicle_id = '$vehicle_id') AND
                                                                (parking_card.issue_date <= parking_card.expiration_date)
                                        ";
                                        // if sticker is already printed (True)
                                        if (SQLSelectNumRow($con, $isPrinted_sql) != 0) {
                                            $temp_data = SQLSelect($con, $isPrinted_sql);
                                            $New_IssuedDateFormat = formatDateThai($temp_data['issue_date']);
                                            $New_ExipredDateFormat = formatDateThai($temp_data['expiration_date']);
                                        }
                                        // if sticker is already printed (False)
                                        else {
                                            $New_IssuedDateFormat = $IssuedDateFormat;
                                            $New_ExipredDateFormat = $ExipredDateFormat;
                                        }

                                        ?>
                                            <!-- Stickers Card (Loop) -->
                                            <div class="sticker-border" style="width: 300px; height: 300px;">
                                                <div class="d-flex flex-column align-items-center">
                                                    <!-- Logo -->
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?php echo $root_folder ?>image/LOGO-TATC_Praking-Black_Text.png" alt="TATC Logo" width="50" height="50">
                                                        <p class="fw-bold fs-6 m-0 p-0">วิทยาลัยเทคนิคสัตหีบ</p>
                                                    </div>
                                                    <!-- QR-Code -->
                                                    <div class="sticker-qr-code" style="width: 100px; height: 100px;">
                                                        <div id="<?php echo $data['vehicle_id'] ?>" style="width:100%; height:100%;" class="bg-body-secondary"></div>
                                                    </div>
                                                    <!-- Details -->
                                                    <div class="mt-3 sticker-detail">
                                                        <div class="">
                                                            <p class="m-0 p-0"><b>ผู้ใช้ยานพาหนะ:</b> <?php echo $data['prefix'] . $data['name'] . " " . $data['lastname'] ?></p>
                                                            <p class="m-0 p-0"><b>ทะเบียนรถ:</b> <?php echo $data['vehicle_id'] ?></p>
                                                            <p class="m-0 p-0"><b>วันที่ออกบัตร:</b> <?php echo $New_IssuedDateFormat ?></p>
                                                            <p class="m-0 p-0"><b>วันหมดอายุ:</b> <?php echo $New_ExipredDateFormat ?></p>
                                                        </div>
                                                    </div>
                                                    <!-- Footer -->
                                                    <div class="mt-2 text-end w-100" style="font-size: 12px;">
                                                        <p class="m-0 p-0">งานปกครองวิทยาลัยเทคนิคสัตหีบ</p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    
    <!-- JS QR-Code -->
    <script type="text/javascript">
        var total_regis_arr = <?php echo json_encode($total_regis_arr) ?>;
        console.log(total_regis_arr);

        function QrCodeGenerator() {
            for (var i = 0; i < total_regis_arr.length; i++) {

                var qrcode = new QRCode(document.getElementById(total_regis_arr[i]), {
                    width: 100,
                    height: 100,
                });

                url = window.location.host + "<?php echo $root_folder ?>report-detail.php?ID=" + total_regis_arr[i];

                qrcode.makeCode(url);
            }
        }
    </script>

    <!-- HTML2PDF -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
	<script>
        function previewPDF(filenameInput) {
            var element = document.getElementById("A4_Stickers");
            element.style.display = 'block';

            var opt = {
                margin:			0.2,
                filename:		filenameInput,
                image:			{ type: 'jpeg', quality: 0.98 },
                html2canvas:	{ scale: 2 },
                jsPDF:			{ unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).outputPdf('datauristring').then(function (pdfDataUri) {
                // [Preview file in new tab]
                var newTab = window.open();
                newTab.document.open();
                newTab.document.write('<iframe src="' + pdfDataUri + '" width="100%" height="100%"></iframe>');
                newTab.document.close();

                // [Download file in the same page]
                // // สร้างลิงค์สำหรับดาวน์โหลด PDF
                // var downloadLink = document.createElement('a');
                // downloadLink.href = pdfDataUri;
                // downloadLink.download = "แบบฟอร์มการขอนำยานพาหนะมาใช้ภายในวิทยาลัยเทคนิคสัตหีบ (ปค.16) [" + filenameInput + '].pdf';
                
                // // คลิกลิงค์ดาวน์โหลด PDF
                // downloadLink.click();
            });
        }

        // ฟังก์ชันสำหรับเลื่อนไปด้านบนสุด
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // ฟังก์ชันสำหรับเลื่อนลงด้านล่างสุด
        function scrollToBottom(filename) {
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });

            previewPDF(filename);

            setTimeout(BackToStickerPage, 1000);
        }

        function BackToStickerPage() {
            window.location = "sticker.php";
        }

        // ฟังก์ชันที่เรียกทั้งสองฟังก์ชันแบบละเอียดด้วยค่า delay
        function DownloadPDF(filename) {

            var IssuedDate = document.getElementById('IssuedDate').value;
            var ExipredDate = document.getElementById('ExipredDate').value;

            var total_regis_arr = <?php echo json_encode($total_regis_arr) ?>;

            $.ajax({
                type: "POST",
                url: "../ajax_db.php",
                data: {
                    BackendFunction: "PrintSticker", 
                    id: total_regis_arr,
                    IssuedDate: IssuedDate,
                    ExipredDate: ExipredDate
                },
                dataType: "json",
                success: function(data){

                    console.log(data);

                    QrCodeGenerator();
                    scrollToTop();
                    setTimeout(scrollToBottom, 1000);
                }
            });
        }
    </script>


</body>
</html>