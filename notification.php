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
            <div class="my-card">
                <p class="fs-5 fw-bold">การแจ้งเตือน</p>
            </div>

            <!-- Notifications -->
            <?php

                if ($UserType == "Student") {
                    $sql = "SELECT notification.*, notification_type.details
                    FROM 
                        notification, notification_type
                    WHERE 
                        (std_id = '$UserID') AND
                        (notification.notiType_id = notification_type.notiType_id)
                    ";
                }
                else {
                    $sql = "SELECT notification.*, notification_type.details
                    FROM 
                        notification, notification_type
                    WHERE 
                        (personnel_id = '$UserID') AND
                        (notification.notiType_id = notification_type.notiType_id)
                    ";
                }

                if (SQLSelectNumRow($con, $sql) != 0) {
                    foreach (SQLSelectLoop($con, $sql) as $data) {
                        ?>
                        <div class="mt-4 my-card">
                            <div class="d-flex align-items-center">
                                <div class="ms-2 me-4">
                                    <img src="image/logo.png" width="75" class="img-fluid">
                                </div>
                                <div class="w-100">
                                    <h5 class="fw-bold" style="margin: 0; padding: 0;">
                                        <?php
                                            switch ($data['notiType_id']) {
                                                case 1:
                                                case 2:
                                                case 3:
                                                case 4:
                                                    echo "รายงานปัญหา - " . $data['details'];
                                                    break;
                                            }
                                        ?>
                                    </h5>
                                    <p style="maring: 0; padding: 0;">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                                    <div class="row align-items-center">
                                        <div class="col-6 text-start">
                                            <a href="#" class="btn" style="background-color: #242729; color: #feffff;">รายละเอียด</a>
                                        </div>
                                        <div class="col-6 text-end"><?php echo formatDateThai($data['notiDate']) . " เวลา " . $data['notiTime'] . " น." ?></div>
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
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <?php
                }
                else {
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

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

</body>
</html>