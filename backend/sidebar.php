<?php

    $sql = "SELECT * FROM admin WHERE admin_id = '" . $_SESSION['AdminID'] . "'";

    $UserDataSideBar = SQLSelect($con, $sql);
    // $UserImageSideBar = base64_encode($UserDataSideBar['img']);
    $UserImageSideBar = $UserDataSideBar['img'];

?>

<div class="p-4 sidebar">
    <div class="text-center">
        <!-- Profile image -->
        <img src="data:image/jpeg;base64,<?php echo $UserImageSideBar; ?>" width="100px" height="100px" class="rounded-circle border border-dark object-fit-cover">
        <h5 class="mt-3"><?php echo $UserDataSideBar['name'] . " " . $UserDataSideBar['lastname'] ?></h5>
        <hr>
        <a href="admin.php" class="me-2 text-decoration-none text-primary">แก้ไข</a>
        <a href="#" class="text-decoration-none text-danger" onclick="Logout()">ออกจากระบบ</a>

        <ul class="mt-4 sidebar-nav text-start">
            <li class="<?php SidebarHoverChecking("index.php") ?>"><a href="index.php"><i class="bi bi-house-fill"></i>ภาพรวม</a></li>
            <li class="<?php SidebarHoverChecking("registration-request.php") ?>"><a href="registration-request.php"><i class="bi bi-file-earmark-richtext-fill"></i>คำขอลงทะเบียน</a></li>
            <li class="<?php SidebarHoverChecking("registration-checking.php") ?>"><a href="registration-checking.php"><i class="bi bi-car-front-fill"></i>ตรวจสภาพรถ</a></li>
            <li class="<?php SidebarHoverChecking("sticker.php") ?>"><a href="sticker.php"><i class="bi bi-person-vcard-fill"></i>สติ๊กเกอร์</a></li>
            <li class="<?php SidebarHoverChecking("report.php") ?>"><a href="report.php"><i class="bi bi-chat-square-text"></i>การรายงานปัญหา</a></li>
            <li class="<?php SidebarHoverChecking("announcement.php") ?>"><a href="announcement.php"><i class="bi bi-megaphone-fill"></i>การประชาสัมพันธ์</a></li>
            <li class="<?php SidebarHoverChecking("settings.php") ?>"><a href="settings.php"><i class="bi bi-gear-fill"></i>ตั้งค่าระบบ</a></li>
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
        else if (($CurrectPage == "admin.php" && $sidebarTitle == "index.php") || ($CurrectPage == "admin-add.php" && $sidebarTitle == "index.php")) {
            echo "sidebar-item-active";
        }
        else if ($CurrectPage == "announcement-add.php" && $sidebarTitle == "announcement.php") {
            echo "sidebar-item-active";
        }
        else if ($CurrectPage == "sticker-print.php" && $sidebarTitle == "sticker.php") {
            echo "sidebar-item-active";
        }
        else if ($CurrectPage == "report.php" && $sidebarTitle == "report.php") {
            echo "sidebar-item-active";
        }
        else {
            echo "sidebar-item";
        }
    }

?>