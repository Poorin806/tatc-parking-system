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
                        <h1 class="m-0 p-0 fs-5 fw-bold">การรายงานปัญหา</h1>
                    </div>
                    <div class="col-sm-9 text-end">
                        <a href="index.php" class="btn btn-maintheme">
                            ย้อนกลับ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="mt-4 my-card">
                <div class="d-flex align-items-center column-gap-2">
                    <div>Filter</div>
                    <!-- Date Sorting -->
                    <div>
                        <select id="Sort" class="form-select" onchange="Filter()">
                            <option value="0">- เรียงลำดับ -</option>
                            <option value="1">ใหม่ที่สุด</option>
                            <option value="2">เก่าที่สุด</option>
                        </select>
                    </div>
                    <!-- Row Record -->
                    <div>
                        <select id="Record" class="form-select" onchange="Filter()">
                            <option value="0">- จำนวนแถว -</option>
                            <option value="2">2 แถว</option>
                            <option value="10">10 แถว</option>
                            <option value="50">50 แถว</option>
                            <option value="100">100 แถว</option>
                            <option value="1">ทั้งหมด</option>
                        </select>
                    </div>
                    <!-- Read Type -->
                    <div>
                        <select id="ReadType" class="form-select" onchange="Filter()">
                            <option value="0">- การรายงาน -</option>
                            <option value="1">อ่านแล้ว</option>
                            <option value="2">ยังไม่ได้อ่าน</option>
                        </select>
                    </div>
                    <!-- Report Type -->
                    <div>
                        <select id="ReportType" class="form-select" onchange="Filter()">
                            <option value="0">- ประเภท -</option>
                            <option value="2">รถจอดขวางทาง</option>
                            <option value="3">รถล้ม</option>
                            <option value="4">ย้ายรถ</option>
                            <option value="5">อื่น ๆ</option>
                            <option value="1">ทั้งหมด</option>
                        </select>
                    </div>
                    <!-- Search Bar -->
                    <div>
                        <input type="text" id="Search" class="form-control" placeholder="ค้นหา">
                    </div>
                    <!-- Search BTN -->
                    <div>
                        <a href="#" onclick="Filter()" class="btn btn-maintheme">
                            <i class="bi bi-search"></i>
                        </a>
                    </div>
                    <!-- Clear Filter -->
                    <div>
                        <a href="report.php" class="btn btn-danger">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    </div>
                </div>
                <!-- Filter Checking -->
                <script>
                    var Filter = new URLSearchParams(window.location.search);
                    var Filter_Search = Filter.get('Search');
                    var Filter_Sort = Filter.get('Sort');
                    var Filter_Record = Filter.get('Record');
                    var Filter_ReadType = Filter.get('ReadType');
                    var Filter_ReportType = Filter.get('ReportType');

                    var Sort = document.getElementById('Sort');
                    var Record = document.getElementById('Record');
                    var Search = document.getElementById('Search');
                    var ReadType = document.getElementById('ReadType');
                    var ReportType = document.getElementById('ReportType');

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

                    //Read Type
                    if ((Filter_ReadType == null) || (Filter_ReadType == "")) {
                        ReadType.value = "0";
                    }
                    else if (Filter_ReadType == "Read") {
                        ReadType.value = "1";
                    }
                    else {
                        ReadType.value = "2";
                    }

                    //Filter Report Type
                    if ((Filter_ReportType == null) || (Filter_ReportType == "")) {
                        ReportType.value = "0";
                    }
                    else if (Filter_ReportType == "1") {
                        ReportType.value = "1";
                    }
                    else {
                        ReportType.value = Filter_ReportType;
                    }

                    //Filter Record
                    if (Filter_Record == "1") {
                        Record.value = "1";
                    }
                    else if ((Filter_Record == "0") || (Filter_Record == null) || (Filter_Record == "")) {
                        Record.value = "0";
                    }
                    else {
                        Record.value = Filter_Record;
                    }

                    Search.value = Filter_Search;
                </script>

                <!-- PHP Filter -->
                <?php
                    //Sorting
                    if ((isset($_GET['Sort'])) && ($_GET['Sort'] != null)) {
                        $Sort = $_GET['Sort'];
                    }
                    else {
                        $Sort = "DESC";
                    }

                    //Read Type
                    if ((isset($_GET['ReadType'])) && ($_GET['ReadType'] != null)) {
                        $Read = ($_GET['ReadType'] == "Read") ? "1" : "0";
                    }
                    else {
                        $Read = "0";
                    }

                    //Report Type
                    if ((isset($_GET['ReportType'])) && ($_GET['ReportType'] != null)) {
                        if ($_GET['ReportType'] == "1") {
                            $ReportType = "1";
                            $ReportType_SQL = "AND notification.notiType_id IN ('2', '3', '4', '5')";
                        }
                        else if ($_GET['ReportType'] == "0") {
                            $ReportType = "";
                            $ReportType_SQL = "AND notification.notiType_id IN ('2', '3', '4', '5')";
                        }
                        else {
                            $ReportType = $_GET['ReportType'];
                            $ReportType_SQL = "AND notification.notiType_id = '$ReportType'";
                        }
                    }
                    else {
                        $ReportType = "0";
                        $ReportType_SQL = "AND notification.notiType_id IN ('2', '3', '4', '5')";
                    }

                    //Limit
                    if ((isset($_GET['Record'])) && ($_GET['Record'] != null) && ($_GET['Record'] != "1")) {
                        $Limit = $_GET['Record'];
                    }
                    else {
                        $Limit = 9999999;
                    }

                    //Search
                    if ((isset($_GET['Search'])) && ($_GET['Search'] != null)) {
                        $Search = $_GET['Search'];
                        $Search_SQL = "AND (notification.noti_id LIKE '%$Search%' OR notification.vehicle_id LIKE '%$Search%' OR notification.std_id LIKE '%$Search%' OR notification.personnel_id LIKE '%$Search%' OR student.name LIKE '%$Search%' OR student.lastname LIKE '%$Search%' OR personnel.name LIKE '%$Search%' OR personnel.lastname LIKE '%$Search%' OR notification.vehicle_id LIKE '%$Search%')";
                    }
                    else {
                        $Search = "";
                        $Search_SQL = "";
                    }
                ?>
            </div>

            <div class="mt-4 my-card">
                <form action="#" method="POST">
                    <table class="table table-stripped table-hover table-hover align-middle">
                        <thead>
                            <tr>
                                <th>
                                    <?php
                                        if (!(isset($_GET['ReadType']) && $_GET['ReadType'] == "Read")) {
                                            ?>
                                            <input type="checkbox" class="form-check-input" name="CheckAll" id="CheckAll_Checkbox" onchange="CheckAll_Func()">
                                            <?php
                                        }
                                    ?>
                                    #
                                </th>
                                <!-- <th>รหัสผู้รายงาน</th> -->
                                <!-- <th>ชื่อ-นามสกุล</th> -->
                                <!-- <th>ประเภทผู้รายงาน</th> -->
                                <th>ทะเบียนรถ</th>
                                <th>ประเภทการรายงาน</th>
                                <th>วันที่รายงาน</th>
                                <th class="text-end">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                                $sql = "SELECT 
                                            notification.noti_id,
                                            
                                            CASE
                                                WHEN notification.std_id IS NOT NULL THEN notification.std_id
                                                ELSE notification.personnel_id
                                            END AS user_id,
                                            
                                            CASE
                                                WHEN notification.std_id IS NOT NULL THEN 'นักศึกษา'
                                                ELSE 'บุคลากร'
                                            END AS UserType,
                                            
                                            CASE
                                                WHEN notification.std_id IS NOT NULL THEN prefix.prefix_name
                                                ELSE null
                                            END AS prefix,
                                            
                                            CASE
                                                WHEN notification.std_id IS NOT NULL THEN student.name
                                                ELSE personnel.name
                                            END AS name,
                                            
                                            CASE
                                                WHEN notification.std_id IS NOT NULL THEN student.lastname
                                                ELSE personnel.lastname
                                            END AS lastname,
                                            
                                            notification.vehicle_id,
                                            province.province_name,
                                            notification.notiType_id,
                                            notification_type.details,
                                            notification.notiDate,
                                            notification.notiTime,
                                            notification.comment
                                            
                                        FROM notification
                                        JOIN notification_type ON notification.notiType_id = notification_type.notiType_id
                                        LEFT JOIN student ON notification.std_id = student.std_id
                                        LEFT JOIN personnel ON notification.personnel_id = personnel.personnel_id
                                        LEFT JOIN prefix ON student.prefix_id = prefix.prefix_id
                                        LEFT JOIN vehicle ON notification.vehicle_id = vehicle.vehicle_id
                                        LEFT JOIN province ON vehicle.province_id = province.province_id
                                        WHERE
                                            notification.admin_receive_status = '$Read'
                                            $Search_SQL
                                            $ReportType_SQL
                                        ORDER BY notification.noti_id $Sort
                                ";

                                // echo "<script>console.log(`$sql`);</script>";

                                $total_report = SQLSelectNumRow($con, $sql);

                                if ($total_report > 0) {                 

                                    // Calculate total number of pages
                                    $totalRecords = SQLSelectNumRow($con, $sql);
                                    $recordsPerPage = $Limit; // Set the number of records per page here
                                    $totalPages = ceil($totalRecords / $recordsPerPage);
                                
                                    // Calculate current page from the URL parameter or default to 1
                                    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                                
                                    // Calculate the offset based on the current page
                                    $offset = ($current_page - 1) * $recordsPerPage;
                                
                                    // Add LIMIT and OFFSET to your SQL query
                                    $sql .= " LIMIT $recordsPerPage OFFSET $offset";

                                    $total_noti_array = array();

                                    foreach (SQLSelectLoop($con, $sql) as $data) {
                                        array_push($total_noti_array, $data['noti_id']);
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                    if (!(isset($_GET['ReadType']) && $_GET['ReadType'] == "Read")) {
                                                        ?>
                                                        <input type="checkbox" class="form-check-input" name="SingleCheck" id="<?php echo $data['noti_id'] ?>" onchange="SingleCheck_Func(<?php echo $data['noti_id'] ?>)">
                                                        <?php
                                                    }
                                                ?>
                                                <?php echo $data['noti_id'] ?>
                                            </td>
                                            <!-- <td><?php echo $data['user_id'] ?></td> -->
                                            <!-- <td>
                                                <?php echo $data['prefix'] . $data['name'] . " " . $data['lastname']; ?>
                                            </td> -->
                                            <!-- <td><?php echo $data['UserType'] ?></td> -->
                                            <td><?php echo $data['vehicle_id'] . " (" . $data['province_name'] . ")" ?></td>
                                            <td><?php echo $data['details'] ?></td>
                                            <td><?php echo formatdatethai($data['notiDate']) . " (" . $data['notiTime'] . "น.)" ?></td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-maintheme" onclick="ReportDetail(<?php echo $data['noti_id'] ?>)">รายละเอียด</button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else {
                                    ?>
                                    <tr>
                                        <td colspan="8">
                                            <div class="alert alert-warning m-0" role="alert">
                                                ไม่มีข้อมูลการรายงาน
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>

                    <div class="my-2" id="AllRead" style="display: none;">
                        <button type="button" class="btn btn-outline-primary" onclick="Multi_MarkRead()">ทำเครื่องหมายทั้งหมดเป็นอ่านแล้ว</button>
                    </div>
                </form>

                <!-- Pagination -->
                <?php
                    if ($total_report > 0) {
                        ?>
                        <div class="d-flex justify-content-center">
                            <nav>
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

                                    $pagination_link = "Search=" . $Search . "&Sort=" . $Sort . "&Record=" . $Limit . "&ReadType=" . $Read ."&ReportType=" . $ReportType;

                                    // Always show "Previous" button
                                    if ($current_page > 1) {
                                        echo '<li class="page-item"><a class="page-link" href="?' . $pagination_link . '&page=' . ($current_page - 1) . '">ก่อนหน้า</a></li>';
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
                                            echo '<li class="page-item active"><a class="page-link" href="?' . $pagination_link . '&page=' . $i . '">' . $i . '</a></li>';
                                        } else {
                                            echo '<li class="page-item"><a class="page-link" href="?' . $pagination_link . '&page=' . $i . '">' . $i . '</a></li>';
                                        }
                                    }

                                    // Show the last page
                                    if ($endPage < $totalPages) {
                                        if ($endPage < $totalPages - 1) {
                                            echo '<li class="page-item"><span class="page-link">...</span></li>';
                                        }
                                        echo '<li class="page-item"><a class="page-link" href="?' . $pagination_link . '&page=' . $totalPages . '">' . $totalPages . '</a></li>';
                                    }

                                    // Always show "Next" button
                                    if ($current_page < $totalPages) {
                                        echo '<li class="page-item"><a class="page-link" href="?' . $pagination_link . '&page=' . ($current_page + 1) . '">ถัดไป</a></li>';
                                    }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                        <?php
                    }
                ?>
            </div>

        </div>

        <!-- Totoal Summarize -->
        <div class="container fixed-bottom mb-5">
            <p class="bg-primary text-light p-2 m-0 rounded fw-bolder fs-6 position-absolute end-0 bottom-50" style="width: fit-content;">
                รายงานปัญหา <?php echo (isset($total_noti_array)) ? count($total_noti_array) : "0" ?> รายงาน
            </p>
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
                    <button type="button" class="btn btn-primary" id="MarkAsReadButton" onclick="MarkAsRead()" style="display: block;">ทำเครื่องหมายเป็นอ่านแล้ว</button>
                    <button type="button" class="btn btn-maintheme" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>

        var total_check = 0;
        var total_check_arr = <?php echo (isset($total_noti_array)) ? json_encode($total_noti_array) : "0"; ?>;
        var AllRead = document.getElementById("AllRead");
        var CheckAll_Checkbox = document.getElementById("CheckAll_Checkbox");
        var total_Checkbox = <?php echo (isset($total_noti_array)) ? count($total_noti_array) : "0" ?>;

        function Filter() {
            var Sort = document.getElementById('Sort').value;
            var Record = document.getElementById('Record').value;
            var Search = document.getElementById('Search').value;
            var ReadType = document.getElementById('ReadType').value;
            var ReportType = document.getElementById('ReportType').value;

            var SortURL = "";
            var RecordURL = "";
            var ReadTypeURL = "";
            var ReportTypeURL = "";

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

            switch (ReportType) {
                case '0' :
                    ReportTypeURL = "";
                    break;
                default:
                    ReportTypeURL = ReportType;
                    break;
            }

            switch (ReadType) {
                case '0' :
                    ReadTypeURL = "";
                    break;
                case '1' :
                    ReadTypeURL = "Read";
                    break;
                case '2' :
                    ReadTypeURL = "UnRead";
                    break;
            }

            if (Record == "0") {
                RecordURL = "";
            }
            else {
                RecordURL = Record;
            }
            window.location = 'report.php?Search=' + Search + '&Sort=' + SortURL + '&Record=' + RecordURL + '&ReadType=' + ReadTypeURL + '&ReportType=' + ReportTypeURL;
        }

        function CheckAll_Func() {
            if (CheckAll_Checkbox.checked) {
                for (var i = 0; i < total_check_arr.length; i++) {
                    var temp_checkbox = document.getElementById(total_check_arr[i]);
                    temp_checkbox.checked = true;
                }
                total_check = total_check_arr.length;
            }
            else {
                for (var i = 0; i < total_check_arr.length; i++) {
                    var temp_checkbox = document.getElementById(total_check_arr[i]);
                    temp_checkbox.checked = false;
                }
                total_check = 0;
            }

            CheckboxChecking();
        }

        function AllCheck_Checking() {
            if (total_check == total_Checkbox) {
                CheckAll_Checkbox.checked = true;
            }
            else {
                CheckAll_Checkbox.checked = false;
            }
        }

        function CheckboxChecking() {
            if (total_check > 0) {
                AllRead.style.display = "block";
            }
            else {
                AllRead.style.display = "none";
            }

            AllCheck_Checking();
        }

        function SingleCheck_Func(id) {
            var checkbox = document.getElementById(id);

            if (checkbox.checked) {
                // alert(id + " = checked");
                total_check = total_check + 1;
                console.log(total_check);
            }
            else {
                // alert(id + " = unchecked");
                total_check = total_check - 1;
                console.log(total_check);
            }

            CheckboxChecking();
        }

        function Multi_MarkRead() {
            var All_Check_arr = [];
            for (var i = 0; i < total_check_arr.length; i++) {
                var temp_checkbox = document.getElementById(total_check_arr[i]);
                if (temp_checkbox.checked) {
                    All_Check_arr.push(total_check_arr[i]);
                }
            }
            $.ajax({
                url: '../ajax_db.php',
                method: 'POST',
                data: { 
                    BackendFunction: 'ReoprtDetail_Multi_MarkRead',
                    id: All_Check_arr
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    Swal.fire({
                        icon: 'success',
                        title: 'ทำรายการสำเร็จ'
                    }).then((result) => {
                        window.location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function ReportDetail(id) {

            var ReportDetail_Title = document.getElementById("ReportDetail_Title");
            var ReportDetail_Body = document.getElementById("ReportDetail_Body");

            $.ajax({
                url: '../ajax_db.php',
                method: 'POST',
                data: { 
                    BackendFunction: 'ReoprtDetail_Modal',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    ReportDetail_Title.innerHTML = `
                        <b>#${ data['noti_id'] } ถูกรายงาน - ${ data['details'] }</b>
                    `;

                    if (data['admin_receive_status'] == "1") {
                        document.getElementById("MarkAsReadButton").style.display = "none";
                    }

                    ReportDetail_Body.innerHTML = `
                        <div>
                            <p class="fs-6 m-0 p-0">
                                <span class="fw-bold">ผู้รายงาน: </span>
                                ${ (data['reporter_prefix'] != null) ? `${ data['reporter_prefix'] }` : `` }
                                ${ data['reporter_name'] + " " + data['reporter_lastname'] }
                            </p>
                            <p class="fs-6 m-0 p-0">
                                <span class="fw-bold">แผนกวิชา: </span>
                                ${
                                    (data['reporter_department'] != null) 
                                    ? 
                                        `${ data['reporter_department'] + " (" + data['reporter_class_level'] + ")" }` 
                                    : 
                                        `- (บุคลากร)`
                                }
                            </p>
                        </div>

                        <hr>

                        <div class="mt-3">
                            <p class="fs-6 m-0 p-0">
                                <span class="fw-bold fw-6">ทะเบียนรถ:</span> ${ data['reported_vehicle_id'] + " (" + data['reported_vehicle_province'] + ")" }
                            </p>
                            <p class="fs-6 m-0 p-0">
                                <span class="fw-bold fw-6">การรายงาน:</span> ${ data['details'] }
                            </p>
                            <p class="fs-6 m-0 p-0">
                                <span class="fw-bold fw-6">รายละเอียด (เพิ่มเติม):</span> ${ (data['comment'] != null) ? data['comment'] : "-" }
                            </p>
                            <p class="mt-2 fs-6 m-0 p-0">
                                <span class="fw-bold">ผู้ถูกรายงาน: </span>
                                ${ (data['reported_prefix'] != null) ? `${ data['reported_prefix'] }` : `` } 
                                ${ data['reported_name'] + " " + data['reported_lastname'] }
                            </p>
                            <p class="fs-6 m-0 p-0">
                                <span class="fw-bold">แผนกวิชา: </span>
                                ${
                                    (data['reporter_department'] != null) 
                                    ? 
                                        `${ data['reporter_department'] + " (" + data['reporter_class_level'] + ")" }` 
                                    : 
                                        `-`
                                }
                            </p>
                            <p class="mt-2">
                                <span class="fw-bold fw-6">รูปภาพประกอบ</span>
                            </p>
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
                        <div class="mt-3">
                            <div>
                                <input type="text" id="isReadButton" value="${ id }" readonly hidden>
                                <label class="fw-bold" for="ReportAdvice">แจ้งเตือนไปยังผู้ถูกรายงาน</label>
                                <input type="text" id="ReportAdvice_TB" class="form-control" placeholder="ความคิดเห็น ชี้แจงไปยังผู้ถูกรายงาน">
                            </div>
                            <div class="mt-2">
                                <button type="button" id="ReportAdvice_BTN" class="btn btn-outline-primary" onclick="ReportAdviceSent(${ data['noti_id'] }, '${ data['reported_vehicle_id'] }')">รายงาน</button>
                            </div>
                        </div>
                    `;
                    $("#ReportDetail").modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

        }

        function MarkAsRead() {
            var getReport_id = document.getElementById("isReadButton").value;
            $.ajax({
                url: '../ajax_db.php',
                method: 'POST',
                data : {
                    BackendFunction: 'ReoprtDetail_Modal_MarkAsRead',
                    id: getReport_id
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    Swal.fire({
                        icon: 'success',
                        title: 'ทำเครื่องหมายเป็นอ่านแล้ว',
                        confirmButtonText: 'ตกลง',
                    }).then((result) => {
                        window.location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            })
        }

        function ReportAdviceSent(id, vehicle_id) {
            var ReportAdvice_TB = document.getElementById("ReportAdvice_TB").value;
            if (ReportAdvice_TB == null || ReportAdvice_TB == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'กรุณากรอกข้อมูลรายงานก่อน'
                });
            }
            else {
                Swal.fire({
                    icon: 'warning',
                    title: 'ยืนยันการส่งรายงาน',
                    text: 'เมื่อดำเนินการแล้ว ข้อมูลจะถูกส่งไปยังผู้ถูกรายงาน',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../ajax_db.php',
                            method: 'POST',
                            data : {
                                BackendFunction: 'ReoprtDetail_Modal_Advice',
                                id: id,
                                vehicle_id: vehicle_id,
                                message: ReportAdvice_TB
                            },
                            dataType: 'json',
                            success: function(data) {
                                console.log(data);
                                if (data) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'รายงานข้อมูลไปยังผู้ใช้สำเร็จ',
                                        confirmButtonText: 'ตกลง'
                                    }).then((result) => {
                                        var ReportAdvice_TB = document.getElementById("ReportAdvice_TB");
                                        ReportAdvice_TB.value = "";
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                            }
                        });
                    }
                })
            }
        }
    </script>
    

</body>
</html>