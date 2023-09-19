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

        ?>

        <div class="content">
            
            <!-- Title -->
            <div class="my-card">
                <div class="row align-items-center justify-content-between row-gap-3">
                    <div class="col-sm-3">
                        <h1 class="m-0 p-0 fs-5 fw-bold">ตั้งค่าระบบ</h1>
                    </div>
                    <div class="col-sm-9 text-end">
                        <a href="index.php" class="btn btn-maintheme">ย้อนกลับ</a>
                    </div>
                </div>
            </div>

            <div class="mt-4 my-card p-4">
                <!-- General Settings -->
                <div class="mb-4">
                    <h3 class="fw-bold">ตั้งค่าระบบ</h3>

                    <div class="mt-3 mx-5">
                        <table class="table table-stripped table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th class="fw-bold fs-5">หัวข้อ</th>
                                    <th class="fw-bold fs-5">คำอธิบาย</th>
                                    <th class="fw-bold fs-5 text-end">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Registration -->
                                <?php
                                    $sql = "SELECT * FROM system_settings WHERE system = 'vehicle_registration'";
                                    $data = SQLSelect($con, $sql);
                                ?>
                                <tr>
                                    <td class="fs-5">การลงทะเบียน</td>
                                    <td class="fs-6"><?php echo $data['comment'] ?></td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                            <input type="radio" class="btn-check" name="btnradio" id="registration_on" autocomplete="off" <?php echo ($data['is_enable'] == '1' ? "checked" : "") ?> onclick="Registration_Settings(1)">
                                            <label class="btn btn-outline-success fw-bold" for="registration_on">เปิด</label>

                                            <input type="radio" class="btn-check" name="btnradio" id="registration_off" autocomplete="off" <?php echo ($data['is_enable'] == '0' ? "checked" : "") ?> onclick="Registration_Settings(0)">
                                            <label class="btn btn-outline-danger fw-bold" for="registration_off">ปิด</label>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Max vehicle for registration -->
                                <?php
                                    $sql = "SELECT * FROM system_settings WHERE system = 'max_vehicle_registration'";
                                    $data = SQLSelect($con, $sql);
                                ?>
                                <tr>
                                    <td class="fs-5">จำนวนยานพาหนะสูงสุดที่อนุญาติให้ลงทะเบียน</td>
                                    <td class="fs-6"><?php echo $data['comment'] ?></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center justify-content-end column-gap-3">
                                            <div>
                                                <button type="button" class="btn btn-maintheme fw-bold" onclick="ChangeMaxVehicleSettings('-')">
                                                    <i class="bi bi-dash-circle-fill"></i>
                                                </button>
                                            </div>
                                            <div>
                                                <input type="text" id="MaxVehicle" value="<?php echo $data['value'] ?>" class="form-control bg-body-secondary text-center" readonly style="width: 70px;">
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-maintheme fw-bold" onclick="ChangeMaxVehicleSettings('+')">
                                                    <i class="bi bi-plus-circle-fill"></i>
                                                </button>
                                            </div>
                                            <div>
                                                <div id="maxVehicleBTN" style="display: none;">
                                                    <button type="button" class="mb-2 btn btn-danger" onclick="ChangeMaxVehicleSettings('cancel')">
                                                        ยกเลิก
                                                    </button>
                                                    <button type="button" class="btn btn-success" onclick="ChangeMaxVehicleSettings('confirm')">
                                                        บันทึก
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Registration -->
                                <tr>
                                    <td class="fs-5">...</td>
                                    <td class="fs-5">...</td>
                                    <td class="fs-5 text-end">...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>

                <!-- Lookup Table Management -->
                <div class="mb-4">
                    <h3 class="fw-bold">จัดการข้อมูลทั่วไป</h3>

                    <div class="mt-3 mx-5">
                        <table class="table table-stripped table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th class="fw-bold fs-5">หัวข้อ</th>
                                    <th class="fw-bold fs-5 text-end">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Prefix Table -->
                                <tr>
                                    <td class="fs-5">ข้อมูล คำนำหน้าชื่อ</td>
                                    <td class="fs-5 text-end">
                                        <button type="button" class="btn btn-maintheme" onclick="General_Settings_Show_Modal('คำนำหน้าชื่อ','prefix')">จัดการ</button>
                                    </td>
                                </tr>
                                <!-- Department Table -->
                                <tr>
                                    <td class="fs-5">ข้อมูล แผนกวิชา</td>
                                    <td class="fs-5 text-end">
                                        <button type="button" class="btn btn-maintheme" onclick="General_Settings_Show_Modal('แผนกวิชา','department')">จัดการ</button>
                                    </td>
                                </tr>
                                <!-- Department Table (Master Table But It's necessary) -->
                                <tr>
                                    <td class="fs-5">ข้อมูล สาขาวิชา</td>
                                    <td class="fs-5 text-end">
                                        <button type="button" class="btn btn-maintheme" onclick="General_Settings_Show_Modal('สาชาวิชา','major')">จัดการ</button>
                                    </td>
                                </tr>
                                <!-- Class Level Table -->
                                <tr>
                                    <td class="fs-5">ข้อมูล ระดับชั้น</td>
                                    <td class="fs-5 text-end">
                                        <button type="button" class="btn btn-maintheme" onclick="General_Settings_Show_Modal('ระดับขั้น','class_level')">จัดการ</button>
                                    </td>
                                </tr>
                                <!-- Vehicle Type Table -->
                                <tr>
                                    <td class="fs-5">ข้อมูล ประเภทยานพาหนะ</td>
                                    <td class="fs-5 text-end">
                                        <button type="button" class="btn btn-maintheme" onclick="General_Settings_Show_Modal('ประเภทยานพาหนะ','vehicle_type')">จัดการ</button>
                                    </td>
                                </tr>
                                <!-- Vehicle Brand Table -->
                                <tr>
                                    <td class="fs-5">ข้อมูล ยี่ห้อยานพาหนะ</td>
                                    <td class="fs-5 text-end">
                                        <button type="button" class="btn btn-maintheme" onclick="General_Settings_Show_Modal('ยี่ห้อยานพาหนะ','vehicle_brand')">จัดการ</button>
                                    </td>
                                </tr>
                                <!-- Color Table -->
                                <tr>
                                    <td class="fs-5">ข้อมูล สี (ยานพาหนะ)</td>
                                    <td class="fs-5 text-end">
                                        <button type="button" class="btn btn-maintheme" onclick="General_Settings_Show_Modal('สียานพาหนะ','color')">จัดการ</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Lookup Table Show Modal -->
    <div class="modal fade" id="LookupTableShow_Modal" tabindex="-1" aria-labelledby="LookupTableShow_Modal_Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bolder" id="LookupTableShow_Modal_Label">
                    <!-- Ajax Fetch Data -->
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-stripped align-middle table-bordered" id="LookupTableData">
                    <!-- Ajax Fetch Data -->
                    <!-- <thead>
                        <tr>
                            <th>#</th>
                            <th>ชื่อหัวข้อ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody> -->
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
            </div>
        </div>
    </div>

    <script>

        var global_max_vehicle_settings = false;
        var global_default_max_vehicle = document.getElementById("MaxVehicle").value;

        function General_Settings_Show_Modal(title, table) {

            var LookupTableData = document.getElementById("LookupTableData");
            var LookupTableShow_Modal_Label = document.getElementById("LookupTableShow_Modal_Label");
            LookupTableShow_Modal_Label.innerHTML = "";
            LookupTableData.innerHTML = "";

            $.ajax({
                url: '../ajax_db.php',
                method: 'POST',
                data: { 
                    SystemSettings: 'LookupTableData',
                    table: table
                },
                dataType: 'json',
                success: function(data) {
                    LookupTableShow_Modal_Label.innerHTML = "จัดการข้อมูล " + title;
                    LookupTableData.innerHTML = data;
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

            $("#LookupTableShow_Modal").modal('show');
        }

        // Vehicle Registration Settings
        function Registration_Settings(value) {
            var registration_on = document.getElementById("registration_on");
            var registration_off = document.getElementById("registration_off");

            var message = "";

            if (value == 1) {
                message = "เปิด การลงทะเบียนสำหรับผู้ใช้แล้ว";
            }
            else if (value == 0) {
                message = "ปิด การลงทะเบียนสำหรับผู้ใช้แล้ว"
            }

            $.ajax({
                    url: '../ajax_db.php',
                    method: 'POST',
                    data: { 
                        SystemSettings: 'Registration_Settings', 
                        value: value
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        Swal.fire({
                            icon: 'success',
                            title: 'ตั้งค่าระบบสำเร็จ',
                            text: message
                        }).then((result) => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
            });
        }

        // Max Vehicle Settings
        function ChangeMaxVehicleSettings(value) {

            var max_vehicle_settings_confirm_btn = document.getElementById("maxVehicleBTN");
            var max_vehicle_textbox = document.getElementById("MaxVehicle");

            if (!(global_max_vehicle_settings)) {
                global_max_vehicle_settings = true;
                max_vehicle_settings_confirm_btn.style.display = "block";
            }
            else if (value == "cancel") {
                global_max_vehicle_settings = false;
                max_vehicle_settings_confirm_btn.style.display = "none";
                max_vehicle_textbox.value = global_default_max_vehicle;
            }
            else if  (value == "confirm") {
                $.ajax({
                    url: '../ajax_db.php',
                    method: 'POST',
                    data: { 
                        SystemSettings: 'Max_Vehicle_Settings', 
                        value: max_vehicle_textbox.value
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        Swal.fire({
                            icon: 'success',
                            title: 'ตั้งค่าระบบสำเร็จ',
                            text: 'จำนวนยานพาหนะสูงสุดที่นำมาลงทะเบียนได้ คือ ' + max_vehicle_textbox.value
                        }).then((result) => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            // Add or Remove
            if (value == "+") {
                max_vehicle_textbox.value = parseInt(max_vehicle_textbox.value) + 1;
            }
            else if (value == "-") {
                if (parseInt(max_vehicle_textbox.value) != 1) {
                    max_vehicle_textbox.value = parseInt(max_vehicle_textbox.value) - 1;
                }
            }
        }

    </script>
    

</body>
</html>