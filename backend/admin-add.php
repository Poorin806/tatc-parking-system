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

            // If Form submitted
            if (isset($_POST['AdminAddNew'])) {

                $Admin_id = $_POST['Username'];
                $Password = $_POST['Password'];
                $Prefix = $_POST['Prefix'];
                $Firstname = $_POST['Firstname'];
                $Lastname = $_POST['Lastname'];
                $Phone = $_POST['Phone'];
                $Position = $_POST['Position'];

                if (isset($_FILES['Image']) && $_FILES['Image']['tmp_name'] != null) {
                    $Image_name = $_FILES['Image']['name'];
                    $Image_data = file_get_contents($_FILES['Image']['tmp_name']);
                    $Image_data_upload = base64_encode($Image_data);

                    $sql = "INSERT INTO admin VALUES ('$Admin_id', '$Prefix', '$Firstname', '$Lastname', '$Phone', '$Position', '$Password', '$Image_data_upload')";
                }
                else {
                    $sql = "INSERT INTO admin VALUES ('$Admin_id', '$Prefix', '$Firstname', '$Lastname', '$Phone', '$Position', '$Password', null)";
                }

                SQLQuery($con, $sql);

                ?>
                <script>
                    Swal.fire({
                        icon: "success",
                        title: "เพิ่มข้อมูลสำเร็จ",
                        confirmButtonText: "ตกลง",
                    }).then((result) => {
                        window.location = "admin.php";
                    });
                </script>
                <?php


            }

        ?>

        <div class="content">
            
            <!-- Title -->
            <div class="my-card">
                <div class="row align-items-center justify-content-between row-gap-3">
                    <div class="col-sm-3">
                        <h1 class="m-0 p-0 fs-5 fw-bold">ผู้ดูแลระบบ > เพิ่มข้อมูล</h1>
                    </div>
                    <div class="col-sm-9 text-end">
                        <a href="admin.php" class="btn btn-maintheme">
                            ย้อนกลับ
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-4 my-card p-4">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                    <!-- Username -->
                    <div class="row align-items-center mb-3">
                        <div class="col-3">
                            <label class="form-label m-0 p-0">รหัสผู้ดูแล (Username)</label>
                        </div>
                        <div class="col">
                            <input type="text" name="Username" class="form-control" placeholder="ชื่อผู้ใช้ 11 หลัก" required style="width: 350px;">
                        </div>
                    </div>
                    <!-- Password -->
                    <div class="row align-items-center mb-3">
                        <div class="col-3">
                            <label class="form-label m-0 p-0">รหัสผ่านผู้ดูแล (Password)</label>
                        </div>
                        <div class="col">
                            <input type="password" name="Password" class="form-control" placeholder="รหัสผ่าน" required style="width: 350px;">
                        </div>
                    </div>
                    <!-- Prefix -->
                    <div class="row align-items-center mb-3">
                        <div class="col-3">
                            <label class="form-label m-0 p-0">คำนำหน้าชื่อ</label>
                        </div>
                        <div class="col">
                            <select name="Prefix" class="form-select" style="width: 350px;" required>
                                <option value="" selected>- คำนำหน้า -</option>
                                <option value="1">นาย</option>
                                <option value="2">นาง</option>
                                <option value="3">นางสาว</option>
                            </select>
                        </div>
                    </div>
                    <!-- Fullname -->
                    <div class="row align-items-center mb-3">
                        <div class="col-3">
                            <label class="form-label m-0 p-0">ชื่อ - นามสกุล</label>
                        </div>
                        <div class="col-auto">
                            <input type="text" name="Firstname" class="form-control" placeholder="ชื่อ" required style="width: 350px;">
                        </div>
                        <div class="col">
                            <input type="text" name="Lastname" class="form-control" placeholder="นามสกุล" required style="width: 350px;">
                        </div>
                    </div>
                    <!-- Phone -->
                    <div class="row align-items-center mb-3">
                        <div class="col-3">
                            <label class="form-label m-0 p-0">เบอร์โทรศัพท์</label>
                        </div>
                        <div class="col">
                            <input type="text" name="Phone" class="form-control" placeholder="เบอร์โทรศัพท์" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required style="width: 350px;">
                        </div>
                    </div>
                    <!-- Position -->
                    <div class="row align-items-center mb-3">
                        <div class="col-3">
                            <label class="form-label m-0 p-0">ตำแหน่ง</label>
                        </div>
                        <div class="col">
                            <select name="Position" class="form-select" style="width: 350px;" required>
                                <option value="" selected>- ตำแหน่ง -</option>
                                <option value="2">ผู้ดูแล</option>
                                <?php
                                    $Admin_ID = $_SESSION['AdminID'];
                                    $sql = "SELECT * FROM admin WHERE admin_id = '$Admin_ID'";
                                    $data = SQLSelect($con, $sql);
                                    if ($data['adminPos_id'] == 1) {
                                        ?>
                                        <option value="1">ผู้ดูแลสูงสุด</option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- Picture (Image Profile) -->
                    <div class="row align-items-center mb-3">
                        <div class="col-3">
                            <label class="form-label m-0 p-0">รูปภาพ</label>
                        </div>
                        <div class="col">
                            <input type="file" name="Image" class="form-control" style="width: 350px;" required>
                            <div class="form-text text-danger"><i>*จำเป็น</i></div>
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="my-3">
                        <input type="submit" value="เพิ่มข้อมูล" name="AdminAddNew" class="btn btn-maintheme">
                        <a href="admin.php" class="btn btn-outline-danger">ยกเลิก</a>
                    </div>
                </form>
            </div>

        </div>

    </div>
    

</body>
</html>