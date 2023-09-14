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

            <div class="mt-4 my-card">
                <form action="#" method="POST">
                    <table class="table table-stripped table-hover table-hover align-middle">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="form-check-input" name="CheckAll" id="CheckAll_Checkbox" onchange="CheckAll_Func()">
                                    #
                                </th>
                                <th>รหัสผู้รายงาน</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>ประเภทผู้รายงาน</th>
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
                                            
                                            notification.vehicle_id,
                                            notification.notiType_id,
                                            notification_type.details,
                                            notification.notiDate,
                                            notification.notiTime,
                                            notification.comment
                                            
                                        FROM notification
                                        JOIN notification_type ON notification.notiType_id = notification_type.notiType_id
                                        ORDER BY notification.noti_id DESC
                                ";

                                $total_report = SQLSelectNumRow($con, $sql);

                                if ($total_report > 0) {                 

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

                                    $total_noti_array = array();

                                    foreach (SQLSelectLoop($con, $sql) as $data) {
                                        array_push($total_noti_array, $data['noti_id']);
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input" name="SingleCheck" id="<?php echo $data['noti_id'] ?>" onchange="SingleCheck_Func(<?php echo $data['noti_id'] ?>)">
                                                <?php echo $data['noti_id'] ?>
                                            </td>
                                            <td><?php echo $data['user_id'] ?></td>
                                            <td>
                                                <?php
                                                    if ($data['UserType'] == 'นักศึกษา') {
                                                        echo "นายภูรินทร์ ทัศคร";
                                                    }
                                                    else {
                                                        echo "อาจารย์สายชล สุขนิ่ม";
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo $data['UserType'] ?></td>
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
                                        <td colspan="7">
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
            </div>

        </div>

        <!-- Totoal Summarize -->
        <div class="container fixed-bottom mb-5">
            <p class="bg-primary text-light p-2 m-0 rounded fw-bolder fs-6 position-absolute end-0 bottom-50" style="width: fit-content;">
                ประกาศทั้งหมด <?php echo $total_report; ?> ครั้ง
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
                    <button type="button" class="btn btn-maintheme" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>

        var total_check = 0;
        var total_check_arr = <?php echo json_encode($total_noti_array) ?>;
        var AllRead = document.getElementById("AllRead");
        var CheckAll_Checkbox = document.getElementById("CheckAll_Checkbox");
        var total_Checkbox = <?php echo $recordsPerPage ?>;

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
            console.log(All_Check_arr);
        }

        function ReportDetail(id) {

            var ReportDetail_Title = document.getElementById("ReportDetail_Title");
            var ReportDetail_Body = document.getElementById("ReportDetail_Body");

            $.ajax({
                url: '../ajax_db.php',
                method: 'POST',
                data: { 
                    BackendFunction: 'User_ReoprtDetail_Modal',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    ReportDetail_Title.innerHTML = `
                        <b>#${ data['noti_id'] } ถูกรายงาน - ${ data['details'] }</b>
                    `;
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
                                        `-`
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
                    `;
                    $("#ReportDetail").modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

            }
    </script>
    

</body>
</html>