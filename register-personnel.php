<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    //Logged in checking
    if (isset($_SESSION['UserID'])) {
        echo "<script>alert('คุณเข้าสู่่ระบบอยู่แล้ว!');</script>";
        echo "<script>window.location='index.php';</script>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TATC Parking - สมัครสมาชิก</title>

    <!-- Essentials css -->
    <?php include_once 'import/css.php'; ?>

    <!-- Login Css -->
    <link rel="stylesheet" href="css/login.css">

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>
</head>
<body class="main-bg">


    <?php
        if (isset($_POST['Submit'])) {

            $std_ID = $_POST['std_ID'];
            $Password = $_POST['Password'];
            $Prefix = $_POST['Prefix'];
            $Firstname = $_POST['Firstname'];
            $Lastname = $_POST['Lastname'];
            $Birthday = $_POST['Birthday'];
            $ClassLevel = $_POST['Level'];
            $Department = $_POST['Department'];
            $Major = $_POST['Major'];
            $Address = $_POST['Address'];
            $Province = $_POST['Province'];
            $Distinct = $_POST['Distinct'];
            $SubDistinct = $_POST['SubDistinct'];
            $Phone = $_POST['Phone'];

            $sql = "SELECT std_id FROM student WHERE std_id = '$std_ID'";
            $RegisterResult = SQLSelectNumRow($con, $sql);
            $UserID = SQLSelect($con, $sql);

            if ($RegisterResult != 0) {
                $_SESSION['UserID'] = $UserID['std_id'];
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'สมัครสมาชิกไม่สำเร็จ',
                        text: 'รหัสนักศึกษานี้มีข้อมูลอยู่แล้วในระบบ!',
                        confirmButtonText: 'ตกลง'
                    });
                </script>";
            }
            else {
                if (isset($_FILES['Image'])) {
                    // Check if a file was uploaded successfully
                    if ($_FILES['Image']['error'] === UPLOAD_ERR_OK) {
                        // Get the temporary file path
                        $tmpFilePath = $_FILES['Image']['tmp_name'];
                        
                        // Read the contents of the file
                        $fileContent = file_get_contents($tmpFilePath);
                        
                        // Escape special characters in the file content
                        $escapedContent = addslashes($fileContent);
                        
                        // Update the file content in the database
                        $sql = "INSERT INTO student VALUES(
                            '$std_ID',
                            '$Prefix',
                            '$Firstname',
                            '$Lastname',
                            '$Birthday',
                            '$ClassLevel',
                            '$Department',
                            '$Major',
                            '$Phone',
                            '$Address',
                            '$Password',
                            '$escapedContent',
                            '$Province',
                            '$Distinct',
                            '$SubDistinct'
                            );
                        ";
                    } 
                    else {
                        echo "<script>alert('Error uploading the file');</script>";
                    }
                }
                else {
                    $sql = "INSERT INTO student VALUES(
                        '$std_ID',
                        '$Prefix',
                        '$Firstname',
                        '$Lastname',
                        '$Birthday',
                        '$ClassLevel',
                        '$Department',
                        '$Major',
                        '$Phone',
                        '$Address',
                        '$Password',
                        null,
                        '$Province',
                        '$Distinct',
                        '$SubDistinct'
                        );
                    ";
                }

                //Query Insert Register Info into database
                SQLQuery($con, $sql);

                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'สมัครสมาชิกสำเร็จ',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        window.location='login.php'
                    });
                </script>";
            }
        }
    ?>

    <div>
        <?php
            // Navbar
            include_once 'import/navbar.php';
        ?>

        <div class="container my-card register-card py-5 px-4 mb-5" style="margin-top: 120px;">
            <p class="text-center fs-3 fw-bold">สมัครสมาชิก (บุคลากร)</p>
            <div class="d-flex flex-column-reverse flex-md-row align-items-center">
                <!-- Left Side Content -->
                <div class="register-content-left">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                        <!-- Prefix, Surname, Lastname -->
                        <div class="mb-3">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label">คำนำหน้า</label>
                                    <select name="Prefix" class="form-select" required>
                                        <?php
                                            $sql = "SELECT * FROM prefix";
                                            foreach (SQLSelectLoop($con, $sql) as $row) {
                                                ?>
                                                <option value="<?php echo $row['prefix_id'] ?>"><?php echo $row['prefix_name'] ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label">ชื่อ</label>
                                    <input type="text" name="Firstname" class="form-control" placeholder="ชื่อ" required>
                                </div>
                                <div class="col-12 col-md-5 mb-3">
                                    <label class="form-label">นามสกุล</label>
                                    <input type="text" name="Lastname" class="form-control" placeholder="นามสกุล" required>
                                </div>
                            </div>
                        </div>
                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" name="Phone" class="form-control" placeholder="เบอร์โทรศัพท์" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                        </div>
                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่าน</label>
                            <input type="text" name="Password" class="form-control" placeholder="รหัสผ่าน" required>
                        </div>
                        <!-- Image -->
                        <div class="mb-3">
                            <label class="form-label">รูปโปรไฟล์ (ไม่บังคับ)</label>
                            <input type="file" name="Image" class="form-control">
                        </div>
                        <!-- Confirm Zone -->
                        <div>
                            <!-- <input class="form-check-input" type="checkbox" value="" id="RegisterConfirm" required> -->
                            <label class="form-check-label" for="RegisterConfirm">
                                <p>
                                    <span class="d-inline">ฉันไม่ใช่บุคลากร, </span>
                                    <a href="register.php" class="nav-link text-primary d-inline">สมัครสมาชิกสำหรับนักศึกษา</a>
                                </p>
                            </label>

                            <input type="submit" value="สมัครสมาชิก" name="Submit" class="mt-3 d-block btn btn-success">
                        </div>
                    </form>
                </div>
                <!-- Right Side Content -->
                <div class="text-center p-4 register-content-right">
                    <img src="image/Logo.png" alt="TATC Logo (IMG)" width="200" class="img-fluid bg-white">
                </div> 
            </div>
        </div>
    </div>

</body>
</html>