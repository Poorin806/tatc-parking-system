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
            <div class="mt-4 my-card">
                <div class="d-flex flex-column row-gap-4 flex-md-row justify-content-md-between align-items-md-center">
                    <div class="">
                        <p class="m-0 p-0 fs-5 fw-bold">การแจ้งเตือน</p>
                    </div>
                    <div class="">
                        <div class="d-flex flex-column justify-content-start gap-3 flex-sm-row">
                            <!-- Sort -->
                            <select id="Sort" class="form-select" onchange="Filter()">
                                <option value="0">- ลำดับ -</option>
                                <option value="1">ใหม่ที่สุด</option>
                                <option value="2">เก่าที่สุด</option>
                            </select>
                            <!-- Notification Type -->
                            <select id="Type" class="form-select" onchange="Filter()">
                                <option value="0">- ประเภท -</option>
                                <option value="1">งานปกครอง</option>
                                <option value="2">การถูกรายงาน</option>
                            </select>
                            <!-- Notification Read or Unread -->
                            <select id="Status" class="form-select" onchange="Filter()">
                                <option value="0">- สถานะ -</option>
                                <option value="1">ยังไม่อ่าน</option>
                                <option value="2">อ่านแล้ว</option>
                            </select>
                            <!-- Button -->
                            <div class="d-flex align-items-center justify-content-between column-gap-3">
                                <a href="notification.php" class="btn btn-danger text-light" style="width: fit-content;">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                                <a href="index.php" class="btn btn-maintheme">
                                    <i class="bi bi-arrow-left-circle-fill"></i>
                                </a>                                
                            </div>
                        </div>

                        <!-- Filter Checking -->
                        <script>
                            var Filter = new URLSearchParams(window.location.search);
                            var Filter_Sort = Filter.get('Sort');
                            var Filter_Type = Filter.get('Type');
                            var Filter_Status = Filter.get('Status');

                            var Type = document.getElementById('Type');
                            var Status = document.getElementById('Status');

                            //Filter Sort
                            if ((Filter_Sort == null) || (Filter_Sort == "")) {
                                Sort.value = "0";
                            }
                            else if (Filter_Sort == "DESC") {
                                Sort.value = "1";
                            }
                            else {
                                Sort.value = "2";
                            }

                            //Filter Sort
                            if ((Filter_Type == null) || (Filter_Type == "")) {
                                Type.value = "0";
                            }
                            else if (Filter_Type == "Report") {
                                Type.value = "2";
                            }
                            else {
                                Type.value = "1";
                            }

                            //Filter Status
                            if ((Filter_Status == null) || (Filter_Status == "")) {
                                Status.value = "0";
                            }
                            else if (Filter_Status == "Read") {
                                Status.value = "2";
                            }
                            else {
                                Status.value = "1";
                            }
                        </script>

                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <?php


                // Sorting
                if ((isset($_GET['Sort'])) && ($_GET['Sort'] != null)) {
                    $Sort = $_GET['Sort'];
                }
                else {
                    $Sort = "DESC";
                }

                // Notification Type
                if (isset($_GET['Type']) && $_GET['Type'] == "Report") {
                    $Noti_Type = "AND (notification.notiType_id <> 1)";
                }
                else if (isset($_GET['Type']) && $_GET['Type'] == "AdministrativeWork") {
                    $Noti_Type = "AND (notification.notiType_id = 1)";
                }
                else {
                    $Noti_Type = "";
                }

                // Notification Status
                if (isset($_GET['Status']) && $_GET['Status'] == "Read") {
                    $Noti_Status = "AND (notification.isRead = '1')";
                }
                else if (isset($_GET['Status']) && $_GET['Status'] == "unRead") {
                    $Noti_Status = "AND (notification.isRead = '0')";
                }
                else {
                    $Noti_Status = "";
                }

                $user_id = $_SESSION['UserID'];

                if ($_SESSION['UserType'] == "Student") {
                    $sql = "SELECT
                                notification.noti_id,
                                CASE
                                    WHEN notification.std_id IS NOT NULL THEN 'Student'
                                    ELSE 'Personnel'
                                END AS Reporter_UserType,
                                CASE
                                    WHEN notification.std_id IS NOT NULL THEN notification.std_id
                                    ELSE notification.personnel_id
                                END AS Reporter_UserID,
                                notification.vehicle_id,
                                registration.regis_id,
                                CASE
                                    WHEN registration.std_id IS NOT NULL THEN 'Student'
                                    ELSE 'Personnel'
                                END AS Reported_Type,
                                CASE
                                    WHEN registration.std_id IS NOT NULL THEN registration.std_id
                                    ELSE registration.personnel_id
                                END AS Reported_UserID,
                                notification.notiType_id,
                                notification_type.details,
                                notification.comment,
                                notification.notiDate,
                                notification.notiTime,
                                notification.isRead,
                                notification.admin_receive_status
                            FROM
                                notification
                            JOIN vehicle ON notification.vehicle_id = vehicle.vehicle_id
                            LEFT JOIN notification_type ON notification.notiType_id = notification_type.notiType_id
                            LEFT JOIN registration ON registration.vehicle_id = notification.vehicle_id
                            WHERE
                                (registration.std_id IS NOT NULL AND registration.std_id = '$user_id') $Noti_Type $Noti_Status
                            ORDER BY noti_id $Sort
                        ";
                } else {
                    $sql = "SELECT
                                notification.noti_id,
                                CASE
                                    WHEN notification.std_id IS NOT NULL THEN 'Student'
                                    ELSE 'Personnel'
                                END AS Reporter_UserType,
                                CASE
                                    WHEN notification.std_id IS NOT NULL THEN notification.std_id
                                    ELSE notification.personnel_id
                                END AS Reporter_UserID,
                                notification.vehicle_id,
                                registration.regis_id,
                                CASE
                                    WHEN registration.std_id IS NOT NULL THEN 'Student'
                                    ELSE 'Personnel'
                                END AS Reported_Type,
                                CASE
                                    WHEN registration.std_id IS NOT NULL THEN registration.std_id
                                    ELSE registration.personnel_id
                                END AS Reported_UserID,
                                notification.notiType_id,
                                notification_type.details,
                                notification.comment,
                                notification.notiDate,
                                notification.notiTime,
                                notification.isRead,
                                notification.admin_receive_status
                            FROM
                                notification
                            JOIN vehicle ON notification.vehicle_id = vehicle.vehicle_id
                            LEFT JOIN notification_type ON notification.notiType_id = notification_type.notiType_id
                            LEFT JOIN registration ON registration.vehicle_id = notification.vehicle_id
                            WHERE
                                (registration.personnel_id IS NOT NULL AND registration.personnel_id = '$user_id') $Noti_Type $Noti_Status
                            ORDER BY noti_id $Sort
                    ";
                }

                if (SQLSelectNumRow($con, $sql) != 0) {
                    // Calculate total number of pages
                    $totalRecords = SQLSelectNumRow($con, $sql);
                    $recordsPerPage = 10; // Set the number of records per page here
                    $totalPages = ceil($totalRecords / $recordsPerPage);
                
                    // Calculate current page from the URL parameter or default to 1
                    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                
                    // Calculate the offset based on the current page
                    $offset = ($current_page - 1) * $recordsPerPage;
                
                    // Add LIMIT and OFFSET to your SQL query
                    $sql .= " LIMIT $recordsPerPage OFFSET $offset";
                
                    // Execute the modified SQL query
                    $result = SQLSelectLoop($con, $sql);
                
                    foreach ($result as $data) {
                        ?>
                        <div class="mt-4 my-card">
                            <div class="d-flex align-items-center">
                                <div class="ms-2 me-4">
                                    <img src="image/<?php
                                        switch($data['notiType_id']) {
                                            case 2:
                                            case 3:
                                            case 4:
                                            case 5:
                                                echo "report.png";
                                                break;
                                            default:
                                                echo "logo.png";
                                                break;
                                        }
                                    ?>" width="75" class="img-fluid">
                                </div>
                                <div class="w-100">
                                    <h5 class="fw-bold" style="margin: 0; padding: 0;">
                                        <?php
                                            switch ($data['notiType_id']) {
                                                case 1:
                                                    echo "#" . $data['noti_id'] . " แจ้งเตือนจากงานปกครอง - " . $data['details'];
                                                    break;
                                                case 2:
                                                case 3:
                                                case 4:
                                                case 5:
                                                    echo "#" . $data['noti_id'] . " ถูกรายงาน - " . $data['details'];
                                                    break;
                                            }
                                        ?>
                                    </h5>
                                    <p class="fs-6" style="maring: 0; padding: 0;"><?php echo ($data['comment'] != null) ? "เนื้อหา: " . $data['comment'] : "" ?></p>
                                    <div class="row align-items-center gy-2">
                                        <div class="col-12 col-sm-6 text-start">
                                            <?php echo formatDateThai($data['notiDate']) . " (เวลา " . $data['notiTime'] . " น.)" ?>
                                        </div>
                                        <div class="col-12 col-sm-6 text-end">
                                            <button type="button" class="btn btn-maintheme-outline" onclick="ReportDetail(<?php echo $data['noti_id'] ?>)">รายละเอียด</button>
                                            <?php
                                                if ($data['isRead'] == '0') {
                                                    ?>
                                                    <button type="button" class="btn btn-maintheme" onclick="ConfirmMatter(<?php echo $data['noti_id'] ?>)">รับทราบ</button>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <!-- Pagination -->
                    <div class="mt-5 position-relative bg-primary">
                        <nav class="position-absolute" style="left: 50%; transform: translateX(-50%);">
                            <ul class="pagination">
                                <?php
                                $maxPageNumbers = 10; // Set the maximum number of page numbers to display

                                if ($totalPages > $maxPageNumbers) {
                                    $startPage = max($current_page - floor($maxPageNumbers / 2), 1);
                                    $endPage = min($startPage + $maxPageNumbers - 1, $totalPages);
                                    if ($endPage - $startPage + 1 < $maxPageNumbers) {
                                        $startPage = max($endPage - $maxPageNumbers + 1, 1);
                                    }
                                } else {
                                    $startPage = 1;
                                    $endPage = $totalPages;
                                }

                                // Always show "Previous" button
                                if ($current_page > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page - 1) . '">ก่อนหน้า</a></li>';
                                }

                                // Show the first page
                                if ($startPage > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                                    if ($startPage > 2) {
                                        echo '<li class="page-item"><span class="page-link">...</span></li>';
                                    }
                                }

                                // Page numbers
                                for ($i = $startPage; $i <= $endPage; $i++) {
                                    if ($i == $current_page) {
                                        echo '<li class="page-item active"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                                    } else {
                                        echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                                    }
                                }

                                // Show the last page
                                if ($endPage < $totalPages) {
                                    if ($endPage < $totalPages - 1) {
                                        echo '<li class="page-item"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '">' . $totalPages . '</a></li>';
                                }

                                // Always show "Next" button
                                if ($current_page < $totalPages) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page + 1) . '">ถัดไป</a></li>';
                                }
                                ?>
                            </ul>
                        </nav>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="mt-4 my-card px-3 py-4">
                        <div class="alert alert-warning m-0 p-3" role="alert">
                            ไม่มีข้อมูลการแจ้งเตือน ณ ขณะนี้
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>

    <!-- Report Detail (Modal) -->
    <div class="modal fade" id="ReportDetail" tabindex="-1" aria-labelledby="ReportDetail_Title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ReportDetail_Title">
                        <!-- JS Ajax fetch data -->
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ReportDetail_Body">
                    <!-- JS Ajax fetch data -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-maintheme" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>

    <!-- Essentials JavaScript -->
    <script>
        function Filter() {
            var Sort = document.getElementById('Sort').value;
            var Type = document.getElementById('Type').value;
            var Status = document.getElementById('Status').value;

            var SortURL = "";
            var TypeURL = "";
            var StatusURL = "";
            
            switch (Sort) {
                case '0' :
                    SortURL = "";
                    break;
                case '1' :
                    SortURL = "DESC";
                    break;
                case '2' :
                    SortURL = "ASC";
                    break;
            }

            switch (Type) {
                case '0' :
                    TypeURL = "";
                    break;
                case '1' :
                    TypeURL = "AdministrativeWork";
                    break;
                case '2' :
                    TypeURL = "Report";
                    break;
            }

            switch (Status) {
                case '0' :
                    StatusURL = "";
                    break;
                case '1' :
                    StatusURL = "unRead";
                    break;
                case '2' :
                    StatusURL = "Read";
                    break;
            }

            window.location = 'notification.php?Sort=' + SortURL + "&Type=" + TypeURL + "&Status=" + StatusURL;
        }
    </script>

    <script>
        function ReportDetail(id) {

            var ReportDetail_Title = document.getElementById("ReportDetail_Title");
            var ReportDetail_Body = document.getElementById("ReportDetail_Body");

            $.ajax({
                url: 'ajax_db.php',
                method: 'POST',
                data: { 
                    function: 'User_ReoprtDetail_Modal',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    ReportDetail_Title.innerHTML = `
                        <b>ถูกรายงาน - ${ data['details'] }</b>
                    `;
                    ReportDetail_Body.innerHTML = `
                        <div>
                            <span class="fw-bold fw-6">รายละเอียด:</span> ${ (data['comment'] != null) ? data['comment'] : "-" }
                        </div>
                        <div class="mt-3">
                            <span class="fw-bold fw-6">รูปภาพประกอบ</span>
                            ${
                                (data['img'] != null) 
                                ? 
                                    `
                                    <div class="mt-2">
                                        <img src="data:image/jpeg;base64,${ data['img'] }" alt="Report Picture" class="img-fluid" width="100%">
                                    </div>
                                    ` 
                                : 
                                    `
                                        <div class="mt-2 alert alert-warning" role="alert">ไม่มีรูปภาพประกอบ</div>
                                    `
                            }
                            
                        </div>
                    `;
                    $("#ReportDetail").modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

        }

        function ConfirmMatter(id) {
            Swal.fire({
                title: 'ยืนยันรับทราบ?',
                text: "เมื่อ ยืนยัน แล้วจะเป็นการรับทราบและข้อมูลจะถูกจัดกับในหมวดหมู่ อ่านแล้ว",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'ajax_db.php',
                        method: 'POST',
                        data: { 
                            function: 'User_Reoprt_ConfirmMatter',
                            id: id
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ทำเป็นเครื่องหมายอ่านแล้ว',
                                    confirmButtonText: 'ตกลง'
                                }).then((result) => {
                                    window.location.reload();
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }
            });
        }
    </script>
    <?php include_once 'import/js.php'; ?>

</body>
</html>