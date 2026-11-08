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
                        <h1 class="m-0 p-0 fs-5 fw-bold">สติ๊กเกอร์ - บัตรจอดรถ</h1>
                    </div>
                    <div class="col-sm-9">
                        <div class="d-flex flex-column flex-sm-row align-items-center gap-2 justify-content-end">
                            Filter
                            <div class="align-self-start align-self-sm-center d-flex align-items-center gap-2">
                                <!-- Date Sorting -->
                                <select id="Sticker" class="form-select" onchange="Filter()">
                                    <option value="0">- สติ๊กเกอร์ -</option>
                                    <option value="1">ยังไม่ได้ปริ้น</option>
                                    <option value="2">ปริ้นแล้ว</option>
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
                                <input type="text" id="Search" class="form-control" placeholder="ค้นหา (ชื่อ-นามสกุล)" style="width: 250px;">
                                <a href="#" onclick="Filter()" class="btn btn-maintheme">
                                    <i class="bi bi-search"></i>
                                </a>
                                <!-- Clear Filter -->
                                <a href="sticker.php" class="btn btn-danger">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            </div>
                            
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
                                # ลำดับ
                            </th>
                            <th>ชื่อ - นามสกุล</th>
                            <th>ประเภทผู้สมัคร</th>
                            <th>ประเภทยานพาหนะ</th>
                            <th class="text-center">เวลาล่าสุดที่ตรวจสอบ</th>
                            <th class="text-end">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            // Filter (Sticker)
                            if (isset($_GET['Sticker'])) {
                                switch ($_GET['Sticker']) {
                                    case "true" :
                                        $Sticker = "sticker printed";
                                        ?>
                                        <script>document.getElementById("Sticker").value = "2"</script>
                                        <?php
                                        break;
                                    case "false" :
                                        $Sticker = "prepare to print sticker";
                                        ?>
                                        <script>document.getElementById("Sticker").value = "1"</script>
                                        <?php
                                        break;
                                    default :
                                        $Sticker = "prepare to print sticker";
                                        ?>
                                        <script>document.getElementById("Sticker").value = "0"</script>
                                        <?php
                                        break;
                                }
                            }
                            else {
                                $Sticker = "prepare to print sticker";
                                ?>
                                <script>document.getElementById("Sticker").value = "0"</script>
                                <?php
                            }

                            // Limit (Rows)
                            if ((isset($_GET['Record'])) && ($_GET['Record'] != null) && ($_GET['Record'] != "1")) {
                                $Limit = $_GET['Record'];
                            }
                            else {
                                $Limit = 9999999;
                            }

                            ?>
                            <!-- Filter Checking -->
                            <script>
                                var Filter = new URLSearchParams(window.location.search);
                                var Filter_Search = Filter.get('Search');
                                var Filter_Record = Filter.get('Record');

                                var Record = document.getElementById('Record');
                                var Search = document.getElementById('Search');

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
                            <?php

                            if ((isset($_GET['Search'])) && ($_GET['Search'] != null)) {
                                $Search = $_GET['Search'];
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
                                            END AS lastname,
                                            
                                            CASE 
                                                WHEN registration.std_id IS NOT NULL THEN personnel.name
                                                ELSE personnel.lastname
                                            END AS personnel_name,
                                            
                                            vehicle_type.vehiType_name
                                                                                    
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
                                            registration_status_detail.regisStatus_id = 4 AND
                                            registration_status_detail.regisStatus_id NOT IN (5, 6, 7, 8, 9) AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING (MAX(regisStatus_id) = 4) or (MAX(regisStatus_id) = 5)
                                                                                    ))
                                        GROUP BY
                                            registration_status_detail.regis_id
                                        HAVING
                                            name LIKE '$Search' OR lastname LIKE '%$Search%'
                                        LIMIT $Limit
                                ";
                            }
                            else {
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
                                            
                                            vehicle_type.vehiType_name
                                            
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
                                            registration_status_detail.regisStatus_id = 4 AND
                                            registration_status_detail.regisStatus_id NOT IN (5, 6, 7, 8, 9) AND
                                            registration_status_detail.comment = '$Sticker' AND
                                            (registration_status_detail.regis_id IN (SELECT regis_id
                                                                                    FROM registration_status_detail
                                                                                    GROUP BY regis_id
                                                                                    HAVING (MAX(regisStatus_id) = 4) or (MAX(regisStatus_id) = 5)
                                                                                    )
                                            )
                                        LIMIT $Limit
                                ";
                            }

                            $total_rows = SQLSelectNumRow($con, $sql);

                            $total_regis_id_arr = array();

                            if ($total_rows > 0) {                                
                                foreach (SQLSelectLoop($con, $sql) as $data) {
                                    array_push($total_regis_id_arr, $data['regis_id']);
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" id="<?php echo $data['regis_id'] ?>" class="form-check-input" onclick="SingleCheck(<?php echo $data['regis_id'] ?>)">
                                            <?php echo $data['regis_id'] ?>
                                        </td>
                                        <td><?php echo $data['prefix'] . $data['name'] . " " . $data['lastname'] ?></td>
                                        <td>
                                            <?php
                                                if ($data['prefix'] != null) {
                                                    echo "นักศึกษา";
                                                }
                                                else {
                                                    echo "บุคลากร";
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $data['vehiType_name'] ?></td>
                                        <td>
                                            <?php 
                                                echo formatDateThai($data['rsd_date']) . " (" . $data['rsd_time'] . " น.)";
                                            ?>
                                        </td>
                                        <td class="text-end">
                                            <form action="sticker-print.php" method="POST">
                                                <input type="text" name="PrintID" value="<?php echo $data['regis_id'] ?>" hidden>
                                                <input type="submit" value="ปริ้นสติ๊กเกอร์" class="btn btn-maintheme" name="PrintSingle">
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else {
                                ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="alert alert-warning m-0" role="alert">
                                            ไม่มีข้อมูล
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>

                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <i class="bi bi-info-circle-fill flex-shrink-0 me-2"></i>
                    <div>
                        สามารถเลือกคำขอที่จะนำไปปริ้นสติ๊กเกอร์ได้สูงสุด 6 คำขอ ต่อ 1 การปริ้น
                    </div>
                </div>
                
                <div id="SelectPrint" class="mt-1 mb-3" style="display: none;">
                    <a href="#" class="btn btn-primary" onclick="MultiPrint()">ปริ้นสติ๊กเกอร์จากข้อมูลที่เลือกทั้งหมด</a>
                </div>
            </div>

        </div>

        <!-- Totoal Summarize -->
        <div class="container fixed-bottom mb-5">
            <p class="bg-primary text-light p-2 m-0 rounded fw-bolder fs-6 position-absolute end-0 bottom-50" style="width: fit-content;">
                คำขอลงทะเบียนฯ ทั้งหมด <?php echo count($total_regis_id_arr); ?> คำขอ
            </p>
        </div>

    </div>

    <script>

        var total_regis_id_arr = <?php echo json_encode($total_regis_id_arr) ?>;
        var checkbox_allcheck = document.getElementById("AllCheck");
        var max_checked = 6;
        var now_checked = 0;

        function SelectPrint() {
            var SelectPrint = document.getElementById("SelectPrint");

            if (now_checked > 0) {
                SelectPrint.style.display = "block";
                checkbox_allcheck.checked = true;
            }
            else {
                SelectPrint.style.display = "none";
                checkbox_allcheck.checked = false;
            }
        }

        function Filter() {
            var Sticker = document.getElementById('Sticker').value;
            var Record = document.getElementById('Record').value;
            var Search = document.getElementById('Search').value;

            var SortURL = "";
            var RecordURL = "";

            switch (Sticker) {
                case '0' :
                    Sticker = "";
                    break;
                case '1' :
                    Sticker = "false";
                    break;
                case '2' :
                    Sticker = "true";
                    break;
            }

            if (Record == "0") {
                RecordURL = "";
            }
            else {
                RecordURL = Record;
            }

            window.location = 'sticker.php?Search=' + Search + '&Sticker=' + Sticker + '&Record=' + RecordURL;
        }

        function All_Check() {

            // All checkbox is checked
            if (checkbox_allcheck.checked) {
                
                for (var i = 0; i < total_regis_id_arr.length; i++) {
                    if (now_checked < max_checked) {
                        var temp_checkbox = document.getElementById(total_regis_id_arr[i]);
                        temp_checkbox.checked = true;
                        now_checked++;
                    }
                    else {
                        break;
                    }
                }

            }
            else {

                for (var i = 0; i < total_regis_id_arr.length; i++) {
                    var temp_checkbox = document.getElementById(total_regis_id_arr[i]);
                    temp_checkbox.checked = false;
                }

                now_checked = 0;

            }

            SelectPrint();
        }

        function SingleCheck(id) {

            var temp_checkbox = document.getElementById(id);
            
            if (temp_checkbox.checked) {
                if (now_checked < max_checked) {
                    now_checked++;
                    console.log("Now checked = " + now_checked + " (Checkbox " + id + " is checked)");
                }
                else {
                    temp_checkbox.checked = false;
                    console.log("Error");
                }
            }
            else {
                if (now_checked != 0) {
                    now_checked--;
                    console.log("Now checked = " + now_checked + " (Checkbox " + id + " is unchecked)");
                }
            }

            SelectPrint();
        }

        function MultiPrint() {

            var total_print = [];

            for (var i = 0; i < total_regis_id_arr.length; i++) {
                var temp_checkbox = document.getElementById(total_regis_id_arr[i]);
                if (temp_checkbox.checked) {
                    total_print.push(total_regis_id_arr[i]);
                }
            }

            window.location = "sticker-print.php?MultiPrint=" + total_print;
        }

    </script>
    

</body>
</html>