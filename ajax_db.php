<?php
 
    //Import connect.php file
    include_once('import/connect.php');
    include_once('import/SQLQuery.php');
 
 
  if (isset($_POST['function']) && $_POST['function'] == 'Province') {
  	$id = $_POST['id'];
  	$sql = "SELECT * FROM district WHERE province_id='$id'";
  	$query = mysqli_query($con, $sql);
    echo "<option value='-- อำเภอ --' selected>-- อำเภอ --</option>";
  	foreach ($query as $value) {
  		echo '<option value="'.$value['district_id'].'">'.$value['district_name_th'].'</option>';
  		
  	}
  }

  if (isset($_POST['function']) && $_POST['function'] == 'District') {
    $id = $_POST['id'];
    $sql = "SELECT * FROM subdistrict WHERE district_id='$id'";
    $query = mysqli_query($con, $sql);
    echo "<option value='-- ตำบล --' selected>-- ตำบล --</option>";
    foreach ($query as $value2) {
      echo '<option value="'.$value2['subDistrict_id'].'">'.$value2['subDistrictName_th'].'</option>';
      
    }
  }

  // QR-Code Generator
  if (isset($_POST['qrCodeData'])) {
    $qrCodeData = $_POST['qrCodeData'];
    $sql = "INSERT INTO test VALUES (null, '$qrCodeData')";
    $query = mysqli_query($con, $sql);
    echo "BRUH";
  }

  //Cancel Registration (Vehicle)
  if (isset($_POST['function']) && $_POST['function'] == 'RegistrationDelete') {
    $id = $_POST['id'];
    
    //Get Vehcile ID
    $sql = "SELECT vehicle_id FROM registration WHERE regis_id = $id";
    $query = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($query);
    $vehicle_id = $data['vehicle_id'];

    //Delete registration
    $sql = "DELETE FROM registration_status_detail WHERE regis_id = $id";
    $query = mysqli_query($con, $sql);
    $sql = "DELETE FROM registration WHERE regis_id = $id";
    $query = mysqli_query($con, $sql);
    
    //Delete vehicle
    $sql = "DELETE FROM vehicle WHERE vehicle_id = '" . $vehicle_id . "'";
    $query = mysqli_query($con, $sql);
  }

  //PDF Document Update
  if (isset($_GET['PDFUpdateID'])) {
    $ID = $_GET['PDFUpdateID'];
    echo "<script>window.location = 'status-detail.php?ID=$ID'</script>";
  }

  //Announcement Details Showing
  if ((isset($_GET['function'])) && ($_GET['function'] == 'AnnouncementDetail')) {
    $an_id = $_GET['id'];
    $sql = "SELECT * FROM announcement WHERE an_id = $an_id";
    $query = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($query);

    $data = array();
    array_push($data, $row['an_id']);
    array_push($data, $row['title']);
    array_push($data, $row['content']);
    array_push($data, $row['file']);
    array_push($data, formatDateTimeThai($row['an_date']));

    echo json_encode($data);
  }

  // User hit the complete registration button
  if ((isset($_GET['function'])) && ($_GET['function'] == 'ConfirmCompleteRegistration')) {
    $id = $_GET['id'];

    $CurrentDate = date("Y-m-d");
    $CurrentTime = date("H:i:s");
    
    // Update registration status detail
    $sql = "INSERT INTO registration_status_detail VALUES ($id, 5, null, '$CurrentDate', '$CurrentTime', null)";
    $query = $con->query($sql);

    // Update Parking Card (isReceived = true)
    $sql = "UPDATE parking_card SET isReceived = '1' WHERE regis_id = '$id'";
    $query = $con->query($sql);

    echo "การลงทะเบียนที่ " . $id . " เสร็จสมบูรณ์";
  }

  // User hit the complete registration button
  if ((isset($_POST['function'])) && ($_POST['function'] == 'User_ReoprtDetail_Modal')) {
    $id = $_POST['id'];
    
    // Update registration status detail
    $sql = "SELECT
              notification.*,
                notification_type.details
            FROM
              notification
            JOIN notification_type ON notification.notiType_id = notification_type.notiType_id
            WHERE
              notification.noti_id = '$id'
    ";
    $query = $con->query($sql);
    $data = mysqli_fetch_array($query);

    echo json_encode($data);
  }

  // User Received the notification matter
  if ((isset($_POST['function'])) && ($_POST['function'] == 'User_Reoprt_ConfirmMatter')) {
    $id = $_POST['id'];
    
    // Update registration status detail
    $sql = "UPDATE notification SET isRead = '1' WHERE noti_id = '$id'";
    $result = $con->query($sql);

    if ($result) {
      echo json_encode(true);
    }
    else {
      echo json_encode(false);
    }
  }


  //------------------ Backend Ajax -------------------
  if (isset($_POST['BackendFunction']) && $_POST['BackendFunction'] == 'RegistrationRequestApprove') {

    $CurrentDate = date("Y-m-d");
    $CurrentTime = date("H:i:s");

    $data = $_POST['data'];
    for ($i = 0; $i < count($data); $i++) {
      $sql = "INSERT INTO registration_status_detail VALUES (" . $data[$i] . ", 3, null, '$CurrentDate', '$CurrentTime', null);";
      $query = mysqli_query($con, $sql);
    }
  }

  if (isset($_POST['BackendFunction']) && $_POST['BackendFunction'] == 'RegistrationRequestApprove_Single') {

    $CurrentDate = date("Y-m-d");
    $CurrentTime = date("H:i:s");

    $data = $_POST['data'];
    $sql = "INSERT INTO registration_status_detail VALUES (" . $data . ", 3, null, '$CurrentDate', '$CurrentTime', null);";
    $query = mysqli_query($con, $sql);
  }

  if (isset($_POST['BackendFunction']) && $_POST['BackendFunction'] == 'RegistrationDetail_Modal') {
    $id = $_POST['id'];
    $type = $_POST['type'];

    if ($type == "Student") {
      $sql = "SELECT
                registration.*, prefix.prefix_name, student.std_id,
                student.name, student.lastname, student.img, department.dept_name,
                class_level.level_name, province.province_name, vehicle_type.vehiType_name
              FROM 
                registration, prefix, student,
                department, class_level, vehicle, province,
                vehicle_type
              WHERE
                (registration.std_id = student.std_id) AND
                (student.prefix_id = prefix.prefix_id) AND
                (student.dept_id = department.dept_id) AND
                (student.level_id = class_level.level_id) AND
                (registration.vehicle_id = vehicle.vehicle_id) AND
                (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                (vehicle.province_id = province.province_id) AND
                (registration.regis_id = $id)
        ";
    }
    else {
      $sql = "SELECT
                registration.*, personnel.personnel_id,
                personnel.name, personnel.lastname, personnel.img, 
                province.province_name, vehicle_type.vehiType_name
              FROM 
                registration, personnel, vehicle, 
                province, vehicle_type
              WHERE
                (registration.personnel_id = personnel.personnel_id) AND
                (registration.vehicle_id = vehicle.vehicle_id) AND
                (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
                (vehicle.province_id = province.province_id) AND
                (registration.regis_id = $id)
        ";
    }

    $data = [];
    $row = SQLSelectAssoc($con, $sql);

    foreach ($row as $key => $value) {
      switch($key) {
        case "copyOf_VehicleRegis" :
        case "copyOf_ownerIdCard" :
        case "copyOf_userIdCard" :
        case "img_front" :
        case "img_side" :
        case "img_back" :
        case "pdfSignature" :
        case "img" :
          // $data[$key] = base64_encode($value);

          $Decoded_Data = base64_decode($value, true);
          // Checking if image file is base64_encode or not
          if ($Decoded_Data !== false) {
              // It's encode to base64
              $data[$key] = $value;
          }
          else {
              // It's not
              $data[$key] = base64_encode($value);
          }

          break;
        default :
          $data[$key] = $value;
      }
    }

    echo json_encode($data);
  }

  if (isset($_POST['BackendFunction']) && $_POST['BackendFunction'] == 'PrintSticker') {
    $regis_id = $_POST['id'];
    $IssuedDate = $_POST['IssuedDate'];
    $ExipredDate = $_POST['ExipredDate'];

    for ($i = 0; $i < count($regis_id); $i++) {
      $temp_vehicle_id = $regis_id[$i];

      // Update registration_status_detail [Sticker Printed]
      $sql = "UPDATE registration_status_detail
              JOIN 
                registration ON registration_status_detail.regis_id = registration.regis_id
              SET
                registration_status_detail.comment = 'sticker printed'
              WHERE
                registration.vehicle_id = '$temp_vehicle_id' AND
                  registration_status_detail.regisStatus_id = 4
      ";
      $query = mysqli_query($con, $sql);

      // Checking Parking Card if already exists
      $sql = "SELECT parking_card.*
              FROM parking_card
              JOIN registration ON  parking_card.regis_id = registration.regis_id
              WHERE
                (registration.vehicle_id = '$temp_vehicle_id') AND
                  (parking_card.issue_date < parking_card.expiration_date)
      ";
      $query = mysqli_query($con, $sql);
      // If Parking Card is not exists (New Registration)
      if ($query->num_rows == 0) {
        $sql = "SELECT registration.regis_id FROM registration WHERE vehicle_id = '$temp_vehicle_id'";
        $query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);
        $temp_regis_id = $row['regis_id'];

        $sql = "INSERT INTO parking_card VALUES (null, '$temp_regis_id', '$IssuedDate', '$ExipredDate', '0')";
        $query = mysqli_query($con, $sql);
      }
    }

    echo json_encode("Registration Printed");
  }

  // User hit the complete registration button
  if ((isset($_POST['BackendFunction'])) && ($_POST['BackendFunction'] == 'ReoprtDetail_Modal')) {
    $id = $_POST['id'];
    
    // Update registration status detail
    $sql = "SELECT
              notification.noti_id,
              CASE
                WHEN notification.std_id IS NOT NULL THEN 'Student'
                ELSE 'Personnel'
              END AS reporter_UserType,
              CASE
                WHEN notification.std_id IS NOT NULL THEN notification.std_id
                ELSE notification.personnel_id
              END AS reporter_UserID,
              reporter.prefix_name AS reporter_prefix,
              reporter.name AS reporter_name,
              reporter.lastname AS reporter_lastname,
              reporter.dept_name AS reporter_department,
              reporter.level_name AS reporter_class_level,
              notification.vehicle_id AS reported_vehicle_id,
                province.province_name AS reported_vehicle_province,
              CASE
                WHEN registration.std_id IS NOT NULL THEN 'Student'
                ELSE 'Personnel'
              END AS reported_UserType,
              CASE
                WHEN registration.std_id IS NOT NULL THEN registration.std_id
                ELSE registration.personnel_id
              END AS reported_UserID,
              reported.prefix_name AS reported_prefix,
              reported.name AS reported_name,
              reported.lastname AS reported_lastname,
              reported.dept_name AS reported_department,
              reported.level_name AS reported_class_level,
              notification.notiType_id,
              notification_type.details,
              notification.notiDate,
              notification.notiTime,
              notification.isRead,
              notification.comment,
              notification.img,
              notification.admin_receive_status
            FROM
              notification
            JOIN notification_type ON notification.notiType_id = notification_type.notiType_id
            LEFT JOIN student ON notification.std_id = student.std_id
            LEFT JOIN personnel ON notification.personnel_id = personnel.personnel_id
            LEFT JOIN vehicle ON notification.vehicle_id = vehicle.vehicle_id
            LEFT JOIN province ON vehicle.province_id = province.province_id
            LEFT JOIN registration ON vehicle.vehicle_id = registration.vehicle_id
            
            LEFT JOIN (
              SELECT student.std_id, prefix.prefix_name, student.name, student.lastname, department.dept_name, class_level.level_name
              FROM student
              JOIN prefix ON student.prefix_id = prefix.prefix_id
              LEFT JOIN department ON student.dept_id = department.dept_id
              LEFT JOIN class_level ON student.level_id = class_level.level_id
              
              UNION ALL
              
              SELECT personnel_id, NULL AS prefix_name, name, lastname, NULL AS dept_name, NULL AS level_name FROM personnel
            ) AS reporter ON notification.std_id = reporter.std_id OR notification.personnel_id = reporter.std_id
            
            LEFT JOIN (
              SELECT student.std_id, prefix.prefix_name, student.name, student.lastname, department.dept_name, class_level.level_name
              FROM student
              JOIN prefix ON student.prefix_id = prefix.prefix_id
              LEFT JOIN department ON student.dept_id = department.dept_id
              LEFT JOIN class_level ON student.level_id = class_level.level_id
              
              UNION ALL
              
              SELECT personnel_id, NULL AS prefix_name, name, lastname, NULL AS dept_name, NULL AS level_name FROM personnel
            ) AS reported ON registration.std_id = reported.std_id OR registration.personnel_id = reported.std_id
            
            WHERE
              notification.noti_id = '$id'
    ";
    $query = $con->query($sql);
    $data = mysqli_fetch_array($query);

    echo json_encode($data);
  }

  if ((isset($_POST['BackendFunction'])) && ($_POST['BackendFunction'] == 'ReoprtDetail_Multi_MarkRead')) {
    $data = $_POST['id']; // This is array variable

    // แปลงค่า array ให้เป็นสตริงที่ถูกต้องสำหรับ SQL
    $idList = implode(',', $data);

    $sql = "UPDATE notification
            SET admin_receive_status = '1'
            WHERE noti_id IN ($idList)
            ";

    $query = $con->query($sql);

    echo json_encode(true);
  }

  if ((isset($_POST['BackendFunction'])) && ($_POST['BackendFunction'] == 'ReoprtDetail_Modal_Advice')) {
    
    $CurrentDate = date("Y-m-d");
    $CurrentTime = date("H:i:s");
    
    $id = $_POST['id'];
    $message = $_POST['message'];
    $vehicle_id = $_POST['vehicle_id'];

    $sql = "INSERT INTO notification VALUES (
        null, # Noti_id
        null, # std_id
        null, # personnel_id
        '$vehicle_id', # vehicle_id
        '1', # notiType_id
        '$CurrentDate', # NotiData
        '$CurrentTime', # NotiTime
        '0', # isRead (User)
        '$message', # comment
        '1',  # admin_receive_status (Make auto read)
        null  # img
      )
    ";

    echo json_encode($sql);
  }

  
?>