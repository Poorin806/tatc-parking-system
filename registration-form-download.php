<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    //Logged in checking
    if (!isset($_SESSION['UserID'])) {
        echo "<script>window.location='login.php';</script>";
    }

    // New Idea
    if (isset($_GET['ID'])) {
        $ID = $_GET['ID'];

        // Checking if ID is for student or personnel
        // Student information
        if ($_SESSION['UserType'] == "Student") {
            $sql = "SELECT 
                        registration.*, prefix.prefix_name, 
                        student.name, student.lastname, department.dept_name, student.address,
                        student.tel, class_level.level_name, major.major_name, 
                        province.province_name,  district.district_name_th, 
                        subdistrict.subDistrictName_th, vehicle_type.vehiType_name, 
                        vehicle_brand.vehiBrand_name, vehicle.vehiModel, vehicle_province.vehicle_province,
                        color.color_name
                    FROM 
                        registration, student, prefix, department, major, 
                        province, district, subdistrict, class_level,
                        vehicle, vehicle_type, vehicle_brand, color,
                        (
                            SELECT 
                                province.province_name AS vehicle_province
                            FROM 
                                vehicle, registration, province 
                            WHERE 
                                (registration.vehicle_id = vehicle.vehicle_id) AND
                                (vehicle.province_id = province.province_id) AND
                                (registration.regis_id = $ID)
                        ) AS vehicle_province
                    WHERE
                        (registration.std_id = student.std_id) AND
                        (student.prefix_id = prefix.prefix_id) AND
                        (student.dept_id = department.dept_id) AND
                        (student.major_id = major.major_id) AND
                        (student.level_id = class_level.level_id) AND
                        (student.province_id = province.province_id) AND
                        (student.district_id = district.district_id) AND
                        (student.subdistrict_id = subdistrict.subDistrict_id) AND
                        (registration.vehicle_id = vehicle.vehicle_id) AND
                        (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                        (vehicle.color_id = color.color_id) AND
                        (vehicle.vehiBrand_id = vehicle_brand.vehiBrand_id) AND
                        (registration.regis_id = $ID);
            ";
            $data = SQLSelect($con, $sql);

            $VehicleType = $data['vehiType_name'];
            $Fullname = $data['prefix_name'] . $data['name'] . " " . $data['lastname'];
            $Address = $data['address'];
            $ClassLevel = $data['level_name'];
            $Department = $data['dept_name'];
            $Phone = $data['tel'];
            $Major = $data['major_name'];
            $Province = $data['province_name'];
            $Distict = $data['district_name_th'];
            $SubDistict = $data['subDistrictName_th'];

            $VehicleID = $data['vehicle_id'];
            $Brand = $data['vehiBrand_name'];
            $VehicleModel = $data['vehiModel'];
            $VehicleColor = $data['color_name'];

            $VehicleProvince = $data['vehicle_province'];

            $CopyOfVehicle = $data['copyOf_VehicleRegis'];   //Input type file
            $CopyOfownerIdCard = $data['copyOf_ownerIdCard'];     //Input type file
            $CopyOfuserIdCard = $data['copyOf_userIdCard'];   //Input type file

            $PictureVehicleFront = $data['img_front'];  //Input type file
            $PictureVehicleSide = $data['img_side'];    //Input type file
            $PictureVehicleBack = $data['img_back'];    //Input type file
        }
        //Personnel information
        else {
            $sql = "SELECT 
                        registration.*,
                        personnel.personnel_id,
                        personnel.name, personnel.lastname,
                        personnel.tel, personnel.email,
                        vehicle_type.vehiType_name, vehicle_brand.vehiBrand_name,
                        vehicle.vehiModel, color.color_name, province.province_name
                    FROM 
                        registration, personnel,
                        vehicle, vehicle_type, vehicle_brand, color, province
                    WHERE
                        (registration.regis_id = $ID) AND
                        (registration.personnel_id = personnel.personnel_id) AND
                        (registration.vehicle_id = vehicle.vehicle_id) AND
                        (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                        (vehicle.vehiBrand_id = vehicle_brand.vehiBrand_id) AND
                        (vehicle.color_id = color.color_id) AND
                        (vehicle.province_id = province.province_id);
            ";

            $data = SQLSelect($con, $sql);

            $VehicleType = $data['vehiType_name'];
            $Fullname = $data['name'] . " " . $data['lastname'];
            $Phone = $data['tel'];

            $VehicleID = $data['vehicle_id'];
            $Brand = $data['vehiBrand_name'];
            $VehicleModel = $data['vehiModel'];
            $VehicleColor = $data['color_name'];

            $VehicleProvince = $data['province_name'];

            $CopyOfVehicle = $data['copyOf_VehicleRegis'];   //Input type file
            $CopyOfownerIdCard = $data['copyOf_ownerIdCard'];     //Input type file
            $CopyOfuserIdCard = $data['copyOf_userIdCard'];   //Input type file

            $PictureVehicleFront = $data['img_front'];  //Input type file
            $PictureVehicleSide = $data['img_side'];    //Input type file
            $PictureVehicleBack = $data['img_back'];    //Input type file
        }
    }
    else {
        echo "<script>
            window.location = 'status.php';
        </script>";
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
</head>
<body class="main-bg">

    <div class="my-container" style="overflow: hidden;">

        <!-- Background Cover A4 Form -->
        <div class="bg-white" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <div class="spinner-border text-primary fs-2" role="status" style="width: 150px; height: 150px;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

        <div>
            <?php
                    // Hide for PDF Print (Student)
                    if ($_SESSION['UserType'] == "Student") {
                        ?>
                            <div id="RegistrationForm" style="background-color: transparent; width: 793.7px;">
                                <!-- Registration No. -->
                                <div class="d-flex flex-inline align-items-center justify-content-end" style="width: 650px;">
                                    <div style="font-size: 14px;">เลขที่</div>
                                    <div class="text-center" style="width: 100px; border-bottom: 1px dotted #000;"><?php echo str_pad($ID, 5, '0', STR_PAD_LEFT) ?></div>
                                </div>
                                <!-- Title -->
                                <div class="d-flex flex-inline align-items-center">
                                    <div><img src="image/LOGO-TATC_Praking-Black_Text.png" width="90"></div>
                                    <div class="ms-3 fw-bold" style="font-size: 18px;">แบบฟอร์มการขอนำยานพาหนะมาใช้ภายในวิทยาลัยเทคนิคสัตหีบ (ปค.16)</div>
                                </div>
                                <!-- Body -->
                                <!-- Row 1 -->
                                <div class="d-flex flex-inline align-items-center mb-2">
                                    <div style="margin-left: 100px;">ข้าพเจ้า</div>
                                    <div class="text-center" style="width: 250px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $Fullname ?></div>
                                    <div>ที่อยู่</div>
                                    <div class="text-center" style="width: 225px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $Address ?></div>
                                </div>
                                <!-- Row 2 -->
                                <div class="d-flex flex-inline align-items-center mb-2">
                                    <div class="ms-5">ตำบล</div>
                                    <div class="text-center" style="width: 160px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $SubDistict ?></div>
                                    <div>อำเภอ</div>
                                    <div class="text-center" style="width: 160px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $Distict ?></div>
                                    <div>จังหวัด</div>
                                    <div class="text-center" style="width: 165px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $Province ?></div>
                                </div>
                                <!-- Row 3 -->
                                <div class="d-flex flex-inline align-items-center mb-2">
                                    <div class="ms-5">แผนกวิชา</div>
                                    <div class="text-center" style="width: 230px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $Department ?></div>
                                    <div>ชั้นปี</div>
                                    <div class="text-center" style="width: 100px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $ClassLevel ?></div>
                                    <div>เบอร์โทร</div>
                                    <div class="text-center" style="width: 130px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $Phone ?></div>
                                </div>
                                <!-- Row 4 -->
                                <div class="d-flex flex-inline align-items-center mb-2">
                                    <div class="ms-5">ยานพาหนะทะเบียน</div>
                                    <div class="text-center" style="width: 130px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $VehicleID ?></div>
                                    <div>จังหวัด</div>
                                    <div class="text-center" style="width: 130px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $VehicleProvince ?></div>
                                    <div>สียานพาหนะ</div>
                                    <div class="text-center" style="width: 105px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $VehicleColor ?></div>
                                </div>
                                <!-- Row 5 -->
                                <div class="d-flex align-items-center mb-2">
                                    <div class="ms-5" style="width: 150px;">ยานพาหนะประเภท</div>
                                    <div clsss="ms-5" style="width: 100px;">
                                        <input type="checkbox" class="form-check-input" id="Car" onclick="this.checked=!this.checked;" 
                                            <?php echo ($VehicleType == "รถยนต์") ? "checked" : ""; ?>
                                        >
                                        <label class="form-check-label" for="Car">รถยนต์</label>
                                    </div>
                                    <div clsss="ms-5" style="width: 200px;">
                                        <input type="checkbox" class="form-check-input" id="Motorbike" onclick="this.checked=!this.checked;" 
                                            <?php echo ($VehicleType == "รถจักรยานยนต์") ? "checked" : ""; ?>
                                        >
                                        <label class="form-check-label" for="Motorbike">รถจักรยานต์</label>
                                    </div>
                                </div>
                                <!-- Row 6 -->
                                <div class="ms-5" style="width: 600px;">
                                    <p class="m-0 p-0">
                                        ข้าพเจ้าเป็นผู้ใช้ยานพาหนะดังกล่าว หากสร้างความเสียหาย, เดือนร้อนใด ๆ ที่เกิดขึ้นกับทรัพย์สิน <br>
                                        ทางราชการ หรือผู้อื่น ข้าพเจ้าขอรับผิดชอบทุกประการ และจะปฏิบัติตามกฎระเบียบข้อกำหนดต่าง ๆ <br>
                                        ของวิทยาลัย ฯ โดยได้แนบเอกสารเพื่อประกอบการพิจารณา ดังนี้
                                    </p>
                                    <form action="#" class="mt-2 ms-5">
                                        <div>
                                            <input type="checkbox" class="form-check-input" id="CopyOfVehicle" onclick="this.checked=!this.checked;" 
                                                <?php echo ($CopyOfVehicle != null) ? "checked" : ""; ?>
                                            >
                                            <label class="form-check-label" for="CopyOfVehicle">สำเนาทะเบียนยานพาหนะ</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" class="form-check-input" id="IDCardVehicleOwner" onclick="this.checked=!this.checked;" 
                                                <?php echo ($CopyOfVehicle != null) ? "checked" : ""; ?>
                                            >
                                            <label class="form-check-label" for="IDCardVehicleOwner">สำเนาบัตรประจำตัวประชาชนเจ้าของกรรมสิทธิ์</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" class="form-check-input" id="IDCard" onclick="this.checked=!this.checked;" 
                                                <?php echo ($CopyOfVehicle != null) ? "checked" : ""; ?>
                                            >
                                            <label class="form-check-label" for="IDCard">สำเนาบัตรประจำตัวประชาชน</label>
                                        </div>
                                    </form>
                                </div>
                                <!-- Row 7 (Signature) -->
                                <div class="mt-3 ms-5 text-end" style="width: 610px;">
                                    <div class="d-flex flex-inline align-items-end justify-content-end">
                                        <div>ลงชื่อ</div>
                                        <div class="text-center" style="width: 250px; border-bottom: 1px dotted #000;"></div>
                                        <div>เจ้าของกรรมสิทธิ์</div>
                                    </div>
                                    <div class="mt-3 d-flex flex-inline align-items-end justify-content-end" style="margin-right: 100px;">
                                        <div>&#40;</div>
                                        <div class="text-center" style="width: 250px; border-bottom: 1px dotted #000;"></div>
                                        <div>&#41;</div>
                                    </div>
                                </div>

                                <div class="mt-4 mb-4 ms-5 text-end" style="width: 610px;">
                                    <div class="d-flex flex-inline align-items-end justify-content-end" style="margin-right: 15px;">
                                        <div>ลงชื่อ</div>
                                        <div class="text-center" style="width: 250px; border-bottom: 1px dotted #000;"></div>
                                        <div>ผู้ใช้ยานพาหนะ</div>
                                    </div>
                                    <div class="mt-3 d-flex flex-inline align-items-end justify-content-end" style="margin-right: 100px;">
                                        <div>&#40;</div>
                                        <div class="text-center" style="width: 250px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $Fullname ?></div>
                                        <div>&#41;</div>
                                    </div>
                                </div>

                                <!-- Considering Result -->
                                <div class="mb-3 ms-5 border-bottom border-black" style="width: 610px;"></div>

                                <div class="ms-5 d-flex align-items-start" stlye="width: 610px;">
                                    <div><p><b><u>ผลการพิจารณา</u></b></p></div>
                                    <div>
                                        <form action="#" class="ms-5">
                                            <div class="mb-2">
                                                <input type="checkbox" class="form-check-input" id="Verified" onclick="this.checked=!this.checked;">
                                                <label class="form-check-label" for="Verified">ไม่อนุญาต เนื่องจาก</label>
                                                <div class="mt-2 ms-3">
                                                    <div class="d-flex flex-inline align-items-center gap-2 ms-5">
                                                        <div clsss="mb-3">
                                                            <input type="checkbox" class="form-check-input" id="illegalVehicle" onclick="this.checked=!this.checked;">
                                                            <label class="form-check-label" for="illegalVehicle">รถดัดแปลง</label>
                                                        </div>
                                                        <div clsss="mb-3">
                                                            <input type="checkbox" class="form-check-input" id="IncompleteDocuments" onclick="this.checked=!this.checked;">
                                                            <label class="form-check-label" for="IncompleteDocuments">เอกสารไม่ครบ</label>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-inline align-items-center gap-2 ms-5">
                                                        <div clsss="mb-3">
                                                            <input type="checkbox" class="form-check-input" id="IncorrectDocuments" onclick="this.checked=!this.checked;">
                                                            <label class="form-check-label" for="IncorrectDocuments">เอกสารไม่ถูกต้อง</label>
                                                        </div>
                                                        <div clsss="mb-3">
                                                            <div class="d-flex flex-inline align-items-end">
                                                                <div style="width: 60px;">
                                                                    <input type="checkbox" class="form-check-input" id="IncorrectDocuments" onclick="this.checked=!this.checked;">
                                                                    <label class="form-check-label" for="IncorrectDocuments">อื่น ๆ</label>
                                                                </div>
                                                                <div class="text-center" style="width: 205px; border-bottom: 1px dotted #000;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <input type="checkbox" class="form-check-input" id="NotVerified" onclick="this.checked=!this.checked;">
                                                <label class="form-check-label" for="NotVerified">อนุญาต</label>
                                                <div class="mt-2 ms-5 d-flex flex-inline align-items-end">
                                                    <div>ให้ใช้บัตรผ่านเลขที่</div>
                                                    <div class="text-center" style="width: 305px; border-bottom: 1px dotted #000;"></div>
                                                </div>
                                                <div class="mt-2 ms-5 d-flex flex-inline align-items-end">
                                                    <div>ออกเมื่อวันที่</div>
                                                    <div class="text-center" style="width: 130px; border-bottom: 1px dotted #000;"></div>
                                                    <div>หมดอายุวันที่</div>
                                                    <div class="text-center" style="width: 130px; border-bottom: 1px dotted #000;"></div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Considering Result -->
                                <div class="mt-3 ms-5 d-flex flex-inline justify-content-between" style="width: 615px;">
                                    <!-- Left Side -->
                                    <div>
                                        <div class="d-flex flex-inline">
                                            <div>ลงชื่อ</div>
                                            <div class="text-center" style="width: 200px; border-bottom: 1px dotted #000;"></div>
                                            <div>ผู้ออกบัตร</div>
                                        </div>
                                        <div class="d-flex flex-inline">
                                            <div style="margin-left: 35px;">&#40;</div>
                                                <div class="text-center" style="width: 200px; border-bottom: 1px dotted #000;"></div>
                                            <div>&#41;</div>
                                        </div>
                                        <div style="margin-left: 70px;">หัวหน้างานปกครอง</div>
                                    </div>
                                    <!-- Right Side -->
                                    <div>
                                        <div class="d-flex flex-inline">
                                            <div>ลงชื่อ</div>
                                            <div class="text-center" style="width: 200px; border-bottom: 1px dotted #000;"></div>
                                            <div>ผู้อนุญาต</div>
                                        </div>
                                        <div class="d-flex flex-inline">
                                            <div style="margin-left: 35px;">&#40;</div>
                                                <div class="text-center" style="width: 200px; border-bottom: 1px dotted #000;"></div>
                                            <div>&#41;</div>
                                        </div>
                                        <div class="text-center">รองผู้อำนวยการฝ่ายพัฒนากิจกรรม<br>นักเรียน นักศึกษา</div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                    // Hide for PDF Print (Personnel)
                    else {
                        ?>
                        <div id="RegistrationForm" style="background-color: transparent; width: 793.7px;">
                            <!-- Registration No. -->
                            <div class="d-flex flex-inline align-items-center justify-content-end" style="width: 650px;">
                                <div style="font-size: 14px;">เลขที่</div>
                                <div class="text-center" style="width: 100px; border-bottom: 1px dotted #000;"><?php echo str_pad($ID, 5, '0', STR_PAD_LEFT) ?></div>
                            </div>
                            <!-- Title -->
                            <div class="d-flex flex-inline align-items-center">
                                <div><img src="image/LOGO-TATC_Praking-Black_Text.png" width="90"></div>
                                <div class="ms-3 fw-bold" style="font-size: 18px;">แบบฟอร์มการขอนำยานพาหนะมาใช้ภายในวิทยาลัยเทคนิคสัตหีบ (ปค.16)</div>
                            </div>
                            <!-- Body -->
                            <!-- Row 1 -->
                            <div class="d-flex flex-inline align-items-center mb-2">
                                <div style="margin-left: 100px;">ข้าพเจ้า</div>
                                <div class="text-center" style="width: 280px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $Fullname ?></div>
                                <div>เบอร์โทร</div>
                                <div class="text-center" style="width: 170px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $Phone ?></div>
                            </div>
                            <!-- Row 2 -->
                            <div class="d-flex flex-inline align-items-center mb-2">
                                <div class="ms-5">ยานพาหนะทะเบียน</div>
                                <div class="text-center" style="width: 130px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $VehicleID ?></div>
                                <div>จังหวัด</div>
                                <div class="text-center" style="width: 130px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $VehicleProvince ?></div>
                                <div>สียานพาหนะ</div>
                                <div class="text-center" style="width: 105px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $VehicleColor ?></div>
                            </div>
                            <!-- Row 3 -->
                            <div class="d-flex align-items-center mb-2">
                                <div class="ms-5" style="width: 150px;">ยานพาหนะประเภท</div>
                                <div clsss="ms-5" style="width: 100px;">
                                    <input type="checkbox" class="form-check-input" id="Car" onclick="this.checked=!this.checked;" 
                                        <?php echo ($VehicleType == "รถยนต์") ? "checked" : ""; ?>
                                    >
                                    <label class="form-check-label" for="Car">รถยนต์</label>
                                </div>
                                <div clsss="ms-5" style="width: 200px;">
                                    <input type="checkbox" class="form-check-input" id="Motorbike" onclick="this.checked=!this.checked;" 
                                        <?php echo ($VehicleType == "รถจักรยานยนต์") ? "checked" : ""; ?>
                                    >
                                    <label class="form-check-label" for="Motorbike">รถจักรยานต์</label>
                                </div>
                            </div>
                            <!-- Row 4 -->
                            <div class="ms-5" style="width: 600px;">
                                <p class="m-0 p-0">
                                    ข้าพเจ้าเป็นผู้ใช้ยานพาหนะดังกล่าว หากสร้างความเสียหาย, เดือนร้อนใด ๆ ที่เกิดขึ้นกับทรัพย์สิน <br>
                                    ทางราชการ หรือผู้อื่น ข้าพเจ้าขอรับผิดชอบทุกประการ และจะปฏิบัติตามกฎระเบียบข้อกำหนดต่าง ๆ <br>
                                    ของวิทยาลัย ฯ โดยได้แนบเอกสารเพื่อประกอบการพิจารณา ดังนี้
                                </p>
                                <form action="#" class="mt-2 ms-5">
                                    <div>
                                        <input type="checkbox" class="form-check-input" id="CopyOfVehicle" onclick="this.checked=!this.checked;" 
                                            <?php echo ($CopyOfVehicle != null) ? "checked" : ""; ?>
                                        >
                                        <label class="form-check-label" for="CopyOfVehicle">สำเนาทะเบียนยานพาหนะ</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" class="form-check-input" id="IDCardVehicleOwner" onclick="this.checked=!this.checked;" 
                                            <?php echo ($CopyOfVehicle != null) ? "checked" : ""; ?>
                                        >
                                        <label class="form-check-label" for="IDCardVehicleOwner">สำเนาบัตรประจำตัวประชาชนเจ้าของกรรมสิทธิ์</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" class="form-check-input" id="IDCard" onclick="this.checked=!this.checked;" 
                                            <?php echo ($CopyOfVehicle != null) ? "checked" : ""; ?>
                                        >
                                        <label class="form-check-label" for="IDCard">สำเนาบัตรประจำตัวประชาชน</label>
                                    </div>
                                </form>
                            </div>
                            <!-- Row 5 (Signature) -->
                            <div class="mt-4 ms-5 text-end" style="width: 610px;">
                                <div class="d-flex flex-inline align-items-end justify-content-end">
                                    <div>ลงชื่อ</div>
                                    <div class="text-center" style="width: 250px; border-bottom: 1px dotted #000;"></div>
                                    <div>เจ้าของกรรมสิทธิ์</div>
                                </div>
                                <div class="mt-3 d-flex flex-inline align-items-end justify-content-end" style="margin-right: 100px;">
                                    <div>&#40;</div>
                                    <div class="text-center" style="width: 250px; border-bottom: 1px dotted #000;"></div>
                                    <div>&#41;</div>
                                </div>
                            </div>

                            <div class="mt-4 mb-4 ms-5 text-end" style="width: 610px;">
                                <div class="d-flex flex-inline align-items-end justify-content-end" style="margin-right: 15px;">
                                    <div>ลงชื่อ</div>
                                    <div class="text-center" style="width: 250px; border-bottom: 1px dotted #000;"></div>
                                    <div>ผู้ใช้ยานพาหนะ</div>
                                </div>
                                <div class="mt-3 d-flex flex-inline align-items-end justify-content-end" style="margin-right: 100px;">
                                    <div>&#40;</div>
                                    <div class="text-center" style="width: 250px; border-bottom: 1px dotted #000; font-size: 14px;"><?php echo $Fullname ?></div>
                                    <div>&#41;</div>
                                </div>
                            </div>

                            <!-- Considering Result -->
                            <div class="my-5 ms-5 border-bottom border-black" style="width: 610px;"></div>

                            <div class="ms-5 d-flex align-items-start" stlye="width: 610px;">
                                <div><p class="mt-3"><b><u>ผลการพิจารณา</u></b></p></div>
                                <div>
                                    <form action="#" class="mt-3 ms-5">
                                        <div class="mb-2">
                                            <input type="checkbox" class="form-check-input" id="Verified" onclick="this.checked=!this.checked;">
                                            <label class="form-check-label" for="Verified">ไม่อนุญาต เนื่องจาก</label>
                                            <div class="mt-2 ms-3">
                                                <div class="d-flex flex-inline align-items-center gap-2 ms-5">
                                                    <div clsss="mb-3">
                                                        <input type="checkbox" class="form-check-input" id="illegalVehicle" onclick="this.checked=!this.checked;">
                                                        <label class="form-check-label" for="illegalVehicle">รถดัดแปลง</label>
                                                    </div>
                                                    <div clsss="mb-3">
                                                        <input type="checkbox" class="form-check-input" id="IncompleteDocuments" onclick="this.checked=!this.checked;">
                                                        <label class="form-check-label" for="IncompleteDocuments">เอกสารไม่ครบ</label>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-inline align-items-center gap-2 ms-5">
                                                    <div clsss="mb-3">
                                                        <input type="checkbox" class="form-check-input" id="IncorrectDocuments" onclick="this.checked=!this.checked;">
                                                        <label class="form-check-label" for="IncorrectDocuments">เอกสารไม่ถูกต้อง</label>
                                                    </div>
                                                    <div clsss="mb-3">
                                                        <div class="d-flex flex-inline align-items-end">
                                                            <div style="width: 60px;">
                                                                <input type="checkbox" class="form-check-input" id="IncorrectDocuments" onclick="this.checked=!this.checked;">
                                                                <label class="form-check-label" for="IncorrectDocuments">อื่น ๆ</label>
                                                            </div>
                                                            <div class="text-center" style="width: 205px; border-bottom: 1px dotted #000;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <input type="checkbox" class="form-check-input" id="NotVerified" onclick="this.checked=!this.checked;">
                                            <label class="form-check-label" for="NotVerified">อนุญาต</label>
                                            <div class="mt-2 ms-5 d-flex flex-inline align-items-end">
                                                <div>ให้ใช้บัตรผ่านเลขที่</div>
                                                <div class="text-center" style="width: 305px; border-bottom: 1px dotted #000;"></div>
                                            </div>
                                            <div class="mt-2 ms-5 d-flex flex-inline align-items-end">
                                                <div>ออกเมื่อวันที่</div>
                                                <div class="text-center" style="width: 130px; border-bottom: 1px dotted #000;"></div>
                                                <div>หมดอายุวันที่</div>
                                                <div class="text-center" style="width: 130px; border-bottom: 1px dotted #000;"></div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Considering Result -->
                            <div class="mt-4 ms-5 d-flex flex-inline justify-content-between" style="width: 615px;">
                                <!-- Left Side -->
                                <div>
                                    <div class="d-flex flex-inline">
                                        <div>ลงชื่อ</div>
                                        <div class="text-center" style="width: 200px; border-bottom: 1px dotted #000;"></div>
                                        <div>ผู้ออกบัตร</div>
                                    </div>
                                    <div class="d-flex flex-inline">
                                        <div style="margin-left: 35px;">&#40;</div>
                                            <div class="text-center" style="width: 200px; border-bottom: 1px dotted #000;"></div>
                                        <div>&#41;</div>
                                    </div>
                                    <div style="margin-left: 70px;">หัวหน้างานปกครอง</div>
                                </div>
                                <!-- Right Side -->
                                <div>
                                    <div class="d-flex flex-inline">
                                        <div>ลงชื่อ</div>
                                        <div class="text-center" style="width: 200px; border-bottom: 1px dotted #000;"></div>
                                        <div>ผู้อนุญาต</div>
                                    </div>
                                    <div class="d-flex flex-inline">
                                        <div style="margin-left: 35px;">&#40;</div>
                                            <div class="text-center" style="width: 200px; border-bottom: 1px dotted #000;"></div>
                                        <div>&#41;</div>
                                    </div>
                                    <div class="text-center">รองผู้อำนวยการฝ่ายพัฒนากิจกรรม<br>นักเรียน นักศึกษา</div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                ?>
                
        </div>
    </div>

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

    <!-- HTML2PDF -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

	<script>

        var global_document_file_name = "";

        function previewPDF(filenameInput) {
            var element = document.getElementById("RegistrationForm");
            element.style.display = 'block';

            var opt = {
                margin:			0.5,
                filename:		filenameInput,
                image:			{ type: 'jpeg', quality: 0.98 },
                html2canvas:	{ scale: 2 },
                jsPDF:			{ unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).outputPdf('datauristring').then(function (pdfDataUri) {
                // [Preview file in new tab]
                // var newTab = window.open();
                // newTab.document.open();
                // newTab.document.write('<iframe src="' + pdfDataUri + '" width="100%" height="100%"></iframe>');
                // newTab.document.close();

                // [Download file in the same page]
                // สร้างลิงค์สำหรับดาวน์โหลด PDF
                var downloadLink = document.createElement('a');
                downloadLink.href = pdfDataUri;
                downloadLink.download = "แบบฟอร์มการขอนำยานพาหนะมาใช้ภายในวิทยาลัยเทคนิคสัตหีบ (ปค.16) [" + filenameInput + '].pdf';
                
                // คลิกลิงค์ดาวน์โหลด PDF
                downloadLink.click();

                // Change Page
                window.location = "registration-form-preview.php?ID=<?php echo $ID ?>";
            });
        }

        previewPDF("<?php echo $Fullname ?>");

    </script>

</body>
</html>