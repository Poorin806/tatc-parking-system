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
                                    <input type="checkbox" class="form-check-input" name="CheckAll" id="CheckAll" oninput="CheckAll()">
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
                                            notification.notiDate,
                                            notification.notiTime,
                                            notification.comment
                                            
                                        FROM notification;
                                ";

                                $total_report = SQLSelectNumRow($con, $sql);

                                if ($total_report > 0) {                 

                                    $total_noti_array = array();

                                    foreach (SQLSelectLoop($con, $sql) as $data) {
                                        array_push($total_noti_array, $data['noti_id']);
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input" name="SingleCheck" id="SingleCheck" onchange="SingleCheck_Func(5)">
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
                                            <td><?php echo $data['notiType_id'] ?></td>
                                            <td><?php echo formatdatethai($data['notiDate']) . " (" . $data['notiTime'] . "น.)" ?></td>
                                            <td class="text-end">
                                                <button class="btn btn-maintheme">รายละเอียด</button>
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
                        <button class="btn btn-outline-primary">ทำเครื่องหมายทั้งหมดเป็นอ่านแล้ว</button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Totoal Summarize -->
        <div class="container fixed-bottom mb-5">
            <p class="bg-primary text-light p-2 m-0 rounded fw-bolder fs-6 position-absolute end-0 bottom-50" style="width: fit-content;">
                ประกาศทั้งหมด <?php echo $total_report; ?> ครั้ง
            </p>
        </div>

    </div>

    <!-- JS -->
    <script>

        var total_check = 0;
        var AllRead = document.getElementById("AllRead");

        function CheckboxChecking() {
            if (total_check > 0) {
                AllRead.style.display = "block";
            }
            else {
                AllRead.style.display = "none";
            }
        }

        function SingleCheck_Func(id) {
            var checkbox = document.getElementById('SingleCheck');

            if (checkbox.checked) {
                // alert(id + " = checked");
                total_check++;
            }
            else {
                // alert(id + " = unchecked");
                total_check--;
            }

            CheckboxChecking();
        }
    </script>
    

</body>
</html>