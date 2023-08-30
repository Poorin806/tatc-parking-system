<?php

    //Import connect.php file
    include_once('../import/connect.php');

    //Import SQL Query file
    include_once('../import/SQLQuery.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TATC Parking (Backend) - เข้าสู่ระบบ</title>

    <!-- Essentials css -->
    <?php include_once '../import/css.php'; ?>

    <!-- Login Css -->
    <link rel="stylesheet" href="../css/login.css">

    <!-- Essentials JavaScript -->
    <?php include_once '../import/js.php'; ?>

</head>
<body class="main-bg">

    <?php
    
        //Logged in checking
        if (isset($_SESSION['AdminID'])) {
            echo "<script>alert('คุณเข้าสู่่ระบบอยู่แล้ว!');</script>";
            echo "<script>window.location='index.php';</script>";
        }

        //Form login checking
        if (isset($_POST['Submit'])) {
            $Username = $_POST['Username'];
            $Password = $_POST['Password'];

            $sql = "SELECT * FROM admin WHERE admin_id = '$Username' AND BINARY password = '$Password'";
            $LoginResult = SQLSelectNumRow($con, $sql);
            $UserID = SQLSelect($con, $sql);

            if ($LoginResult != 0) {
                $_SESSION['AdminID'] = $UserID['admin_id'];
                $_SESSION['AdminPosition'] = $UserID['adminPos_id'];
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
    
    
    ?>

    <div class="my-container">
        <?php
            // Navbar
            include_once 'navbar.php';
        ?>

        <div class="container my-card login-card middle-center py-3">
            <p class="mt-2 text-center fs-3 fw-bold">เข้าสู่ระบบ</p>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mb-3 mx-5">
                    <label class="form-label">รหัสผู้ดูแล</label>
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
        </div>

    </div>
    
</body>
</html>