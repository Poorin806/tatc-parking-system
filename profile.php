<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    //Logged in checking
    if (!isset($_SESSION['UserID'])) {
        echo "<script>window.location='login.php';</script>";
    }
    else {
        $UserID = $_SESSION['UserID'];
        $UserType = $_SESSION['UserType'];
    }

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

    <!-- Parking Card css -->
    <link rel="stylesheet" href="css/parkingcard.css">

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

</head>
<body class="main-bg">

    <div>
        <?php
            //Navbar
            include_once 'import/navbar.php';

            //Sidebar
            include_once 'import/sidebar.php';
        ?>

        <div class="content">
            <!-- Title -->
            <div class="my-card d-flex align-items-center flex-row justify-content-between">
                <div><p class="fs-5 fw-bold m-0 p-0">ข้อมูลส่วนตัว</p></div>
                <div><a href="index.php" class="btn btn-maintheme">ย้อนกลับ</a></div>
            </div>

            <!-- Card content -->
            <div class="mt-4 my-card p-3 py-sm-4 px-sm-5">
                <h3 class="fw-bold">ข้อมูลส่วนตัว</h3>
                <?php
                    if ($UserType == "Student") {
                        $sql = "SELECT 
                                    student.*, prefix.prefix_name,
                                    department.dept_name, major.major_name, class_level.level_name,
                                    province.province_name, district.district_name_th, subdistrict.subDistrictName_th
                                FROM student 
                                JOIN prefix ON student.prefix_id = prefix.prefix_id
                                LEFT JOIN department ON student.dept_id = department.dept_id
                                LEFT JOIN major ON student.major_id = major.major_id
                                LEFT JOIN class_level ON student.level_id = class_level.level_id
                                LEFT JOIN province ON student.province_id = province.province_id
                                LEFT JOIN district ON student.district_id = district.district_id
                                LEFT JOIN subdistrict ON student.subDistrict_id = subdistrict.subDistrict_id
                                WHERE std_id = '$UserID'
                        ";
                    }
                    else {
                        $sql = "SELECT * FROM personnel WHERE personnel_id = '$UserID'";
                    }

                    $data = SQLSelect($con, $sql);
                    $Decoded_Data = base64_decode($data['img'], true);

                    // Checking if image file is base64_encode or not
                    if ($Decoded_Data !== false) {
                        // It's encode to base64
                        $UserImage = $data['img'];
                    }
                    else {
                        // It's not
                        $UserImage = base64_encode($data['img']);
                    }
                ?>

                <!-- Main Info -->
                <div class="mt-3 d-flex flex-column flex-sm-row align-items-start row-gap-4 column-gap-4">
                    <!-- Image -->
                    <div class="align-self-center">
                        <img src="data:image/jpeg;base64,<?php echo $UserImage ?>" alt="Profile Image" class="img-fluid rounded" width="120">
                    </div>
                    <!-- Data -->
                    <div class="d-flex flex-column align-items-start row-gap-2">
                        <p class="m-0 p-0 fs-5">
                            <span class="fw-bold">ชื่อ-นามสกุล: </span>
                            <?php
                                if ($UserType == "Student") {
                                    echo $data['prefix_name'] . $data['name'] . " " . $data['lastname'];
                                }
                                else {
                                    echo $data['name'] . " " . $data['lastname'];
                                }
                            ?>
                        </p>
                        <?php
                            if ($UserType == "Student") {
                                ?>
                                <p class="m-0 p-0 fs-5">
                                    <span class="fw-bold">แผนกวิชา: </span>
                                    <?php echo $data['dept_name'] ?>
                                </p>
                                <p class="m-0 p-0 fs-5">
                                    <span class="fw-bold">สาขาวิชา: </span>
                                    <?php echo $data['major_name'] ?>
                                </p>
                                <p class="m-0 p-0 fs-5">
                                    <span class="fw-bold">ระดับชั้น: </span>
                                    <?php echo $data['level_name'] ?>
                                </p>
                                <?php
                            }
                            else {
                                ?>
                                <p class="m-0 p-0 fs-5">
                                    <span class="fw-bold">เบอร์โทรศัพท์: </span>
                                    <?php echo $data['tel'] ?>
                                </p>
                                <p class="m-0 p-0 fs-5">
                                    <span class="fw-bold">Email: </span>
                                    <?php echo ($data['email'] != null || $data['email'] != "" ) ? $data['email'] : "-" ?>
                                </p>
                                <?php
                            }
                        ?>
                    </div>

                    <!-- More Detail (เริ่มแสดงเป็นคอลัมน์ใหม่) -->
                    <?php
                        if ($UserType == "Student") {
                            ?>
                            <div class="d-flex flex-column align-items-start row-gap-2">
                                <p class="m-0 p-0 fs-5">
                                    <span class="fw-bold">เบอร์โทรศัพท์: </span>
                                    <?php echo $data['tel'] ?>
                                </p>
                                <p class="m-0 p-0 fs-5">
                                    <span class="fw-bold">วันเกิด: </span>
                                    <?php echo formatDateThai_Full($data['birthday']) ?>
                                </p>
                                <p class="m-0 p-0 fs-5">
                                    <span class="fw-bold">ที่อยู่: </span>
                                    <?php echo $data['address'] . " ต." . $data['subDistrictName_th'] . " อ." . $data['district_name_th'] . " จ." . $data['province_name'] ?>
                                </p>
                            </div>
                            <?php
                        }
                    ?>
                </div>


                <hr>

                <!-- Edit Mode -->
                <div>
                    <button type="button" class="btn btn-maintheme-outline" data-bs-toggle="collapse" data-bs-target="#ProfileEditing" aria-expanded="false" aria-controls="ProfileEditing" onclick="ClearForm()">
                        แก้ไขข้อมูล
                    </button>

                    <!-- Editing Info -->
                    <div class="collapse card mt-3 mx-1 mx-sm-5" id="ProfileEditing">
                        <div class="card-body">
                            <form id="UserEditingForm" action="profile-update.php" method="POST" enctype="multipart/form-data">
                                <!-- Title -->
                                <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h4 class="fw-bold m-0 p-0">แก้ไขข้อมูลส่วนตัว</h4>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary" 
                                            <?php echo ($UserType == "Student") ? "onclick='DefaultData(\"Student\")'" : "onclick='DefaultData(\"Personnel\")'" ?>
                                        >
                                            ใช้ข้อมูลเดิม
                                        </button>
                                    </div>
                                </div>
                                <!-- Student ID || Personnel ID -->
                                <div class="mb-3">
                                    <?php
                                        if ($UserType == "Student") {
                                            ?>
                                            <label class="form-label">รหัสนักศึกษา</label>
                                            <input type="text" id="std_ID" name="std_ID" class="form-control bg-body-secondary" placeholder="รหัสนักศึกษา" value="<?php echo $data['std_id'] ?>" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required readonly>
                                            <?php
                                        }
                                        else {
                                            ?>
                                            <label class="form-label">บัตรประจำตัวประชาชน</label>
                                            <input type="text" id="Personnel_ID" name="Personnel_ID" class="form-control bg-body-secondary" placeholder="บัตรประจำตัวประชาชน" value="<?php echo $data['personnel_id'] ?>" maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required readonly>
                                            <?php
                                        }
                                     ?>
                                     <input type="text" name="OldUserID" value="<?php echo ($UserType == 'Student') ? $data['std_id'] : $data['personnel_id'] ?>" required readonly hidden>
                                </div>
                                <!-- Password -->
                                <div class="mb-3">
                                    <label class="form-label">รหัสผ่าน</label>
                                    <input type="password" id="Password" name="Password" class="form-control" placeholder="รหัสผ่าน" required>
                                </div>
                                <!-- Prefix, Surname, Lastname -->
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <?php
                                            if ($UserType == "Student") {
                                                ?>
                                                <div class="col-12 col-md-3 mb-3">
                                                    <label class="form-label">คำนำหน้า</label>
                                                    <select id="Prefix" name="Prefix" class="form-select" required>
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
                                                <?php
                                            }
                                        ?>
                                        <div class="<?php echo ($UserType == 'Student') ? 'col-12 col-md-4 mb-3' : 'col-12 col-md-6 mb-3' ?>">
                                            <label class="form-label">ชื่อ</label>
                                            <input type="text" id="Firstname" name="Firstname" class="form-control" placeholder="ชื่อ" required>
                                        </div>
                                        <div class="<?php echo ($UserType == 'Student') ? 'col-12 col-md-4 mb-3' : 'col-12 col-md-6 mb-3' ?>">
                                            <label class="form-label">นามสกุล</label>
                                            <input type="text" id="Lastname" name="Lastname" class="form-control" placeholder="นามสกุล" required>
                                        </div>
                                    </div>
                                </div>
                                <!-- Birthday, Class Level, Address -->
                                <?php
                                    if ($UserType == "Student") {
                                        ?>
                                        <!-- Birthday -->
                                        <div class="mb-3">
                                            <label class="form-label">วันเกิด</label>
                                            <input type="date" id="Birthday" name="Birthday" class="form-control" required>
                                        </div>
                                        <!-- Class Level -->
                                        <div class="mb-3">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-md-4 mb-3">
                                                    <label class="form-label">ระดับชั้น</label>
                                                    <select id="Level" name="Level" class="form-select" required>
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
                                                    <select id="Department" name="Department" class="form-select" required>
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
                                                    <select id="Major" name="Major" class="form-select" required>
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
                                            <input type="text" id="Address" name="Address" class="form-control" placeholder="บ้านเลขที่, หมู่, ซอย, ถนน" required>

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
                                                    <select id="District" name="District" class="form-select" required>
                                                        <option value="" selected>-- อำเภอ --</option>
                                                    </select>
                                                </div>
                                                <!-- Subdistrict -->
                                                <div class="col-12 col-md-4 mb-3">
                                                    <label class="form-label">ตำบล</label>
                                                    <select id="SubDistrict" name="SubDistrict" class="form-select" required>
                                                        <option value="" selected>-- ตำบล --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                ?>
                                <!-- Email (For Personnel) -->
                                <?php
                                    if ($UserType == "Personnel") {
                                        ?>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="text" id="Email" name="Email" class="form-control" placeholder="Email" required>
                                        </div>
                                        <?php
                                    }
                                ?>
                                <!-- Phone -->
                                <div class="mb-3">
                                    <label class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="text" id="Phone" name="Phone" class="form-control" placeholder="เบอร์โทรศัพท์" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                </div>
                                <!-- Image -->
                                <div class="mb-5 row align-items-start gy-3">
                                    <div class="col-12 col-sm-3">
                                        <label class="form-label m-0 p-0">รูปโปรไฟล์</label>
                                        <?php echo ($UserType == "Student") ? "<div class='form-text text-danger'>*รูปภาพควรใช้จาก RMS</div>" : "" ?>
                                    </div>
                                    <div class="col-12 col-sm">
                                        <input type="file" id="Image" name="Image" class="form-control" accept=".jpg, .png, .gif, .jpeg" max="5242880" required>
                                    </div>
                                    <div class="col-12 col-sm">
                                        <input type="checkbox" class="btn-check" id="OldImage" onclick="OldImageChecking()">
                                        <label class="btn btn-outline-primary" for="OldImage">ใช้รูปภาพเดิม</label>
                                        <input type="text" name="IsUseOldImage" id="IsUseOldImage" value="false" hidden>
                                    </div>
                                </div>
                                <!-- Confirm Zone -->
                                <div>
                                    <input type="text" name="UserType" value="<?php echo ($UserType == "Student") ? 'Student' : 'Personnel' ?>" hidden>
                                    <input type="submit" value="แก้ไขข้อมูล" name="Submit" class="btn btn-outline-warning">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Provicne District & subDistict Condition Checking (Student Only) -->
    <script type="text/javascript">

        var Province = document.getElementById("Province");
        var District = document.getElementById("District");
        var Form_SubDistrict = document.getElementById("SubDistrict");

        function SubDistrictPreview() {
            Form_SubDistrict.innerHTML = "";
            // Create a new option element
            var option = document.createElement("option");
            
            // Set the value and text of the option
            option.value = "-- ตำบล --";
            option.text = "-- ตำบล --";
            
            // Append the option to the select element
            Form_SubDistrict.appendChild(option);
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

    <!-- Auto Generation User Data -->
    <script>

        <?php
            if ($UserType == "Student") {
                ?>
                var UserID = "<?php echo $data['std_id'] ?>";
                var Password = "<?php echo $data['password'] ?>";
                var Prefix = "<?php echo $data['prefix_id'] ?>";
                var Firstname = "<?php echo $data['name'] ?>";
                var Lastname = "<?php echo $data['lastname'] ?>";
                var Birthday = "<?php echo $data['birthday'] ?>";
                var Class_level = "<?php echo $data['level_id'] ?>";
                var Department = "<?php echo $data['dept_id'] ?>";
                var Major = "<?php echo $data['major_id'] ?>";
                var Address = "<?php echo $data['address'] ?>";
                var Province = "<?php echo $data['province_id'] ?>";
                var District = "<?php echo $data['district_id'] ?>";
                var SubDistrict = "<?php echo $data['subdistrict_id'] ?>";
                var Phone = "<?php echo $data['tel'] ?>";

                var Form_std_ID = document.getElementById("std_ID");
                var Form_Password = document.getElementById("Password");
                var Form_Prefix = document.getElementById("Prefix");
                var Form_Firstname = document.getElementById("Firstname");
                var Form_Lastname = document.getElementById("Lastname");
                var Form_Birthday = document.getElementById("Birthday");
                var Form_Class_level = document.getElementById("Level");
                var Form_Department = document.getElementById("Department");
                var Form_Major = document.getElementById("Major");
                var Form_Address = document.getElementById("Address");
                var Form_Province = document.getElementById("Province");
                var Form_District = document.getElementById("District");
                var Form_SubDistrict = document.getElementById("SubDistrict");
                var Form_Phone = document.getElementById("Phone");
                <?php
            }
            else {
                ?>
                var UserID = "<?php echo $data['personnel_id'] ?>";
                var Password = "<?php echo $data['password'] ?>";
                var Firstname = "<?php echo $data['name'] ?>";
                var Lastname = "<?php echo $data['lastname'] ?>";
                var Phone = "<?php echo $data['tel'] ?>";
                var Email = "<?php echo $data['email'] ?>";

                var Form_Personnel_ID = document.getElementById("Personnel_ID");
                var Form_Password = document.getElementById("Password");
                var Form_Firstname = document.getElementById("Firstname");
                var Form_Lastname = document.getElementById("Lastname");
                var Form_Phone = document.getElementById("Phone");
                var Form_Email = document.getElementById("Email");
                <?php
            }
        ?>

        function DefaultData(UserType) {
            if (UserType == "Student") {
                // Form Generate Data
                Form_std_ID.value = UserID;
                Form_Password.value = Password;
                Form_Prefix.value = Prefix;
                Form_Firstname.value = Firstname;
                Form_Lastname.value = Lastname;
                Form_Birthday.value = Birthday;
                Form_Class_level.value = Class_level;
                Form_Department.value = Department;
                Form_Major.value = Major;
                Form_Address.value = Address;

                Form_Province.value = Province;
                Form_Phone.value = Phone;

                // Province, District and SubDistrict Process Auto-Generate
                $.ajax({
                    type: "POST",
                    url: "ajax_db.php",
                    data: {
                        id: Province, 
                        function:'Province'
                    },
                    success: function(data){
                        $('#District').html(data); 
                        $('#SubDistrict').html(); 
                        $('#SubDistrict').val(' ');

                        Form_District.value = District;

                        // Call Ajax for SubDistrict
                        $.ajax({
                            type: "POST",
                            url: "ajax_db.php",
                            data: {
                                id: District, 
                                function:'District'
                            },
                            success: function(data){
                                $('#SubDistrict').html(data); 
                                Form_SubDistrict.value = SubDistrict;
                            }
                        });
                    }
                });
            }
            else {
                // Form Generate Data
                Form_Personnel_ID.value = UserID;
                Form_Password.value = Password;
                Form_Firstname.value = Firstname;
                Form_Lastname.value = Lastname;
                Form_Phone.value = Phone;
                Form_Email.value = Email;
            }
        }

        function OldImageChecking() {
            var OldImage = document.getElementById("OldImage");
            var Image = document.getElementById("Image");
            var IsUseOldImage = document.getElementById("IsUseOldImage");

            if (OldImage.checked) {
                Image.required = false;
                Image.disabled = true;
                IsUseOldImage.value = "true";
                Image.value = "";
            }
            else {
                Image.required = true;
                Image.disabled = false;
                IsUseOldImage.value = "false";
            }
        }

        function ClearForm() {
            // Clear Form Data
            <?php
                if ($UserType == "Student") {
                    ?>
                    Form_std_ID.value = UserID;
                    Form_Password.value = '';
                    Form_Prefix.value = '1';
                    Form_Firstname.value = '';
                    Form_Lastname.value = '';
                    Form_Birthday.value = '';
                    Form_Class_level.value = '';
                    Form_Department.value = '';
                    Form_Major.value = '';
                    Form_Address.value = '';
                    Form_Province.value = '';
                    Form_Phone.value = '';
                    <?php
                }
                else {
                    ?>
                    Form_Personnel_ID.value = UserID;
                    Form_Password.value = '';
                    Form_Firstname.value = '';
                    Form_Lastname.value = '';
                    Form_Phone.value = '';
                    Form_Email.value = '';
                    <?php
                }
            ?>
        }
    </script>

</body>
</html>