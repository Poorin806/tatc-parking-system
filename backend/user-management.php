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
                        <h1 class="m-0 p-0 fs-5 fw-bold">จัดการผู้ใช้</h1>
                    </div>
                    <div class="col-sm-9 text-end">
                        <a href="index.php" class="btn btn-maintheme">ย้อนกลับ</a>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="mt-4 my-card">
                <div class="d-flex align-items-center column-gap-2">
                    <div>Filter</div>
                    <!-- User Type -->
                    <div>
                        <select id="UserType" class="form-select" onchange="Filter()">
                            <option value="Default">- ประเภทผู้ใช้ -</option>
                            <option value="Student">นักเรียน</option>
                            <option value="Personnel">บุคลากร</option>
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
                            <option value="All">ทั้งหมด</option>
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
                        <a href="user-management.php" class="btn btn-danger">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    </div>
                </div>

                <!-- JS Filter -->
                <script>
                    var Filter = new URLSearchParams(window.location.search);
                    var Filter_UserType = Filter.get('UserType');
                    var Filter_Record = Filter.get('Record');

                    var UserType = document.getElementById('UserType');
                    var Record = document.getElementById('Record');

                    // Fiter UserType
                    switch (Filter_UserType) {
                        case 'Student':
                            UserType.value = 'Student';
                            break;
                        case 'Personnel':
                            UserType.value = 'Personnel';
                            break;
                        default:
                            UserType.value = 'Default';
                            break;
                    }

                    // Filter Record
                    if (Filter_Record == "All") {
                        Record.value = "All";
                    }
                    else if ((Filter_Record == "0") || (Filter_Record == null) || (Filter_Record == "")) {
                        Record.value = "0";
                    }
                    else {
                        Record.value = Filter_Record;
                    }
                </script>

                <!-- PHP Filter -->
                <?php
                    // UserType Filter
                    if (isset($_GET['UserType'])) {
                        $UserType = $_GET['UserType'];
                    }
                    else {
                        $UserType = "Default";
                    }

                    // Search Filter
                    if (isset($_GET['Search'])) {
                        $Search = $_GET['Search'];
                        if ($UserType == "Student" || $UserType == "Default") {
                            $Search_SQL = "AND (student.std_id LIKE '%$Search%' OR student.name LIKE '%$Search%' OR student.lastname LIKE '%$Search%' OR department.dept_name LIKE '%$Search%')";
                        }
                        else {
                            $Search_SQL = "AND (personnel.personnel_id LIKE '%$Search%' OR personnel.name LIKE '%$Search%' OR personnel.lastname LIKE '%$Search%')";
                        }
                    }
                    else {
                        $Search = "";
                        $Search_SQL = "";
                    }

                    //Limit
                    if ((isset($_GET['Record'])) && ($_GET['Record'] != null)) {
                        if ($_GET['Record'] == "All") {
                            $Limit_SQL = 9999999;
                        }
                        else {
                            $Limit_SQL = $_GET['Record'];
                        }
                    }
                    else {
                        $Limit_SQL = 9999999;
                    }
                ?>
            </div>

            <div class="mt-4 my-card">
                <table class="table table-stripped table-hover table-hover align-middle">
                    <thead>
                        <?php
                            if ($UserType == "Student" || $UserType == "Default") {
                                ?>
                                <!-- Student -->
                                <tr>
                                    <th>รหัสนักศึกษา</th>
                                    <th>ชื่อ - นามสกุล</th>
                                    <th>แผนกวิชา (ระดับชั้น)</th>
                                    <th class="text-end">จัดการ</th>
                                </tr>
                                <?php
                            }
                            else {
                                ?>
                                <!-- Personnel -->
                                <tr>
                                    <th>รหัสบุคลากร</th>
                                    <th>ชื่อ - นามสกุล</th>
                                    <th class="text-end">จัดการ</th>
                                </tr>
                                <?php
                            }
                        ?>
                    </thead>
                    <tbody>
                        <?php
                            $sql_student = "SELECT 
                                        student.*,
                                        prefix.prefix_name,
                                        department.dept_name,
                                        class_level.level_name
                                    FROM student
                                    JOIN prefix ON student.prefix_id = prefix.prefix_id
                                    LEFT JOIN department ON student.dept_id = department.dept_id
                                    LEFT JOIN class_level ON student.level_id = class_level.level_id
                                    WHERE
                                        (student.std_id IS NOT NULL)
                                        $Search_SQL
                            ";

                            $sql_personnel = "SELECT * 
                                                FROM personnel
                                                WHERE
                                                    (personnel.personnel_id IS NOT NULL)
                                                    $Search_SQL
                            ";

                            if ($UserType == "Student" || $UserType == "Default") {

                                // Calculate total number of pages
                                $totalRecords = SQLSelectNumRow($con, $sql_student);
                                $recordsPerPage = $Limit_SQL; // Set the number of records per page here
                                $totalPages = ceil($totalRecords / $recordsPerPage);
                            
                                // Calculate current page from the URL parameter or default to 1
                                $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                            
                                // Calculate the offset based on the current page
                                $offset = ($current_page - 1) * $recordsPerPage;
                            
                                // Add LIMIT and OFFSET to your SQL query
                                $sql_student .= " LIMIT $recordsPerPage OFFSET $offset";
                                
                                // Student Table
                                $totalUser = SQLSelectNumRow($con, $sql_student);
                                foreach(SQLSelectLoop($con, $sql_student) as $data) {
                                    ?>
                                    <tr>
                                        <td><?php echo $data['std_id'] ?></td>
                                        <td><?php echo $data['prefix_name'] . $data['name'] . " " . $data['lastname'] ?></td>
                                        <td><?php echo $data['dept_name'] . " (" . $data['level_name'] . ")" ?></td>
                                        <td class="text-end">
                                            <a href="user-management-detail.php?UserID=<?php echo $data['std_id'] ?>&UserType=Student" class="btn btn-maintheme">รายละเอียด</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else {

                                // Calculate total number of pages
                                $totalRecords = SQLSelectNumRow($con, $sql_personnel);
                                $recordsPerPage = $Limit_SQL; // Set the number of records per page here
                                $totalPages = ceil($totalRecords / $recordsPerPage);
                            
                                // Calculate current page from the URL parameter or default to 1
                                $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                            
                                // Calculate the offset based on the current page
                                $offset = ($current_page - 1) * $recordsPerPage;
                            
                                // Add LIMIT and OFFSET to your SQL query
                                $sql_personnel .= " LIMIT $recordsPerPage OFFSET $offset";

                                // Personnel Table
                                $totalUser = SQLSelectNumRow($con, $sql_personnel);
                                foreach(SQLSelectLoop($con, $sql_personnel) as $data) {
                                    ?>
                                    <tr>
                                        <td><?php echo $data['personnel_id'] ?></td>
                                        <td><?php echo $data['name'] . " " . $data['lastname'] ?></td>
                                        <td class="text-end">
                                            <a href="user-management-detail.php?UserID=<?php echo $data['personnel_id'] ?>&UserType=Personnel" class="btn btn-maintheme">จัดการ</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php
                if ($totalUser > 0) {
                    ?>
                    <div class="mt-4 d-flex justify-content-center">
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

                                $pagination_link = "Search=" . $Search . "&UserType=" . $UserType . "&Record=" . $Limit_SQL;

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

    <script>
        function Filter() {
            var UserType = document.getElementById("UserType").value;
            var Search = document.getElementById("Search").value;
            var Record = document.getElementById('Record').value;

            var UserTypeURL = "";
            var RecordURL = "";

            switch (UserType) {
                case 'Student':
                    UserTypeURL = "Student";
                    break;
                case 'Personnel':
                    UserTypeURL = "Personnel";
                    break;
                default:
                    UserTypeURL = "Default";
                    break;
            }

            if (Record == "0") {
                RecordURL = "";
            }
            else {
                RecordURL = Record;
            }

            window.location = 'user-management.php?Search=' + Search + '&UserType=' + UserTypeURL + '&Record=' + RecordURL;
        }
    </script>
    

</body>
</html>