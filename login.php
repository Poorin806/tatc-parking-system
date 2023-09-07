<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TATC Parking - เข้าสู่ระบบ</title>

    <!-- Essentials css -->
    <?php include_once 'import/css.php'; ?>

    <!-- Login Css -->
    <link rel="stylesheet" href="css/login.css">

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

</head>
<body class="main-bg">

    <?php
    
        //Logged in checking
        if (isset($_SESSION['User'])) {
            echo "<script>alert('คุณเข้าสู่่ระบบอยู่แล้ว!');</script>";
            echo "<script>window.location='index.php';</script>";
        }

        //Form login checking
        if (isset($_POST['Submit'])) {
            $Username = $_POST['Username'];
            $Password = $_POST['Password'];

            $sql = "SELECT std_id FROM student WHERE std_id = '$Username' AND BINARY password = '$Password'";
            $LoginResult = SQLSelectNumRow($con, $sql);
            $UserID = SQLSelect($con, $sql);

            if ($LoginResult != 0) {
                $_SESSION['UserID'] = $UserID['std_id'];
                $_SESSION['UserType'] = "Student";
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'เข้าสู่ระบบสำเร็จ',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        window.location='index.php'
                    });
                </script>";
            }
            else {
                $sql = "SELECT personnel_id FROM personnel WHERE personnel_id = '$Username' AND BINARY password = '$Password'";
                if (SQLSelectNumRow($con, $sql) != 0) {
                    $UserID = SQLSelect($con, $sql);
                    $_SESSION['UserID'] = $UserID['personnel_id'];
                    $_SESSION['UserType'] = "Personnel";
                    echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'เข้าสู่ระบบสำเร็จ',
                                confirmButtonText: 'ตกลง'
                            }).then((result) => {
                                window.location='index.php'
                            });
                        </script>";
                }
                else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'เข้าสู่ระบบไม่สำเร็จ',
                            text: 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!',
                            confirmButtonText: 'ตกลง'
                        });
                    </script>";
                }
            }
        }
    
    
    ?>

    <div class="my-container">
        <?php
            // Navbar
            include_once 'import/navbar.php';
        ?>

        <div class="container my-card login-card" style="margin-top: 120px;">
            <p class="mt-2 text-center fs-3 fw-bold">เข้าสู่ระบบ</p>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mb-3 mx-5">
                    <label class="form-label">รหัสนักศึกษา</label>
                    <input type="text" name="Username" class="form-control" required>
                </div>
                <div class="mb-5 mx-5">
                    <label class="form-label">รหัสผ่าน</label>
                    <input type="password" name="Password" class="form-control" required>
                </div>
                <div class="mb-3 text-center">
                    <input type="submit" value="เข้าสู่ระบบ" name="Submit" class="login-btn">
                </div>
            </form>

            <div class="mt-3 mb-3 text-center">
                <p>
                    ไม่มีบัญชี? <a href="register.php" class="text-link">สมัครสมาชิก</a>
                </p>
            </div>

            <div class="alert alert-warning" role="alert">
                สำหรับทดสอบ (ผู้ใช้) 65309010017 ทั้ง Username และ Password <br><br>
                สำหรับทดสอบ (ผู้ดูแลระบบ) ให้เติม Folder backend ".../backend/" ต่อท้าย URL เช่น localhost/TATCParking/backend/ 
                <br>
                Username: 11, Password: 12
            </div>
            <div class="alert alert-primary" role="alert">
                ชิ้นงาน Update ขึ้น Host ล่าสุด 4 กันยายน 2566!
                <div>
                    <b>Update :</b>
                    <ul class="m-0">
                        <li>ปริ้นสติีกเกอร์</li>
                        <li>ระบบอนุมัติการลงทะเบียนจอดรถ (สมบูรณ์)</li>
                    </ul>
                    <b>In Progress :</b>
                    <ul class="m-0">
                        <li>ระบบรายงานปัญหารถ (User / Backend)</li>
                        <li>ทดสอบระบบจริง</li>
                        <li>CSV File import (Optional)</li>
                    </ul>

                    <b>คิดเป็นเปอร์เซ็นต์ความก้าวหน้า 90/100 (90%)</b>
                </div>
            </div>
        </div>

    </div>
    
</body>
</html>