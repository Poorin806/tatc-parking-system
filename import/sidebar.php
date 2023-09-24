<?php

    if ($_SESSION['UserType'] == "Student") {
        $sql = "SELECT student.*, prefix.prefix_name 
                FROM student, prefix 
                WHERE std_id = " . $_SESSION['UserID'] . " AND student.prefix_id = prefix.prefix_id
        ";
    }
    else {
        $sql = "SELECT *
                FROM personnel
                WHERE personnel.personnel_id = " . $_SESSION['UserID'] . "
        ";
    }

    $UserDataSideBar = SQLSelect($con, $sql);
    $Decoded_Data = base64_decode($UserDataSideBar['img'], true);

    // Checking if image file is base64_encode or not
    if ($Decoded_Data !== false) {
        // It's encode to base64
        $UserImageSideBar = $UserDataSideBar['img'];
    }
    else {
        // It's not
        $UserImageSideBar = base64_encode($UserDataSideBar['img']);
    }

?>

<div class="p-4 sidebar">
    <div class="text-center">
        <!-- Profile image -->
        <img src="data:image/jpeg;base64,<?php echo $UserImageSideBar; ?>" width="100px" height="100px" class="rounded-circle border border-dark object-fit-cover">
        <h5 class="mt-3">
            <?php
                if ($_SESSION['UserType'] == "Student") {
                    echo $UserDataSideBar['prefix_name'] . $UserDataSideBar['name'] . " " . $UserDataSideBar['lastname'];
                }
                else {
                    echo $UserDataSideBar['name'] . " " . $UserDataSideBar['lastname'];
                }
            ?>
        </h5>
        <hr>
        <a href="profile.php" class="me-2 text-decoration-none text-primary">แก้ไข</a>
        <a href="#" class="ms-2 text-decoration-none text-danger" onclick="Logout()">ออกจากระบบ</a>

        <ul class="mt-4 sidebar-nav text-start">
            <li class="<?php SidebarHoverChecking("index.php") ?>"><a href="index.php"><i class="bi bi-house-fill"></i>หน้าหลัก</a></li>
            <li class="<?php SidebarHoverChecking("status.php") ?>"><a href="status.php"><i class="bi bi-segmented-nav"></i>ตรวจสอบสถานะ</a></li>
            <li class="<?php SidebarHoverChecking("registration.php") ?>"><a href="registration.php"><i class="bi bi-pencil-square"></i>ลงทะเบียนจอดรถ</a></li>
            <li class="<?php SidebarHoverChecking("notification.php") ?>">
                <a href="notification.php" class="d-flex flex-row align-items-center">
                    <i class="bi bi-bell-fill"></i>การแจ้งเตือน 
                    <?php
                        if ($_SESSION['UserType'] == "Student") {
                            $user_id = $_SESSION['UserID'];
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
                                        (registration.std_id IS NOT NULL AND registration.std_id = '$user_id') AND (notification.isRead = '0')
                            ";
                        }
                        else {
                            $user_id = $_SESSION['UserID'];
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
                                        (registration.personnel_id IS NOT NULL AND registration.personnel_id = '$user_id') AND (notification.isRead = '0')
                            ";
                        }
                        $noti_num = SQLSelectNumRow($con, $sql);
                        if ($noti_num != 0) {
                            ?><p class="text-light fw-bold bg-danger" style="padding: 0 7px; border-radius: 15px; top: 20px; font-size: 16px;"><?php echo $noti_num ?></p><?php
                        }
                    ?>
                </a>
            </li>
            <li class="<?php SidebarHoverChecking("report.php") ?>"><a href="report.php"><i class="bi bi-chat-square-text"></i>รายงานปัญหา</a></li>
            <li class="<?php SidebarHoverChecking("announcement.php") ?>"><a href="announcement.php"><i class="bi bi-megaphone-fill"></i>ประชาสัมพันธ์</a></li>
            <li class="<?php SidebarHoverChecking("manual.php") ?>"><a href="manual.php"><i class="bi bi-journal"></i>คู่มือการใช้งาน</a></li>
        </ul>
    </div>
</div>

<script>
    function Logout() {
        Swal.fire({
            icon: 'success',
            title: 'ออกจากระบบสำเร็จ',
            confirmButtonText: 'ตกลง'
        }).then((result) => {
            window.location='logout.php'
        });
    }
</script>

<?php

    //Active Sidebar checking
    function SidebarHoverChecking($sidebarTitle) {
        $CurrectPage = basename($_SERVER['PHP_SELF']);
        if ($CurrectPage == $sidebarTitle) {
            echo "sidebar-item-active";
        }
        else if (($CurrectPage == "parkingcard.php" && $sidebarTitle == "index.php") || ($CurrectPage == "parkingcard-detail.php" && $sidebarTitle == "index.php") || ($CurrectPage == "profile.php" && $sidebarTitle == "index.php")) {
            echo "sidebar-item-active";
        }
        else if (($CurrectPage == "registration-form.php" && $sidebarTitle == "registration.php") || ($CurrectPage == "registration-form-preview.php" && $sidebarTitle == "registration.php")) {
            echo "sidebar-item-active";
        }
        else if ($CurrectPage == "status-detail.php" && $sidebarTitle == "status.php") {
            echo "sidebar-item-active";
        }
        else if ($CurrectPage == "report-detail.php" && $sidebarTitle == "report.php") {
            echo "sidebar-item-active";
        }
        else {
            echo "sidebar-item";
        }
    }

?>