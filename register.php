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
                        $ImageFile = base64_encode($fileContent);
                        
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
                            '$ImageFile',
                            '$Province',
                            '$Distinct',
                            '$SubDistinct'
                            );
                        ";

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
                    else {
                        echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'สมัครสมาชิกไม่สำเร็จ',
                                    text: 'Error to uploading file!',
                                    confirmButtonText: 'ตกลง'
                                });
                            </script>";
                    }
                }
            }
        }
    ?>

    <div>
        <?php
            // Navbar
            include_once 'import/navbar.php';
        ?>

        <div class="container my-card register-card py-5 px-4 mb-5" style="margin-top: 120px;">
            <p class="text-center fs-3 fw-bold">สมัครสมาชิก</p>
            <div class="d-flex flex-column-reverse flex-md-row align-items-center">
                <!-- Left Side Content -->
                <div class="register-content-left">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                        <!-- It's not Student Registration -->
                        <div class="my-3 alert alert-warning" role="alert">
                            <label class="form-check-label" for="RegisterConfirm">
                                <p class="m-0 p-0">
                                    <span class="d-inline">ฉันไม่ใช่นักศึกษา, </span>
                                    <a href="register-personnel.php" class="nav-link text-primary d-inline">สมัครสมาชิกสำหรับบุคลากร</a>
                                </p>
                            </label>
                        </div>
                        <!-- Student ID -->
                        <div class="mb-3">
                            <label class="form-label">รหัสนักศึกษา</label>
                            <input type="text" name="std_ID" class="form-control" placeholder="รหัสนักศึกษา" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                        </div>
                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่าน</label>
                            <input type="password" name="Password" class="form-control" placeholder="รหัสผ่าน" required>
                        </div>
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
                        <!-- Birthday -->
                        <div class="mb-3">
                            <label class="form-label">วันเกิด</label>
                            <input type="date" name="Birthday" class="form-control" required>
                        </div>
                        <!-- Class Level -->
                        <div class="mb-3">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label">ระดับชั้น</label>
                                    <select name="Level" class="form-select" required>
                                        <option value="" selected>-- ระดับชั้น --</option>
                                        <?php
                                            $sql = "SELECT * FROM class_level";
                                            foreach (SQLSelectLoop($con, $sql) as $row) {
                                                ?>
                                                <option value="<?php echo $row['level_id'] ?>"><?php echo $row['level_name'] ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label">แผนกวิชา</label>
                                    <select name="Department" class="form-select" required>
                                        <option value="" selected>-- แผนกวิชา --</option>
                                        <?php
                                            $sql = "SELECT * FROM department";
                                            foreach (SQLSelectLoop($con, $sql) as $row) {
                                                ?>
                                                <option value="<?php echo $row['dept_id'] ?>"><?php echo $row['dept_name'] ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label">สาขาวิชา</label>
                                    <select name="Major" class="form-select" required>
                                        <option value="" selected>-- สาขาวิชา --</option>
                                        <?php
                                            $sql = "SELECT * FROM major";
                                            foreach (SQLSelectLoop($con, $sql) as $row) {
                                                ?>
                                                <option value="<?php echo $row['major_id'] ?>"><?php echo $row['major_name'] ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Address -->
                        <div class="mb-3">
                            <label class="form-label">ที่อยู่</label>
                            <input type="text" name="Address" class="form-control" placeholder="บ้านเลขที่, หมู่, ซอย, ถนน" required>

                            <div class="mt-2 row align-items-center">
                                <!-- Province -->
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label">จังหวัด</label>
                                    <select id="Province" name="Province" class="form-select" required>
                                        <option value="" selected>-- จังหวัด --</option>
                                        <?php
                                            $sql = "SELECT * FROM province";
                                            foreach (SQLSelectLoop($con, $sql) as $row) {
                                                ?>
                                                <option value="<?php echo $row['province_id'] ?>"><?php echo $row['province_name'] ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <!-- District -->
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label">อำเภอ</label>
                                    <select id="District" name="Distinct" class="form-select" required>
                                        <option value="" selected>-- อำเภอ --</option>
                                    </select>
                                </div>
                                <!-- Subdistrict -->
                                <div class="col-12 col-md-4 mb-3">
                                    <label class="form-label">ตำบล</label>
                                    <select id="SubDistrict" name="SubDistinct" class="form-select" required>
                                        <option value="" selected>-- ตำบล --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" name="Phone" class="form-control" placeholder="เบอร์โทรศัพท์" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                        </div>
                        <!-- Image -->
                        <div class="mb-3">
                            <label class="form-label">รูปโปรไฟล์ <span class="text-danger">*จำเป็น</span></label>
                            <input type="file" name="Image" class="form-control" accept="image/*" max="5242880" required>
                            <div class="form-text text-danger">รูปภาพควรใช้จาก RMS และควรมีขนาดไม่เกิน 5MB</div>
                        </div>
                        <!-- Confirm Zone -->
                        <div>
                            <input type="submit" value="สมัครสมาชิก" name="Submit" class="mt-3 d-block btn btn-success">
                        </div>
                    </form>
                </div>
                <!-- Right Side Content -->
                <div class="text-center p-4 register-content-right">
                    <img src="image/logo.png" alt="TATC Logo (IMG)" width="200" class="img-fluid bg-white">
                </div> 
            </div>
        </div>
    </div>

    <!-- Provicne District & subDistict Condition Checking -->
    <script type="text/javascript">

        var Province = document.getElementById("Province");
        var District = document.getElementById("District");
        var SubDistrict = document.getElementById("SubDistrict");

        function SubDistrictPreview() {
            SubDistrict.innerHTML = "";
            // Create a new option element
            var option = document.createElement("option");
            
            // Set the value and text of the option
            option.value = "-- ตำบล --";
            option.text = "-- ตำบล --";
            
            // Append the option to the select element
            SubDistrict.appendChild(option);
        }

        $('#Province').change(function() {
            var id_province = $(this).val();
        
            $.ajax({
                type: "POST",
                url: "ajax_db.php",
                data: {id:id_province,function:'Province'},
                success: function(data){
                    $('#District').html(data); 
                    $('#SubDistrict').html(); 
                    $('#SubDistrict').val(' ');
                    SubDistrictPreview();
                }
            });
        });
        
        $('#District').change(function() {
            var id_amphures = $(this).val();
        
            $.ajax({
                type: "POST",
                url: "ajax_db.php",
                data: {id:id_amphures,function:'District'},
                success: function(data){
                    $('#SubDistrict').html(data);  
                }
            });
        });
    </script>

</body>
</html>