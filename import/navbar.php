<?php

  if (isset($_SESSION['UserID'])) {
    if ($_SESSION['UserType'] == "Student") {
      $sql = "SELECT * 
      FROM student, prefix 
      WHERE student.prefix_id = prefix.prefix_id AND 
      student.std_id = '" . $_SESSION['UserID'] . "'";
    }
    else {
      $sql = "SELECT * 
      FROM personnel
      WHERE personnel.personnel_id = '" . $_SESSION['UserID'] . "'";
    }

    $UserData = SQLSelect($con, $sql);
    $Decoded_Data = base64_decode($UserData['img'], true);

    // Checking if image file is base64_encode or not
    if ($Decoded_Data !== false) {
        // It's encode to base64
        $UserImage = $UserData['img'];
    }
    else {
        // It's not
        $UserImage = base64_encode($UserData['img']);
    }
  }

?>

<nav class="my-navbar navbar navbar-expand-lg fixed-top" style="z-index: 99;">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <div class="d-flex justify-items-center align-items-center">
        <img src="image/TATC_Logo_White_Text.png" width="50px" height="50px" class="img-fluid">
        <h4 style="font-family: 'Nunito', sans-serif; color: #feffff; margin: 0;">TATC PARKING</h4>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list text-white fs-1 fw-bold"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php
          //Logged in checking
          if (!isset($_SESSION['UserID'])) {
            ?>
              <li class="nav-item">
                <a class="nav-link" href="login.php" style="color: #feffff;">เข้าสู่ระบบ</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="register.php" style="color: #feffff;">สมัครสมาชิก</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="backend/" style="color: #feffff;">เข้าสู่ระบบ (สำหรับผู้ดูแล)</a>
              </li>
            <?php
          }
          else {
            ?>
              <!-- Profile -->
              <li class="nav-item nav-item-hide my-3">
                <div class="d-flex align-items-center column-gap-3">
                  <div>
                    <img src="data:image/jpeg;base64,<?php echo $UserImage ?>" class="img-fluid rounded" width="50">
                  </div>
                  <div class="nav-link d-flex flex-column align-items-start row-gap-2">
                    <div class="fw-bolder fs-5 text-light">
                      <?php echo $UserData['prefix_name'] . $UserData['name'] . " " . $UserData['lastname'] ?>
                    </div>
                    <div>
                      <a href="profile.php" class="text-decoration-none fs-6 fw-bold" style="color: #3B91FF;">แก้ไขข้อมูลส่วนตัว</a>
                    </div>
                  </div>
                </div>
              </li>
              <li class="nav-item nav-item-hide">
                <a href="index.php" class="nav-link" style="color: #feffff;">หน้าหลัก</a>
              </li>
              <li class="nav-item nav-item-hide">
                <a href="status.php" class="nav-link" style="color: #feffff;">ตรวจสอบสถานะ</a>
              </li>
              <li class="nav-item nav-item-hide">
                <a href="registration.php" class="nav-link" style="color: #feffff;">ลงทะเบียนจอดรถ</a>
              </li>
              <li class="nav-item nav-item-hide">
                <a href="notification.php" class="nav-link" style="color: #feffff;">
                    การแจ้งเตือน
                    <?php

                        $user_id = $_SESSION['UserID'];

                        if ($_SESSION['UserType'] == "Student") {
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
                            ?><p class="px-2 py-1 m-0 bg-danger d-inline-flex rounded-circle"><?php echo $noti_num ?></p><?php
                        }
                    ?>
                </a>
              </li>
              <li class="nav-item nav-item-hide">
                <a href="report.php" class="nav-link" style="color: #feffff;">รายงานปัญหา</a>
              </li>
              <li class="nav-item nav-item-hide">
                <a href="announcement.php" class="nav-link" style="color: #feffff;">ประชาสัมพันธ์</a>
              </li>
              <li class="nav-item nav-item-hide">
                <a href="user_manual_users.pdf" target="_blank" class="nav-link" style="color: #feffff;">คู่มือการใช้งาน</a>
              </li>
              <li class="nav-item nav-item-hide mb-3">
                <a href="about.php" class="nav-link" style="color: #feffff;">เกี่ยวกับ</a>
              </li>

              <!-- Menu Function -->
              <li class="nav-item nav-item-hide">
                <a href="#" onclick="Logout()" class="btn btn-danger" style="color: #feffff;">ออกจากระบบ</a>
              </li>
            <?php
          }
        ?>
      </ul>
    </div>
  </div>
</nav>

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

<?php $UserData = array(); ?>