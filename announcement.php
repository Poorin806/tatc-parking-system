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

    <!-- Homepage css -->
    <link rel="stylesheet" href="css/homepage.css">

    <!-- Essentials JS -->
    <?php include_once 'import/js.php'; ?>
    
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
                        <p class="m-0 p-0 fs-5 fw-bold">ประชาสัมพันธ์ - ข่าวสาร</p>
                    </div>
                    <div class="">
                        <div class="d-flex flex-column justify-content-start gap-3 flex-sm-row">
                            <!-- Sort -->
                            <select id="Sort" class="form-select" onchange="Filter()">
                                <option value="0">- เรียงลำดับ -</option>
                                <option value="1">ใหม่ที่สุด</option>
                                <option value="2">เก่าที่สุด</option>
                            </select>
                            <!-- Litmit record -->
                            <select id="Record" class="form-select" onchange="Filter()">
                                <option value="0">- จำนวนแถว -</option>
                                <option value="10">10 แถว</option>
                                <option value="50">50 แถว</option>
                                <option value="100">100 แถว</option>
                                <option value="1">ทั้งหมด</option>
                            </select>
                            <!-- Search -->
                            <input type="text" id="Search" class="form-control" placeholder="ค้นหาชื่อเรื่อง">
                            <!-- Button -->
                            <div class="d-flex align-items-center justify-content-between column-gap-3">
                                <a href="#" onclick="Filter()" class="btn btn-maintheme" style="width: fit-content;">
                                    <i class="bi bi-search"></i>
                                </a>
                                <a href="announcement.php" class="btn btn-danger text-light" style="width: fit-content;">
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
                            var Filter_Search = Filter.get('Search');
                            var Filter_Sort = Filter.get('Sort');
                            var Filter_Record = Filter.get('Record');

                            var Sort = document.getElementById('Sort');
                            var Record = document.getElementById('Record');
                            var Search = document.getElementById('Search');

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

                    </div>
                </div>
            </div>

            <div class="mt-4 my-card p-4" id="Content">
                <?php

                    //Sorting
                    if ((isset($_GET['Sort'])) && ($_GET['Sort'] != null)) {
                        $Sort = $_GET['Sort'];
                    }
                    else {
                        $Sort = "DESC";
                    }

                    //Limit
                    if ((isset($_GET['Record'])) && ($_GET['Record'] != null)) {
                        $Limit = $_GET['Record'];
                    }
                    else {
                        $Limit = 9999999;
                    }

                    //Search
                    if ((isset($_GET['Search'])) && ($_GET['Search'] != null)) {
                        $Search = $_GET['Search'];
                        $sql = "SELECT * FROM announcement WHERE title LIKE '%$Search%' ORDER BY an_id $Sort LIMIT $Limit";
                    }
                    else {
                        $sql = "SELECT * FROM announcement ORDER BY an_id $Sort LIMIT $Limit";
                    }

                    $announcement_row = SQLSelectNumRow($con, $sql);
                    if ($announcement_row > 0) {
                        foreach (SQLSelectLoop($con, $sql) as $data) {
                            ?>
                            <div class="card mb-3">
                                <!-- Card Header -->
                                <div class="card-header">
                                    <div class="d-flex flex-column row-gap-3 flex-sm-row align-items-start align-items-sm-center justify-content-between">
                                        <!-- Left Side (Title) -->
                                        <div>
                                            <p class="m-0 p-0 fs-6">
                                                <span class="fw-bold m-0 p-0"><?php echo formatDateTimeThai($data['an_date']) ?> : </span>
                                                <?php echo $data['title'] ?>
                                            </p>
                                        </div>
                                        <!-- Right Side (Detail BTN) -->
                                        <div>
                                            <?php
                                                if ($data['content'] != null) {
                                                    ?>
                                                    <button class="btn btn-maintheme" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_CardBody_<?php echo $data['an_id'] ?>" aria-expanded="false" aria-controls="collapse_CardBody_<?php echo $data['an_id'] ?>">
                                                        รายละเอียด
                                                    </button>
                                                    <?php
                                                }
                                                if ($data['filename'] != null) {
                                                    ?>
                                                    <a href="file_downloading.php?DownloadID=<?php echo $data['an_id'] ?>" class="m-0 btn btn-maintheme">ดาวน์โหลดเอกสาร</a>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
    
                                <!-- Card Body (Content) -->
                                <?php
                                    if ($data['content'] != null) {
                                        ?>
                                        <div class="collapse" id="collapse_CardBody_<?php echo $data['an_id'] ?>">
                                            <div class="card-body">
                                                <pre class="m-0 p-0 fs-6" style="font-family: 'Noto Sans Thai', Arial, Helvetica, sans-serif;"><?php echo $data['content'] ?></pre>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                            <?php
                        }
                    }
                    else {
                        ?>
                        <div class="alert alert-warning m-0" role="alert">
                            ไม่มีข้อมูลการประกาศ
                        </div>
                        <?php
                    }

                ?>
            </div>
        </div>

    </div>

    <!-- Essentials JavaScript -->
    <script>
        function Filter() {
            var Sort = document.getElementById('Sort').value;
            var Record = document.getElementById('Record').value;
            var Search = document.getElementById('Search').value;

            var SortURL = "";
            var RecordURL = "";

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

            if (Record == "0") {
                RecordURL = "";
            }
            else {
                RecordURL = Record;
            }

            window.location = 'announcement.php?Search=' + Search + '&Sort=' + SortURL + '&Record=' + RecordURL;
        }

    </script>

</body>
</html>