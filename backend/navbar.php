<?php

  if (isset($_SESSION['AdminID'])) {
    $sql = "SELECT * FROM admin WHERE admin_id = '" . $_SESSION['AdminID'] . "'";
    $UserData = SQLSelect($con, $sql);
  }

?>

<nav class="my-navbar navbar navbar-expand-lg fixed-top" style="z-index: 99;">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <div class="d-flex justify-items-center align-items-center">
        <img src="../image/TATC_Logo_White_Text.png" width="50px" height="50px" class="img-fluid">
        <h4 style="font-family: 'Nunito', sans-serif; color: #feffff; margin: 0;">TATC PARKING</h4>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list text-white fs-1 fw-bold"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php
          if (isset($_SESSION['AdminID'])) {
            ?>
            <li class="nav-item nav-name-hide">
                <p style="color: #feffff; margin: 0; padding: 0;"><?php echo $UserData['name'] . " " . $UserData['lastname'] ?></p>
            </li>
            <li class="nav-item nav-item-hide">
                <a href="index.php" class="nav-link" style="color: #feffff;">หน้าหลัก</a>
            </li>
            <li class="nav-item nav-item-hide">
                <a href="registration-request.php" class="nav-link" style="color: #feffff;">คำขอลงทะเบียน</a>
            </li>
            <li class="nav-item nav-item-hide">
                <a href="registration-checking.php" class="nav-link" style="color: #feffff;">การตรวจสอบสภาพรถ</a>
            </li>
            <li class="nav-item nav-item-hide">
                <a href="reporting.php" class="nav-link" style="color: #feffff;">การรายงานปัญหา</a>
            </li>
            <li class="nav-item nav-item-hide">
                <a href="announcement.php" class="nav-link" style="color: #feffff;">ประชาสัมพันธ์</a>
            </li>
            <li class="nav-item nav-item-hide">
                <a href="#" onclick="Logout()" class="btn btn-danger" style="color: #feffff;">ออกจากระบบ</a>
            </li>
            <?php
          }
          else {
            ?>
            <li class="nav-item">
                <a href="../index.php" class="nav-link" style="color: #feffff;">ออกจากหลังบ้าน</a>
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