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

            //If Deleted
            if (isset($_GET['DeleteID'])) {
                $DeleteID = $_GET['DeleteID'];
                $sql = "DELETE FROM announcement WHERE an_id = $DeleteID";
                SQLQuery($con, $sql);

                echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบข้อมูลประกาศสำเร็จ',
                                confirmButtonText: 'ตกลง'
                            }).then(result =>{
                                window.location = 'announcement.php';
                            })
                        </script>
                ";
            }
            if (isset($_GET['Multi-DeleteID'])) {
                $DeleteID = $_GET['Multi-DeleteID'];
                $sql = "DELETE FROM announcement WHERE an_id in ($DeleteID)";
                SQLQuery($con, $sql);

                echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบข้อมูลประกาศสำเร็จ',
                                confirmButtonText: 'ตกลง'
                            }).then(result =>{
                                window.location = 'announcement.php';
                            })
                        </script>
                ";
            }
        ?>

        <div class="content">
            
            <!-- Title -->
            <div class="my-card">
                <div class="row align-items-center justify-content-between row-gap-3">
                    <div class="col-sm-3">
                        <h1 class="m-0 p-0 fs-5 fw-bold">การประชาสัมพันธ์</h1>
                    </div>
                    <div class="col-sm-9">
                        <div class="d-flex flex-column flex-sm-row align-items-center gap-2 justify-content-end">
                            Filter
                            <div class="align-self-start align-self-sm-center d-flex align-items-center gap-2">
                                <!-- Date Sorting -->
                                <select id="Sort" class="form-select" onchange="Filter()">
                                    <option value="0">- เรียงลำดับ -</option>
                                    <option value="1">ใหม่ที่สุด</option>
                                    <option value="2">เก่าที่สุด</option>
                                </select>
                                <!-- Row Record -->
                                <select id="Record" class="form-select" onchange="Filter()">
                                    <option value="0">- จำนวนแถว -</option>
                                    <option value="10">10 แถว</option>
                                    <option value="50">50 แถว</option>
                                    <option value="100">100 แถว</option>
                                    <option value="1">ทั้งหมด</option>
                                </select>
                                <!-- Search -->
                                <input type="text" id="Search" class="form-control" placeholder="ค้นหาชื่อเรื่อง">
                                <a href="#" onclick="Filter()" class="btn btn-maintheme">
                                    <i class="bi bi-search"></i>
                                </a>
                                <!-- Clear Filter -->
                                <a href="announcement.php" class="btn btn-danger">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
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
            </div>

            <div class="mt-4 my-card">
                <table class="table table-stripped table-hover table-hover align-middle">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="AllCheck" class="form-check-input" onclick="All_Check()">
                            </th>
                            <th># ลำดับ</th>
                            <th>เรื่อง</th>
                            <th>วันที่ประกาศ</th>
                            <th class="text-center">รายละเอียด</th>
                            <th class="text-center">แนบไฟล์</th>
                            <th class="text-end">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            
                            //Sorting
                            if ((isset($_GET['Sort'])) && ($_GET['Sort'] != null)) {
                                $Sort = $_GET['Sort'];
                            }
                            else {
                                $Sort = "DESC";
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
                                $sql = "SELECT * FROM announcement WHERE title LIKE '%$Search%' ORDER BY an_id $Sort LIMIT $Limit";
                            }
                            else {
                                $sql = "SELECT * FROM announcement ORDER BY an_id $Sort LIMIT $Limit";
                            }

                            $total_announcement = SQLSelectNumRow($con, $sql);

                            $announcement_id_arr = array();

                            if ($total_announcement > 0) {                                
                                foreach (SQLSelectLoop($con, $sql) as $data) {
                                    array_push($announcement_id_arr, $data['an_id']);
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" id="<?php echo $data['an_id'] ?>" class="form-check-input" onclick="Single_Check()">
                                        </td>
                                        <td><?php echo $data['an_id'] ?></td>
                                        <td><?php echo $data['title'] ?></td>
                                        <td>
                                            <?php 
                                                echo formatDateTimeThai($data['an_date']); 
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                if ($data['content'] != null) {
                                                    echo "<i class='bi bi-check-circle text-success'></i>";
                                                }
                                                else {
                                                    echo "<i class='bi-x-circle text-danger'></i>";
                                                }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                if ($data['file'] != null) {
                                                    echo "<i class='bi bi-check-circle text-success'></i>";
                                                }
                                                else {
                                                    echo "<i class='bi-x-circle text-danger'></i>";
                                                }
                                            ?>
                                        </td>
                                        <td class="text-end">
                                            <a href="announcement-edit.php?ID=<?php echo $data['an_id'] ?>" class="btn btn-warning text-light">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger text-light" onclick="confirmDelte(<?php echo $data['an_id'] ?>)">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
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
                                            ไม่มีข้อมูลการประกาศ
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
                <div id="SelectDelete" class="mt-1 mb-3" style="display: none;">
                    <a href="#" class="btn btn-danger" onclick="DeleteAllSelected()">ลบข้อมูลที่เลือกทั้งหมด</a>
                </div>
                <div class="text-end">
                    <a href="announcement-add.php" class="btn btn-maintheme">เพิ่มการประกาศ</a>
                </div>
            </div>

        </div>

        <!-- Totoal Summarize -->
        <div class="container fixed-bottom mb-5">
            <p class="bg-primary text-light p-2 m-0 rounded fw-bolder fs-6 position-absolute end-0 bottom-50" style="width: fit-content;">
                ประกาศทั้งหมด <?php echo $total_announcement; ?> ครั้ง
            </p>
        </div>

    </div>

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

        function confirmDelte(id) {
            Swal.fire({
                title: 'การลบข้อมูล',
                text: "ยืนยันการลบประกาศที่ " + id,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = "announcement.php?DeleteID=" + id;
                }
            })
        }

        function All_Check() {

            var AllCheck = document.getElementById('AllCheck');
            var all_checkbox = <?php echo json_encode($announcement_id_arr) ?>;

            if (AllCheck.checked == true) {
                for (var i = 0; i < all_checkbox.length; i++) {
                    var temp_checkbox_id = document.getElementById(all_checkbox[i]);
                    temp_checkbox_id.checked = true;
                    SelectedDelete_On();
                }
            }
            else {
                for (var i = 0; i < all_checkbox.length; i++) {
                    var temp_checkbox_id = document.getElementById(all_checkbox[i]);
                    temp_checkbox_id.checked = false;
                    SelectedDelete_Off();
                }
            }

        }

        function Single_Check() {
            var AllCheck = <?php echo json_encode($announcement_id_arr) ?>;
            var CheckCount = 0;

            for (var i = 0; i < AllCheck.length; i++) {
                var temp_checkbox_id = document.getElementById(AllCheck[i]);
                if (temp_checkbox_id.checked === true) {
                    CheckCount++;
                }
            }

            if (AllCheck.length == CheckCount) {
                document.getElementById('AllCheck').checked = true;
            }
            else {
                document.getElementById('AllCheck').checked = false;
            }

            if (CheckCount > 0) {
                SelectedDelete_On();
            }
            else  {
                SelectedDelete_Off();
            }
        }

        function SelectedDelete_Off() {
            var btn_approve = document.getElementById('SelectDelete');

            if (btn_approve.style.display === 'none') {
                btn_approve.style.display = 'block';
            }
            else {
                btn_approve.style.display = 'none';
            }
        }

        function SelectedDelete_On() {
            var btn_approve = document.getElementById('SelectDelete');

            btn_approve.style.display = 'block';
        }

        function DeleteAllSelected() {
            var AllCheck = <?php echo json_encode($announcement_id_arr) ?>;
            var AllDelete = [];

            for (var i = 0; i < AllCheck.length; i++) {
                var temp_checkbox_id = document.getElementById(AllCheck[i]);
                if (temp_checkbox_id.checked === true) {
                    AllDelete.push(AllCheck[i]);
                }
            }

            Swal.fire({
                title: 'การลบข้อมูล',
                text: "ยืนยันการลบประกาศที่ " + AllDelete,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'announcement.php?Multi-DeleteID=' + AllDelete;
                }
            })
        }
    </script>
    

</body>
</html>