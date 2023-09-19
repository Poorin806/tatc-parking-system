<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    //Logged in checking
    include_once('import/LoginChecking.php');

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

    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

</head>
<body class="main-bg">

    <div class="my-container">

        <?php
            // Form submit
            if (isset($_POST['Submit'])) {

                $UserID = $_SESSION['UserID'];

                // Get Server Settings for checking permissions
                $sql = "SELECT * FROM system_settings WHERE system = 'vehicle_registration'";
                $data = SQLSelect($con, $sql);
                $SystemSettings_isRegistrationEnable = $data['is_enable'];

                $sql = "SELECT * FROM system_settings WHERE system = 'max_vehicle_registration'";
                $data = SQLSelect($con, $sql);
                $SystemSettings_maxVehicle = $data['value'];

                // If System Enable
                if ($SystemSettings_isRegistrationEnable == '1') {
                    // Get Number of Vehicle registration of user
                    if ($UserType == "Student") {
                        $sql = "SELECT
                                    LastestStatus.regis_id,
                                    LastestStatus.lastest_regisStatus_id,
                                    registration_status.regisStatus_name,
                                    registration.std_id,
                                    registration.personnel_id
                                FROM
                                    registration_status_detail,
                                    (SELECT regis_id, MAX(regisStatus_id) AS lastest_regisStatus_id FROM registration_status_detail GROUP BY regis_id) AS LastestStatus
                                    
                                JOIN registration_status ON LastestStatus.lastest_regisStatus_id = registration_status.regisStatus_id
                                LEFT JOIN registration ON LastestStatus.regis_id = registration.regis_id
                                LEFT JOIN vehicle ON registration.vehicle_id = vehicle.vehicle_id
                                LEFT JOIN province ON vehicle.province_id = province.province_id
                                LEFT JOIN vehicle_type ON vehicle.vehiType_id = vehicle_type.vehiType_id
                                    
                                WHERE
                                    (registration_status_detail.regis_id = LastestStatus.regis_id) AND
                                    (registration_status_detail.regisStatus_id = LastestStatus.lastest_regisStatus_id) AND
                                    (LastestStatus.lastest_regisStatus_id NOT IN (6, 7, 8, 9)) AND
                                    (registration.std_id = '$UserID')
                                ORDER BY LastestStatus.regis_id DESC;
                        ";
                    }
                    else {
                        $sql = "SELECT
                                    LastestStatus.regis_id,
                                    LastestStatus.lastest_regisStatus_id,
                                    registration_status.regisStatus_name,
                                    registration.std_id,
                                    registration.personnel_id
                                FROM
                                    registration_status_detail,
                                    (SELECT regis_id, MAX(regisStatus_id) AS lastest_regisStatus_id FROM registration_status_detail GROUP BY regis_id) AS LastestStatus
                                    
                                JOIN registration_status ON LastestStatus.lastest_regisStatus_id = registration_status.regisStatus_id
                                LEFT JOIN registration ON LastestStatus.regis_id = registration.regis_id
                                LEFT JOIN vehicle ON registration.vehicle_id = vehicle.vehicle_id
                                LEFT JOIN province ON vehicle.province_id = province.province_id
                                LEFT JOIN vehicle_type ON vehicle.vehiType_id = vehicle_type.vehiType_id
                                    
                                WHERE
                                    (registration_status_detail.regis_id = LastestStatus.regis_id) AND
                                    (registration_status_detail.regisStatus_id = LastestStatus.lastest_regisStatus_id) AND
                                    (LastestStatus.lastest_regisStatus_id NOT IN (6, 7, 8, 9)) AND
                                    (registration.personnel_id = '$UserID')
                                ORDER BY LastestStatus.regis_id DESC;
                        ";
                    }

                    // Checking User number of registration and System Settings (Max vehicle number)
                    $user_total_vehicle_registration = SQLSelectNumRow($con, $sql);
                    if ($user_total_vehicle_registration >= $SystemSettings_maxVehicle) {
                        ?>
                        <script>
                            swal.fire({
                                icon: 'error',
                                title: 'ไม่สามารถลงทะเบียนได้',
                                text: 'ระบบจำกัดการลงทะเบียนได้สูงสุด <?php echo $SystemSettings_maxVehicle ?> คัน'
                            });
                        </script>
                        <?php
                    }
                    else {
                        echo "<script>console.log('Total User Vehicle = $user_total_vehicle_registration, System Max = $SystemSettings_maxVehicle')</script>";
                        // Continue Progress
                        if ($UserType == "Student") {
                            $Address = $_POST['Address'];
                            $ClassLevel = $_POST['ClassLevel'];
                            $Department = $_POST['Department'];
                            $Major = $_POST['Major'];
                            $Province = $_POST['Province'];
                            $Distict = $_POST['District'];
                            $SubDistict = $_POST['SubDistrict'];
                        }
                        
                        $Fullname = $_POST['Fullname'];
                        $Phone = $_POST['Phone'];

                        $VehicleType = $_POST['VehicleType'];
                        $VehicleID = $_POST['VehicleID'];
                        $Brand = $_POST['Brand'];
                        $Color = $_POST['Color'];
                        $VehicleModel = $_POST['VehicleModel'];
                        $VehicleProvince = $_POST['VehicleProvince'];

                        if ($_GET['VehicleType'] == "Car") {
                            $VehicleType = 1;
                        }
                        else {
                            $VehicleType = 2;
                        }

                        //Checking If this registration is already registered or progressing
                        $sql = "SELECT vehicle_id FROM vehicle WHERE vehicle_id = '$VehicleID'";
                        $IsThereThisVehicleInTheDatabase = SQLSelectNumRow($con, $sql);
                        if ($IsThereThisVehicleInTheDatabase == 0) {
                            $CopyOfVehicle = $_FILES['CopyOfVehicle'];   //Input type file
                            $CopyOfOwnerIDCard = $_FILES['CopyOfOwnerIDCard'];     //Input type file
                            $CopyOfUserIDCard = $_FILES['CopyOfUserIDCard'];   //Input type file
            
                            $PictureVehicleFront = $_FILES['PictureVehicleFront'];  //Input type file
                            $PictureVehicleSide = $_FILES['PictureVehicleSide'];    //Input type file
                            $PictureVehicleBack = $_FILES['PictureVehicleBack'];    //Input type file
            
                            // Insert new Vehicle
                            $sql = "INSERT INTO vehicle VALUES ('$VehicleID', '$VehicleProvince', '$Color', '$VehicleType', '$Brand', 1, '$VehicleModel')";
                            SQLQuery($con, $sql);
            
                            //Insert to registartion
                            //------- Copy of vehicle -------
                            // รับข้อมูลของไฟล์
                            $file = $_FILES['CopyOfVehicle'];
                            $fileName = $file['name'];
                            $fileTmpName = $file['tmp_name'];
            
                            // เปิดไฟล์เพื่ออ่านข้อมูล
                            $fileData = fopen($fileTmpName, 'rb');
                            $fileContents = fread($fileData, filesize($fileTmpName));
                            fclose($fileData);
            
                            // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                            $CopyOfVehicle_filename = $con->real_escape_string($fileName);
                            $CopyOfVehicle_file = $con->real_escape_string($fileContents);
            
                            //------- Copy of ID Card (Owner) -------
                            // รับข้อมูลของไฟล์
                            $file = $_FILES['CopyOfOwnerIDCard'];
                            $fileName = $file['name'];
                            $fileTmpName = $file['tmp_name'];
            
                            // เปิดไฟล์เพื่ออ่านข้อมูล
                            $fileData = fopen($fileTmpName, 'rb');
                            $fileContents = fread($fileData, filesize($fileTmpName));
                            fclose($fileData);
            
                            // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                            $CopyOfOwnerIDCard_filename = $con->real_escape_string($fileName);
                            $CopyOfOwnerIDCard_file = $con->real_escape_string($fileContents);
            
                            //------- Copy of ID Card (User) [Old = Stdcard] -------
                            // รับข้อมูลของไฟล์
                            $file = $_FILES['CopyOfUserIDCard'];
                            $fileName = $file['name'];
                            $fileTmpName = $file['tmp_name'];
            
                            // เปิดไฟล์เพื่ออ่านข้อมูล
                            $fileData = fopen($fileTmpName, 'rb');
                            $fileContents = fread($fileData, filesize($fileTmpName));
                            fclose($fileData);
            
                            // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                            $CopyOfUserIDCard_filename = $con->real_escape_string($fileName);
                            $CopyOfUserIDCard_file = $con->real_escape_string($fileContents);
            
                            //------- Picture of Vehicle Front -------
                            // รับข้อมูลของไฟล์
                            $file = $_FILES['PictureVehicleFront'];
                            $fileName = $file['name'];
                            $fileTmpName = $file['tmp_name'];
            
                            // เปิดไฟล์เพื่ออ่านข้อมูล
                            $fileData = fopen($fileTmpName, 'rb');
                            $fileContents = fread($fileData, filesize($fileTmpName));
                            fclose($fileData);
            
                            // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                            $PictureVehicleFront_filename = $con->real_escape_string($fileName);
                            $PictureVehicleFront_file = $con->real_escape_string($fileContents);
            
                            //------- Picture of Vehicle Side -------
                            // รับข้อมูลของไฟล์
                            $file = $_FILES['PictureVehicleSide'];
                            $fileName = $file['name'];
                            $fileTmpName = $file['tmp_name'];
            
                            // เปิดไฟล์เพื่ออ่านข้อมูล
                            $fileData = fopen($fileTmpName, 'rb');
                            $fileContents = fread($fileData, filesize($fileTmpName));
                            fclose($fileData);
            
                            // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                            $PictureVehicleSide_filename = $con->real_escape_string($fileName);
                            $PictureVehicleSide_file = $con->real_escape_string($fileContents);
            
                            //------- Picture of Vehicle Back -------
                            // รับข้อมูลของไฟล์
                            $file = $_FILES['PictureVehicleBack'];
                            $fileName = $file['name'];
                            $fileTmpName = $file['tmp_name'];
            
                            // เปิดไฟล์เพื่ออ่านข้อมูล
                            $fileData = fopen($fileTmpName, 'rb');
                            $fileContents = fread($fileData, filesize($fileTmpName));
                            fclose($fileData);
            
                            // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                            $PictureVehicleBack_filename = $con->real_escape_string($fileName);
                            $PictureVehicleBack_file = $con->real_escape_string($fileContents);
            
                            $UserID = $_SESSION['UserID'];
            
                            if ($UserType == "Student") {
                                $sql = "INSERT INTO registration VALUES (null, '$UserID', null, '$VehicleID', '$CopyOfVehicle_file', '$CopyOfOwnerIDCard_file', '$CopyOfUserIDCard_file', '$PictureVehicleFront_file', '$PictureVehicleSide_file', '$PictureVehicleBack_file', null, '$CopyOfVehicle_filename', '$CopyOfOwnerIDCard_filename', '$CopyOfUserIDCard_filename', '$PictureVehicleFront_filename', '$PictureVehicleSide_filename', '$PictureVehicleBack_filename', null)";
                            }
                            else {
                                $sql = "INSERT INTO registration VALUES (null, null, $UserID, '$VehicleID', '$CopyOfVehicle_file', '$CopyOfOwnerIDCard_file', '$CopyOfUserIDCard_file', '$PictureVehicleFront_file', '$PictureVehicleSide_file', '$PictureVehicleBack_file', null, '$CopyOfVehicle_filename', '$CopyOfOwnerIDCard_filename', '$CopyOfUserIDCard_filename', '$PictureVehicleFront_filename', '$PictureVehicleSide_filename', '$PictureVehicleBack_filename', null)";
                            }
            
                            SQLQuery($con, $sql);
            
                            //Update Log (Registation Status Details)
                            if ($UserType == "Student") {
                                $sql = "SELECT MAX(regis_id) AS regis_id FROM registration WHERE std_id = '$UserID'";   //Get Current Registration ID (Student)
                            }
                            else {
                                $sql = "SELECT MAX(regis_id) AS regis_id FROM registration WHERE personnel_id = '$UserID'";   //Get Current Registration ID (Personnel)
                            }
                            $CurrentRegistrationID_temp = SQLSelect($con, $sql);  //Get Current Registration ID
                            $CurrentRegistrationID = $CurrentRegistrationID_temp['regis_id'];   //Get Current Registration ID
            
                            $CurrentDate = date("Y-m-d");
                            $CurrentTime = date("H:i:s");
            
                            $sql = "INSERT INTO registration_status_detail VALUES ($CurrentRegistrationID, 1, null, '$CurrentDate', '$CurrentTime', null)";
                            SQLQuery($con, $sql);
            
                            //Alert
                            echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลงทะเบียนสำเร็จ',
                                    confirmButtonText: 'ตกลง'
                                }).then((result) => {
                                    window.location='status-detail.php?ID=$CurrentRegistrationID'
                                });
                            </script>";
                        }
                        else {
                            // If this registration is already have in progress (Database)

                            function RegistrationContinue() {

                                global $con;
                                global $UserType;
                                global $VehicleID;

                                $CopyOfVehicle = $_FILES['CopyOfVehicle'];   //Input type file
                                $CopyOfOwnerIDCard = $_FILES['CopyOfOwnerIDCard'];     //Input type file
                                $CopyOfUserIDCard = $_FILES['CopyOfUserIDCard'];   //Input type file
                
                                $PictureVehicleFront = $_FILES['PictureVehicleFront'];  //Input type file
                                $PictureVehicleSide = $_FILES['PictureVehicleSide'];    //Input type file
                                $PictureVehicleBack = $_FILES['PictureVehicleBack'];    //Input type file
                
                                //Insert to registartion
                                //------- Copy of vehicle -------
                                // รับข้อมูลของไฟล์
                                $file = $_FILES['CopyOfVehicle'];
                                $fileName = $file['name'];
                                $fileTmpName = $file['tmp_name'];
                
                                // เปิดไฟล์เพื่ออ่านข้อมูล
                                $fileData = fopen($fileTmpName, 'rb');
                                $fileContents = fread($fileData, filesize($fileTmpName));
                                fclose($fileData);
                
                                // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                                $CopyOfVehicle_filename = $con->real_escape_string($fileName);
                                $CopyOfVehicle_file = $con->real_escape_string($fileContents);
                
                                //------- Copy of ID Card (Owner) -------
                                // รับข้อมูลของไฟล์
                                $file = $_FILES['CopyOfOwnerIDCard'];
                                $fileName = $file['name'];
                                $fileTmpName = $file['tmp_name'];
                
                                // เปิดไฟล์เพื่ออ่านข้อมูล
                                $fileData = fopen($fileTmpName, 'rb');
                                $fileContents = fread($fileData, filesize($fileTmpName));
                                fclose($fileData);
                
                                // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                                $CopyOfOwnerIDCard_filename = $con->real_escape_string($fileName);
                                $CopyOfOwnerIDCard_file = $con->real_escape_string($fileContents);
                
                                //------- Copy of ID Card (User) [Old = Stdcard] -------
                                // รับข้อมูลของไฟล์
                                $file = $_FILES['CopyOfUserIDCard'];
                                $fileName = $file['name'];
                                $fileTmpName = $file['tmp_name'];
                
                                // เปิดไฟล์เพื่ออ่านข้อมูล
                                $fileData = fopen($fileTmpName, 'rb');
                                $fileContents = fread($fileData, filesize($fileTmpName));
                                fclose($fileData);
                
                                // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                                $CopyOfUserIDCard_filename = $con->real_escape_string($fileName);
                                $CopyOfUserIDCard_file = $con->real_escape_string($fileContents);
                
                                //------- Picture of Vehicle Front -------
                                // รับข้อมูลของไฟล์
                                $file = $_FILES['PictureVehicleFront'];
                                $fileName = $file['name'];
                                $fileTmpName = $file['tmp_name'];
                
                                // เปิดไฟล์เพื่ออ่านข้อมูล
                                $fileData = fopen($fileTmpName, 'rb');
                                $fileContents = fread($fileData, filesize($fileTmpName));
                                fclose($fileData);
                
                                // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                                $PictureVehicleFront_filename = $con->real_escape_string($fileName);
                                $PictureVehicleFront_file = $con->real_escape_string($fileContents);
                
                                //------- Picture of Vehicle Side -------
                                // รับข้อมูลของไฟล์
                                $file = $_FILES['PictureVehicleSide'];
                                $fileName = $file['name'];
                                $fileTmpName = $file['tmp_name'];
                
                                // เปิดไฟล์เพื่ออ่านข้อมูล
                                $fileData = fopen($fileTmpName, 'rb');
                                $fileContents = fread($fileData, filesize($fileTmpName));
                                fclose($fileData);
                
                                // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                                $PictureVehicleSide_filename = $con->real_escape_string($fileName);
                                $PictureVehicleSide_file = $con->real_escape_string($fileContents);
                
                                //------- Picture of Vehicle Back -------
                                // รับข้อมูลของไฟล์
                                $file = $_FILES['PictureVehicleBack'];
                                $fileName = $file['name'];
                                $fileTmpName = $file['tmp_name'];
                
                                // เปิดไฟล์เพื่ออ่านข้อมูล
                                $fileData = fopen($fileTmpName, 'rb');
                                $fileContents = fread($fileData, filesize($fileTmpName));
                                fclose($fileData);
                
                                // ทำการ escape ข้อมูลเพื่อป้องกันการทำให้ข้อมูลส่งผลกระทบกับคำสั่ง SQL
                                $PictureVehicleBack_filename = $con->real_escape_string($fileName);
                                $PictureVehicleBack_file = $con->real_escape_string($fileContents);
                
                                $UserID = $_SESSION['UserID'];
                
                                if ($UserType == "Student") {
                                    $sql = "INSERT INTO registration VALUES (null, '$UserID', null, '$VehicleID', '$CopyOfVehicle_file', '$CopyOfOwnerIDCard_file', '$CopyOfUserIDCard_file', '$PictureVehicleFront_file', '$PictureVehicleSide_file', '$PictureVehicleBack_file', null, '$CopyOfVehicle_filename', '$CopyOfOwnerIDCard_filename', '$CopyOfUserIDCard_filename', '$PictureVehicleFront_filename', '$PictureVehicleSide_filename', '$PictureVehicleBack_filename', null)";
                                }
                                else {
                                    $sql = "INSERT INTO registration VALUES (null, null, $UserID, '$VehicleID', '$CopyOfVehicle_file', '$CopyOfOwnerIDCard_file', '$CopyOfUserIDCard_file', '$PictureVehicleFront_file', '$PictureVehicleSide_file', '$PictureVehicleBack_file', null, '$CopyOfVehicle_filename', '$CopyOfOwnerIDCard_filename', '$CopyOfUserIDCard_filename', '$PictureVehicleFront_filename', '$PictureVehicleSide_filename', '$PictureVehicleBack_filename', null)";
                                }
                
                                SQLQuery($con, $sql);
                
                                //Update Log (Registation Status Details)
                                if ($UserType == "Student") {
                                    $sql = "SELECT MAX(regis_id) AS regis_id FROM registration WHERE std_id = '$UserID'";   //Get Current Registration ID (Student)
                                }
                                else {
                                    $sql = "SELECT MAX(regis_id) AS regis_id FROM registration WHERE personnel_id = '$UserID'";   //Get Current Registration ID (Personnel)
                                }
                                $CurrentRegistrationID_temp = SQLSelect($con, $sql);  //Get Current Registration ID
                                $CurrentRegistrationID = $CurrentRegistrationID_temp['regis_id'];   //Get Current Registration ID
                
                                $CurrentDate = date("Y-m-d");
                                $CurrentTime = date("H:i:s");
                
                                $sql = "INSERT INTO registration_status_detail VALUES ($CurrentRegistrationID, 1, null, '$CurrentDate', '$CurrentTime', null)";
                                SQLQuery($con, $sql);
                
                                //Alert
                                echo "<script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'ลงทะเบียนสำเร็จ',
                                        confirmButtonText: 'ตกลง'
                                    }).then((result) => {
                                        window.location='status-detail.php?ID=$CurrentRegistrationID'
                                    });
                                </script>";
                            }

                            // Get Lastest Registration 
                            $sql_getLastestRegistration = "SELECT registration.regis_id 
                                    FROM registration 
                                    WHERE vehicle_id = '$VehicleID'
                                    ORDER BY regis_id DESC
                                    LIMIT 1
                            ";
                            $LastestRegistration = SQLSelect($con, $sql_getLastestRegistration);
                            $LastestRegistration_regis_id = $LastestRegistration['regis_id'];

                            // Get Lastest Registration Status Details
                            $sql_getLastestRegistrationDetail = "SELECT * FROM registration_status_detail
                                    WHERE regis_id = '$LastestRegistration_regis_id'
                            ";
                            $LastestRegistrationDetails_regisStatus_id = [];
                            foreach (SQLSelectLoop($con, $sql_getLastestRegistrationDetail) as $data) {
                                $LastestRegistrationDetails_regisStatus_id[] = $data['regisStatus_id'];
                            }

                            // Checking Lastest Registration Details (Status)
                            // If Status 5 (Registration Successfully)
                            if (in_array(5, $LastestRegistrationDetails_regisStatus_id)) {
                                $sql_getParkingCard = "SELECT * FROM parking_card
                                                        WHERE 
                                                            (parking_card.regis_id = '$LastestRegistration_regis_id') AND
                                                            (parking_card.issue_date <= parking_card.expiration_date)
                                ";
                                // If This Parking Card Registration is not expired
                                if (SQLSelectNumRow($con, $sql_getParkingCard) > 0) {
                                    // Failed Alert
                                    echo "<script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'เกิดข้อผิดพลาดในการลงทะเบียน',
                                            text: 'รถทะเบียนนี้อาจมีข้อมูลการลงทะเบียนอยู่แล้ว หรืออยู่ระหว่างการดำเนินการ...',
                                            confirmButtonText: 'ตกลง'
                                        })
                                    </script>";
                                    echo "<script>console.log('This vehicle and registration are successful and it has not expired');</script>";
                                }
                                // This Parknig card is expired
                                else {
                                    // Continue to registration progress
                                    echo "<script>console.log(`Continue Registration progress`);</script>";
                                    RegistrationContinue();
                                }
                            }
                            // If Status 6-9 (Registration Failed)
                            else {
                                $isRegistartionHasError = false;
                                for ($i = 0; $i < count($LastestRegistrationDetails_regisStatus_id); $i++) {
                                    switch($LastestRegistrationDetails_regisStatus_id[$i]) {
                                        case 6:
                                        case 7:
                                        case 8:
                                        case 9:
                                            $isRegistartionHasError = true;
                                            break 2;
                                    }
                                }
                                
                                // If this registration has failed
                                if ($isRegistartionHasError) {
                                    // Continue to registration progress
                                    echo "<script>console.log(`Continue Registration progress`);</script>";
                                    RegistrationContinue();
                                }
                                // If this registration is not failed
                                else {
                                    // Checking if this registration is in progress (1-3)
                                    $isRegistartionInProgress = false;
                                    for ($i = 0; $i < count($LastestRegistrationDetails_regisStatus_id); $i++) {
                                        switch($LastestRegistrationDetails_regisStatus_id[$i]) {
                                            case 1:
                                            case 2:
                                            case 3:
                                                $isRegistartionInProgress = true;
                                                break 2;
                                        }
                                    }

                                    // If this registration is currently in progress
                                    if ($isRegistartionInProgress) {
                                        // Failed Alert
                                        echo "<script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'เกิดข้อผิดพลาดในการลงทะเบียน',
                                            text: 'รถทะเบียนนี้อาจมีข้อมูลการลงทะเบียนอยู่แล้ว หรืออยู่ระหว่างการดำเนินการ...',
                                            confirmButtonText: 'ตกลง'
                                        })
                                    </script>";
                                    echo "<script>console.log('This registration is currently in progress');</script>";
                                    }
                                    // If this registration is not in progress
                                    else {
                                        // Continue to registration progress
                                        echo "<script>console.log(`Continue Registration progress`);</script>";
                                        RegistrationContinue();
                                    }
                                }
                            }
                        }
                    }
                }
                // If System Disable
                else {
                    ?>
                    <script>
                        swal.fire({
                            icon: 'error',
                            title: 'ไม่สามารถลงทะเบียนได้',
                            text: 'ระบบการลงทะเบียนจอดรถฯ ไม่ได้เปิดอยู่ ณ ขณะนี้'
                        });
                    </script>
                    <?php
                }
                
            }

            //Navbar
            include_once 'import/navbar.php';

            //Sidebar
            include_once 'import/sidebar.php';

            // Essentials JavaScript
            include_once 'import/js.php';
        ?>

        <div class="content">

            <!-- Title -->
            <div class="my-card">
                <?php
                    if (isset($_GET['VehicleType'])) {
                        if ($_GET['VehicleType'] == "Car") {
                            $VehicleType = "รถยนต์";
                        }
                        else {
                            $VehicleType = "รถจักรยานยนต์";
                        }
                    }
                    else {
                        echo "<script>window.location='registration.php'</script>";
                    }
                ?>
                <p class="fs-5 fw-bold">การลงทะเบียน > <?php echo $VehicleType  ?></p>
            </div>

            <!-- content -->
            <div class="mt-4 my-card p-4">
                <form id="registration" action="<?php echo $_SERVER['PHP_SELF'] . "?VehicleType=" . $_GET['VehicleType']; ?>" method="POST" enctype="multipart/form-data">
                    <?php

                        if ($UserType == "Student") {
                            $sql = "SELECT *
                                FROM student, class_level, major, department, province, district, subdistrict, prefix
                                WHERE 
                                    student.prefix_id = prefix.prefix_id AND
                                    student.level_id = class_level.level_id AND
                                    student.dept_id = department.dept_id AND
                                    student.major_id = major.major_id AND
                                    student.province_id = province.province_id AND
                                    student.district_id = district.district_id AND
                                    student.subdistrict_id = subdistrict.subDistrict_id AND
                                    student.std_id = '" . $_SESSION['UserID'] . "'";

                            $UserData = SQLSelect($con, $sql);

                            ?>
                            <!-- Card 1 - Personal Information -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="card-title fw-bold mb-4 fs-5">ส่วนที่ 1 ข้อมูลส่วนตัว</div>
                                    <div class="row align-items-start gx-5 gy-3">
                                        <!-- Left Side -->
                                        <div class="col-md-6 col-sm-12 col-12">
                                            <!-- Fullname -->
                                            <div class="mb-3">
                                                <label class="form-label">ชื่อ-นามสกุล</label>
                                                <input type="text" name="Fullname" class="form-control bg-body-secondary" required value="<?php echo $UserData['prefix_name'] . $UserData['name'] . " " . $UserData['lastname'] ?>" readonly>
                                            </div>

                                            <!-- Class Level -->
                                            <div class="mb-3">
                                                <label class="form-label">ระดับชั้น</label>
                                                <input type="text" name="ClassLevel" class="form-control bg-body-secondary" required value="<?php echo $UserData['level_name'] ?>" readonly>
                                            </div>

                                            <!-- Address -->
                                            <div class="mb-3">
                                                <label class="form-label">ที่อยู่</label>
                                                <input type="text" name="Address" class="form-control bg-body-secondary" required value="<?php echo $UserData['address'] ?>" readonly>
                                            </div>

                                            <!-- Phone -->
                                            <div class="mb-3">
                                                <label class="form-label">เบอร์โทรศัพท์</label>
                                                <input type="text" name="Phone" class="form-control bg-body-secondary" required value="<?php echo $UserData['tel'] ?>" readonly>
                                            </div>
                                        </div>
                                        <!-- Right Side -->
                                        <div class="col-md-6 col-sm-12 col-12">
                                            <!-- Department -->
                                            <div class="mb-3">
                                                <label class="form-label">แผนกวิชา</label>
                                                <input type="text" name="Department" class="form-control bg-body-secondary" required value="<?php echo $UserData['dept_name'] ?>" readonly>
                                            </div>

                                            <!-- Major -->
                                            <div class="mb-3">
                                                <label class="form-label">สาขาวิชา</label>
                                                <input type="text" name="Major" class="form-control bg-body-secondary" required value="<?php echo $UserData['major_name'] ?>" readonly>
                                            </div>

                                            <!-- Province / District / Sub District -->
                                            <div class="row align-items-center mb-3">
                                                <div class="col-md-4 col-12">
                                                    <label class="form-label">จังหวัด</label>
                                                    <input type="text" name="Province" class="form-control bg-body-secondary" required value="<?php echo $UserData['province_name'] ?>" readonly>
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <label class="form-label">อำเภอ</label>
                                                    <input type="text" name="District" class="form-control bg-body-secondary" required value="<?php echo $UserData['district_name_th'] ?>" readonly>
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <label class="form-label">ตำบล</label>
                                                    <input type="text" name="SubDistrict" class="form-control bg-body-secondary" required value="<?php echo $UserData['subDistrictName_th'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        else {
                            $sql = "SELECT *
                                    FROM personnel
                                    WHERE
                                        (personnel.personnel_id = $UserID)
                            ";
                            $UserData = SQLSelect($con, $sql);
                            ?>
                            <!-- Card 1 - Personal Information -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="card-title fw-bold mb-4 fs-5">ส่วนที่ 1 ข้อมูลส่วนตัว</div>
                                    <div class="row align-items-start gx-5 gy-3">
                                        <!-- Fullname -->
                                        <div class="mb-3 col-md-6 col-sm-12 col-12">
                                            <label class="form-label">ชื่อ-นามสกุล</label>
                                            <input type="text" name="Fullname" class="form-control bg-body-secondary" required value="<?php
                                                if ($_SESSION['UserType'] == "Student") {
                                                    echo $UserData['prefix_name'] . $UserData['name'] . " " . $UserData['lastname'];
                                                }
                                                else {
                                                    echo $UserData['name'] . " " . $UserData['lastname'];
                                                }
                                            ?>" readonly>
                                        </div>

                                        <!-- Phone -->
                                        <div class="mb-3 col-md-6 col-sm-12 col-12">
                                            <label class="form-label">เบอร์โทรศัพท์</label>
                                            <input type="text" name="Phone" class="form-control bg-body-secondary" required value="<?php echo $UserData['tel'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    
                    ?>

                    <!-- Card 2 - Vehicle Information -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="card-title fw-bold mb-4 fs-5">ส่วนที่ 2 ยานพาหนะ</div>
                            <input type="text" name="VehicleType" class="form-control" value="<?php echo $VehicleType ?>" required hidden>
                            <div class="row align-items-start gx-5 gy-3">
                                <!-- Left Side -->
                                <div class="col-md-6 col-sm-12 col-12">
                                    <!-- Vehicle ID -->
                                    <div class="mb-3">
                                        <label class="form-label">หมายเลขทะเบียนรถ</label>
                                        <input type="text" name="VehicleID" id="VehicleID" class="form-control" required>
                                    </div>

                                    <!-- Vehicle Brand -->
                                    <div class="mb-3">
                                        <label class="form-label">ยี่ห้อ</label>
                                        <select class="form-select" name="Brand" id="Brand" required>
                                            <option selected>-- ยี่ห้อ --</option>
                                            <?php
                                                $sql = "SELECT * FROM vehicle_brand";
                                                foreach (SQLSelectLoop($con, $sql) as $row) {
                                                    ?>
                                                        <option value="<?php echo $row['vehiBrand_id'] ?>"><?php echo $row['vehiBrand_name'] ?></option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Vehicle Color -->
                                    <div class="mb-3">
                                        <label class="form-label">สียานพาหนะ</label>
                                        <select class="form-select" name="Color" id="Color" required>
                                            <option selected>-- สี --</option>
                                            <?php
                                                $sql = "SELECT * FROM color";
                                                foreach (SQLSelectLoop($con, $sql) as $row) {
                                                    ?>
                                                        <option value="<?php echo $row['color_id'] ?>"><?php echo $row['color_name'] ?></option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Right Side -->
                                <div class="col-md-6 col-sm-12 col-12">
                                    <!-- Vehicle Province -->
                                    <div class="mb-3">
                                        <label class="form-label">จังหวัด</label>
                                        <select class="form-select" name="VehicleProvince" id="VehicleProvince" required>
                                            <option selected>-- จังหวัด --</option>
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

                                    <!-- Vehicle Model -->
                                    <div class="mb-3">
                                        <label class="form-label">รุ่น</label>
                                        <input type="text" name="VehicleModel" id="VehicleModel" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload File -->
                            <hr class="text-secondary">

                            <p class="m-0 p-0 fw-bold fs-5 mb-3">การอัพโหลดไฟล์</p>
                            <div class="row align-items-center gx-5 gy-3 mb-3">
                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label">รูปถ่าย สำเนาทะเบียนยานพาหนะ</label>
                                    <input type="file" name="CopyOfVehicle" id="CopyOfVehicle" class="form-control" id="CopyOfVehicle" accept="image/*" required>
                                </div>
                                <div class="col-md-6 col-12 col-6 mb-3">
                                    <label class="form-label">รูปถ่าย สำเนาบัตรประชาชน (เจ้าของยานพาหนะ)</label>
                                    <input type="file" name="CopyOfOwnerIDCard" id="CopyOfOwnerIDCard" class="form-control" id="CopyOfOwnerIDCard" accept="image/*" required>
                                </div>
                                <div class="col-md-6 col-12 col-6 mb-3">
                                    <label class="form-label">รูปถ่าย สำเนาบัตรประชาชน (ผู้ลงทะเบียนฯ)</label>
                                    <input type="file" name="CopyOfUserIDCard" id="CopyOfUserIDCard" class="form-control" id="CopyOfUserIDCard" accept="image/*" required>
                                </div>
                            </div>

                            <!-- Vehicle Pictures -->
                            <hr class="text-secondary">

                            <p class="m-0 p-0 fw-bold fs-5 mb-3">การตรวจสภาพรถ</p>
                            <div class="row align-items-center gx-5 gy-3 mb-3">
                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label">รูปถ่ายรถ ด้านหน้า</label>
                                    <input type="file" name="PictureVehicleFront" id="PictureVehicleFront" class="form-control" id="PictureVehicleFront" accept="image/*" required>
                                </div>
                                <div class="col-md-6 col-12 col-6 mb-3">
                                    <label class="form-label">รูปถ่ายรถ ด้านข้าง</label>
                                    <input type="file" name="PictureVehicleSide" id="PictureVehicleSide" class="form-control" id="PictureVehicleSide" accept="image/*" required>
                                </div>
                                <div class="col-md-6 col-12 col-6 mb-3">
                                    <label class="form-label">รูปถ่ายรถ ด้านหลัง</label>
                                    <input type="file" name="PictureVehicleBack" id="PictureVehicleBack" class="form-control" id="PictureVehicleBack" accept="image/*" required>
                                </div>
                            </div>
                            <div class="alert alert-warning" role="alert">
                                การถ่ายภาพรถควรถ่ายภาพให้ชัดเจน สามารถดูตัวอย่างวิธีการถ่ายภาพได้ <button type="button" class="text-decoration-none btn text-primary fw-bolder" onclick="showModal()">คลิกที่นี่</button>
                            </div>
                        </div>
                    </div>

                    <!-- Button Zone -->
                    <div class="mb-2 text-end">
                        <!-- <input type="submit" value="ยืนยัน" name="Submit" class="btn btn-primary"> -->
                        <input type="text" name="Submit" value="Submit" hidden>
                        <a href="#" class="btn btn-primary" onclick="FormSubmit()">ยืนยัน</a>
                        <a href="registration.php" class="btn btn-danger">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Picture Modal -->
    <div class="modal fade" id="VehiclePictureModal" tabindex="-1" aria-labelledby="VehiclePictureModal_Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4 fw-bolder" id="VehiclePictureModal_Label">ตัวอย่างการถ่ายรูปรถ</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- MotorBike -->
                    <div class="mx-3">
                        <h5 class="fw-bold">การถ่ายรูป - รถจักยานยนต์</h5>
                        <div class="d-flex flex-column align-items-center row-gap-5">
                            <div class="d-flex flex-column align-items-center">
                                <img src="image/motorbike_front.jpg" class="img-fluid rounded img-zoom-hover bg-body-secondary" style="max-width: 250px;">
                                รูปถ่ายด้านหน้า
                            </div>
                            <div class="d-flex flex-column align-items-center">
                                <img src="image/motorbike_side.jpg" class="img-fluid rounded img-zoom-hover bg-body-secondary" style="max-width: 250px;">
                                รูปถ่ายด้านข้าง
                            </div>
                            <div class="d-flex flex-column align-items-center">
                                <img src="image/motorbike_back.png" class="img-fluid rounded img-zoom-hover bg-body-secondary" style="max-width: 250px;">
                                รูปถ่ายด้านหลัง
                            </div>
                        </div>
                    </div>

                    <!-- Car -->
                    <div class="mt-3 mx-3">
                        <h5 class="fw-bold">การถ่ายรูป - รถยนต์</h5>
                        <div class="d-flex flex-column align-items-center row-gap-5">
                            <div class="d-flex flex-column align-items-center">
                                <img src="image/car_front.png" class="img-fluid rounded img-zoom-hover bg-body-secondary" style="max-width: 250px;">
                                รูปถ่ายด้านหน้า
                            </div>
                            <div class="d-flex flex-column align-items-center">
                                <img src="image/car_side.jpg" class="img-fluid rounded img-zoom-hover bg-body-secondary" style="max-width: 250px;">
                                รูปถ่ายด้านข้าง
                            </div>
                            <div class="d-flex flex-column align-items-center">
                                <img src="image/car_back.png" class="img-fluid rounded img-zoom-hover bg-body-secondary" style="max-width: 250px;">
                                รูปถ่ายด้านหลัง
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>


    <script>

        function showModal() {
            $("#VehiclePictureModal").modal('show');
        }

        function FormSubmit() {
            Swal.fire({
                title: 'ยืนยันการลงทะเบียน?',
                text: "ยืนวันว่าข้อมูลถูกต้องทั้งหมด!",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    var VehicleID = document.getElementById("VehicleID").value;
                    var Brand = document.getElementById("Brand");
                    var Color = document.getElementById("Color");
                    var VehicleProvince = document.getElementById("VehicleProvince");
                    var VehicleModel = document.getElementById("VehicleModel").value;
                    var CopyOfVehicle = document.getElementById("CopyOfVehicle");
                    var CopyOfOwnerIDCard = document.getElementById("CopyOfOwnerIDCard");
                    var CopyOfUserIDCard = document.getElementById("CopyOfUserIDCard");
                    var PictureVehicleFront = document.getElementById("PictureVehicleFront");
                    var PictureVehicleSide = document.getElementById("PictureVehicleSide");
                    var PictureVehicleBack = document.getElementById("PictureVehicleBack");
                    if (VehicleID == "" || Brand.selectedIndex == 0 || Color.selectedIndex == 0 || VehicleProvince.selectedIndex == 0 || VehicleModel == "" || CopyOfVehicle.files.length == 0 || CopyOfOwnerIDCard.files.length == 0 || CopyOfUserIDCard.files.length == 0 || PictureVehicleFront.files.length == 0 || PictureVehicleSide.files.length == 0 || PictureVehicleBack.files.length == 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'ข้อมูลไม่สมบูรณ์',
                            text: "กรุณากรอกข้อมูลให้สมบูรณ์",
                            confirmButtonText: 'ตกลง'
                        });
                    }
                    else {
                        document.getElementById("registration").submit();
                    }
                }
            })
        }
    </script>

</body>
</html>