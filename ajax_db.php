<?php
 
    //Import connect.php file
    include_once('import/connect.php');
    include_once('import/SQLQuery.php');
 
 
  if (isset($_POST['function']) && $_POST['function'] == 'Province') {
  	$id = $_POST['id'];
  	$sql = "SELECT * FROM district WHERE province_id='$id'";
  	$query = mysqli_query($con, $sql);
    echo "<option value='' selected>-- อำเภอ --</option>";
  	foreach ($query as $value) {
  		echo '<option value="'.$value['district_id'].'">'.$value['district_name_th'].'</option>';
  		
  	}
  }

  else if (isset($_POST['function']) && $_POST['function'] == 'District') {
    $id = $_POST['id'];
    $sql = "SELECT * FROM subdistrict WHERE district_id='$id'";
    $query = mysqli_query($con, $sql);
    echo "<option value='' selected>-- ตำบล --</option>";
    foreach ($query as $value2) {
      echo '<option value="'.$value2['subDistrict_id'].'">'.$value2['subDistrictName_th'].'</option>';
      
    }
  }

  // QR-Code Generator
  else if (isset($_POST['qrCodeData'])) {
    $qrCodeData = $_POST['qrCodeData'];
    $sql = "INSERT INTO test VALUES (null, '$qrCodeData')";
    $query = mysqli_query($con, $sql);
    echo "BRUH";
  }

  //Cancel Registration (Vehicle)
  else if (isset($_POST['function']) && $_POST['function'] == 'RegistrationDelete') {
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
  else if (isset($_GET['PDFUpdateID'])) {
    $ID = $_GET['PDFUpdateID'];
    echo "<script>window.location = 'status-detail.php?ID=$ID'</script>";
  }

  //Announcement Details Showing
  else if ((isset($_GET['function'])) && ($_GET['function'] == 'AnnouncementDetail')) {
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
  else if ((isset($_GET['function'])) && ($_GET['function'] == 'ConfirmCompleteRegistration')) {
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

  // User_ReoprtDetail_Modal
  else if ((isset($_POST['function'])) && ($_POST['function'] == 'User_ReoprtDetail_Modal')) {
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
  else if ((isset($_POST['function'])) && ($_POST['function'] == 'User_Reoprt_ConfirmMatter')) {
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
  else if (isset($_POST['BackendFunction']) && $_POST['BackendFunction'] == 'RegistrationRequestApprove') {

    $CurrentDate = date("Y-m-d");
    $CurrentTime = date("H:i:s");

    $data = $_POST['data'];
    for ($i = 0; $i < count($data); $i++) {
      $sql = "INSERT INTO registration_status_detail VALUES (" . $data[$i] . ", 3, null, '$CurrentDate', '$CurrentTime', null);";
      $query = mysqli_query($con, $sql);
    }
  }

  else if (isset($_POST['BackendFunction']) && $_POST['BackendFunction'] == 'RegistrationRequestApprove_Single') {

    $CurrentDate = date("Y-m-d");
    $CurrentTime = date("H:i:s");

    $data = $_POST['data'];
    $sql = "INSERT INTO registration_status_detail VALUES (" . $data . ", 3, null, '$CurrentDate', '$CurrentTime', null);";
    $query = mysqli_query($con, $sql);
  }

  // RegistrationDetail_Modal Calling
  else if (isset($_POST['BackendFunction']) && $_POST['BackendFunction'] == 'RegistrationDetail_Modal') {
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

  // Sticker Printing
  else if (isset($_POST['BackendFunction']) && $_POST['BackendFunction'] == 'PrintSticker') {
    $regis_id = $_POST['id'];
    $IssuedDate = $_POST['IssuedDate'];
    $ExipredDate = $_POST['ExipredDate'];

    for ($i = 0; $i < count($regis_id); $i++) {
      $temp_vehicle_id = $regis_id[$i];

      // Get Regis_id for update Status
      $sql_getRegisID = "SELECT
                            LastestStatus.regis_id
                            #LastestStatus.lastest_regisStatus_id,
                            #registration.vehicle_id
                          FROM
                            registration_status_detail,
                            (SELECT regis_id, MAX(regisStatus_id) AS lastest_regisStatus_id FROM registration_status_detail GROUP BY regis_id) AS LastestStatus
                            
                          JOIN registration_status ON LastestStatus.lastest_regisStatus_id = registration_status.regisStatus_id
                          LEFT JOIN registration ON LastestStatus.regis_id = registration.regis_id
                          WHERE
                            (registration_status_detail.regis_id = LastestStatus.regis_id) AND
                            (registration_status_detail.regisStatus_id = LastestStatus.lastest_regisStatus_id) AND
                            (registration.vehicle_id = '$temp_vehicle_id')
                          ORDER BY registration_status_detail.regis_id DESC
                          LIMIT 1
      ";
      $query_getRegisID = $con->query($sql_getRegisID);
      $data = $query_getRegisID->fetch_array();
      $temp_getRegisID = $data['regis_id'];

      // Update registration_status_detail [Sticker Printed]
      $sql = "UPDATE registration_status_detail
              JOIN 
                registration ON registration_status_detail.regis_id = registration.regis_id
              SET
                registration_status_detail.comment = 'sticker printed'
              WHERE
                (registration.vehicle_id = '$temp_vehicle_id') AND
                (registration.regis_id = $temp_getRegisID) AND
                (registration_status_detail.regisStatus_id = 4)
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
        $sql = "INSERT INTO parking_card VALUES (null, '$temp_getRegisID', '$IssuedDate', '$ExipredDate', '0')";
        $query = mysqli_query($con, $sql);
      }
    }

    echo json_encode("Registration Printed");
  }

  // User hit the complete registration button
  else if ((isset($_POST['BackendFunction'])) && ($_POST['BackendFunction'] == 'ReoprtDetail_Modal')) {
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

  // ReoprtDetail_Multi_MarkRead
  else if ((isset($_POST['BackendFunction'])) && ($_POST['BackendFunction'] == 'ReoprtDetail_Multi_MarkRead')) {
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

  // Admin Direct Message to users
  else if ((isset($_POST['BackendFunction'])) && ($_POST['BackendFunction'] == 'ReoprtDetail_Modal_Advice')) {
    
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

    $query = $con->query($sql);

    echo json_encode(true);
  }

  // ReoprtDetail_Modal_MarkAsRead
  else if ((isset($_POST['BackendFunction'])) && ($_POST['BackendFunction'] == 'ReoprtDetail_Modal_MarkAsRead')) {
    
    $id = $_POST['id'];

    $sql = "UPDATE notification SET admin_receive_status = '1' WHERE noti_id = '$id'";
    $query = $con->query($sql);

    echo json_encode(true);
  }

  // ------------------- Server Settings -------------------

  // System Settings - Registration Settings
  else if (isset($_POST['SystemSettings']) && $_POST['SystemSettings'] == 'Registration_Settings') {
    $value = $_POST['value'];

    $sql = "UPDATE system_settings SET is_enable = $value WHERE system = 'vehicle_registration'";

    $query = $con->query($sql);

    echo json_encode(true);
  }

  // System Settings - Max Vehicle Settings
  else if (isset($_POST['SystemSettings']) && $_POST['SystemSettings'] == 'Max_Vehicle_Settings') {
    $value = $_POST['value'];

    $sql = "UPDATE system_settings SET value = $value WHERE system = 'max_vehicle_registration'";

    $query = $con->query($sql);

    echo json_encode(true);
  }

  // System Settings Lookup Table Show Data (Showing)
  else if (isset($_POST['SystemSettings']) && $_POST['SystemSettings'] == 'LookupTableData') {
    $table = $_POST['table'];

    switch ($table) {

      // ------ CASE (Prefix) ------
      case 'prefix' :
        $sql = "SELECT * FROM $table";
        $query = $con->query($sql);

        $HTML_Output = "
          <div class='mb-4' id='AddNewDataZone' onclick='LookupTable_AddNewData(\"prefix\")'>
            <button class='btn btn-success'>เพิ่มข้อมูล</button>
          </div>
        ";

        $HTML_Output .= "
          <tr>
            <th>#</th>
            <th>คำนำหน้า</th>
            <th class='text-end'>จัดการ</th>
          </tr>
        ";

        while ($result = $query->fetch_array()) {
            $prefix_id = $result['prefix_id'];
            $prefix_name = $result['prefix_name'];
            
            $HTML_Output .= "
                <tr>
                    <td id='$prefix_id'>$prefix_id</td>
                    <td id='$prefix_name'>$prefix_name</td>
                    <td class='text-end'>
                        <button type='button' class='btn btn-warning text-light' onclick='LookupTable_EditData(\"prefix\", \"$prefix_id\")'>
                            <i class='bi bi-pencil-square'></i>
                        </button>
                        <button type='button' class='btn btn-danger text-light' onclick='DeleteData(\"prefix\", \"$prefix_id\")'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </td>
                </tr>
            ";
        }

        break;

      // ------ CASE (Department) ------
      case 'department' :
        $sql = "SELECT * FROM $table";
        $query = $con->query($sql);

        $HTML_Output = "
          <div class='mb-4' onclick='LookupTable_AddNewData(\"department\")'>
            <button class='btn btn-success'>เพิ่มข้อมูล</button>
          </div>
        ";

        $HTML_Output .= "
          <tr>
            <th>#</th>
            <th>แผนกวิชา</th>
            <th class='text-end'>จัดการ</th>
          </tr>
        ";

        while ($result = $query->fetch_array()) {
          $dept_id = $result['dept_id'];
          $dept_name = $result['dept_name'];
          $HTML_Output .= "
            <tr>
              <td>$dept_id</td>
              <td>$dept_name</td>
              <td class='text-end'>
                  <button type='button' class='btn btn-warning text-light' onclick='LookupTable_EditData(\"department\", \"$dept_id\")'>
                      <i class='bi bi-pencil-square'></i>
                  </button>
                  <button type='button' class='btn btn-danger text-light' onclick='DeleteData(\"department\", \"$dept_id\")'>
                      <i class='bi bi-trash'></i>
                  </button>
              </td>
            </tr>
          ";
        }

        break;

      // ------ CASE (Major) ------
      case 'major' :
        $sql = "SELECT major.major_id, major.major_name, department.dept_name FROM major JOIN department ON major.dept_id = department.dept_id";
        $query = $con->query($sql);

        $HTML_Output = "
          <div class='mb-4' onclick='LookupTable_AddNewData(\"major\")'>
            <button class='btn btn-success'>เพิ่มข้อมูล</button>
          </div>
        ";

        $HTML_Output .= "
          <tr>
            <th>#</th>
            <th>สาชาวิชา</th>
            <th>แผนกวิชา</th>
            <th class='text-end'>จัดการ</th>
          </tr>
        ";

        while ($result = $query->fetch_array()) {
          $major_id = $result['major_id'];
          $marjor_name = $result['major_name'];
          $dept_name = $result['dept_name'];
          $HTML_Output .= "
            <tr>
              <td style='width: 15%;'>$major_id</td>
              <td style='width: 35%;'>$marjor_name</td>
              <td style='width: 35%;'>$dept_name</td>
              <td class='text-end' style='width: 15%;'>
                  <button type='button' class='btn btn-warning text-light' onclick='LookupTable_EditData(\"major\", \"$major_id\")'>
                      <i class='bi bi-pencil-square'></i>
                  </button>
                  <button type='button' class='btn btn-danger text-light' onclick='DeleteData(\"major\", \"$major_id\")'>
                      <i class='bi bi-trash'></i>
                  </button>
              </td>
            </tr>
          ";
        }

        break;

      // ------ CASE (Class Level) ------
      case 'class_level' :
        $sql = "SELECT * FROM class_level";
        $query = $con->query($sql);

        $HTML_Output = "
          <div class='mb-4' onclick='LookupTable_AddNewData(\"class_level\")'>
            <button class='btn btn-success'>เพิ่มข้อมูล</button>
          </div>
        ";

        $HTML_Output .= "
          <tr>
            <th>#</th>
            <th>ระดับชั้น</th>
            <th class='text-end'>จัดการ</th>
          </tr>
        ";

        while ($result = $query->fetch_array()) {
          $level_id = $result['level_id'];
          $level_name = $result['level_name'];
          $HTML_Output .= "
            <tr>
              <td>$level_id</td>
              <td>$level_name</td>
              <td class='text-end'>
                  <button type='button' class='btn btn-warning text-light' onclick='LookupTable_EditData(\"class_level\", \"$level_id\")'>
                      <i class='bi bi-pencil-square'></i>
                  </button>
                  <button type='button' class='btn btn-danger text-light' onclick='DeleteData(\"class_level\", \"$level_id\")'>
                      <i class='bi bi-trash'></i>
                  </button>
              </td>
            </tr>
          ";
        }

        break;

      // ------ CASE (Vehicle Type) ------
      case 'vehicle_type' :
        $sql = "SELECT * FROM vehicle_type";
        $query = $con->query($sql);

        $HTML_Output = "
          <div class='mb-4' onclick='LookupTable_AddNewData(\"vehicle_type\")'>
            <button class='btn btn-success'>เพิ่มข้อมูล</button>
          </div>
        ";

        $HTML_Output .= "
          <tr>
            <th>#</th>
            <th>ประเภทยานพาหนะ</th>
            <th class='text-end'>จัดการ</th>
          </tr>
        ";

        while ($result = $query->fetch_array()) {
          $vehiType_id = $result['vehiType_id'];
          $vehiType_name = $result['vehiType_name'];
          $HTML_Output .= "
            <tr>
              <td>$vehiType_id</td>
              <td>$vehiType_name</td>
              <td class='text-end'>
                  <button type='button' class='btn btn-warning text-light' onclick='LookupTable_EditData(\"vehicle_type\", \"$vehiType_id\")'>
                      <i class='bi bi-pencil-square'></i>
                  </button>
                  <button type='button' class='btn btn-danger text-light' onclick='DeleteData(\"vehicle_type\", \"$vehiType_id\")'>
                      <i class='bi bi-trash'></i>
                  </button>
              </td>
            </tr>
          ";
        }

        break;

      // ------ CASE (Vehicle Brand) ------
      case 'vehicle_brand' :
        $sql = "SELECT * FROM vehicle_brand";
        $query = $con->query($sql);

        $HTML_Output = "
          <div class='mb-4' onclick='LookupTable_AddNewData(\"vehicle_brand\")'>
            <button class='btn btn-success'>เพิ่มข้อมูล</button>
          </div>
        ";

        $HTML_Output .= "
          <tr>
            <th>#</th>
            <th>ยี่ห้อยานพาหนะ</th>
            <th class='text-end'>จัดการ</th>
          </tr>
        ";

        while ($result = $query->fetch_array()) {
          $vehiBrand_id = $result['vehiBrand_id'];
          $vehiBrand_name = $result['vehiBrand_name'];
          $HTML_Output .= "
            <tr>
              <td>$vehiBrand_id</td>
              <td>$vehiBrand_name</td>
              <td class='text-end'>
                  <button type='button' class='btn btn-warning text-light' onclick='LookupTable_EditData(\"vehicle_brand\", \"$vehiBrand_id\")'>
                      <i class='bi bi-pencil-square'></i>
                  </button>
                  <button type='button' class='btn btn-danger text-light' onclick='DeleteData(\"vehicle_brand\", \"$vehiBrand_id\")'>
                      <i class='bi bi-trash'></i>
                  </button>
              </td>
            </tr>
          ";
        }

        break;

      // ------ CASE (Color) ------
      case 'color' :
        $sql = "SELECT * FROM color";
        $query = $con->query($sql);

        $HTML_Output = "
          <div class='mb-4' onclick='LookupTable_AddNewData(\"color\")'>
            <button class='btn btn-success'>เพิ่มข้อมูล</button>
          </div>
        ";

        $HTML_Output .= "
          <tr>
            <th>#</th>
            <th>ยี่ห้อยานพาหนะ</th>
            <th class='text-end'>จัดการ</th>
          </tr>
        ";

        while ($result = $query->fetch_array()) {
          $color_id = $result['color_id'];
          $color_name = $result['color_name'];
          $HTML_Output .= "
            <tr>
              <td>$color_id</td>
              <td>$color_name</td>
              <td class='text-end'>
                  <button type='button' class='btn btn-warning text-light' onclick='LookupTable_EditData(\"color\", \"$color_id\")'>
                      <i class='bi bi-pencil-square'></i>
                  </button>
                  <button type='button' class='btn btn-danger text-light' onclick='DeleteData(\"color\", \"$color_id\")'>
                      <i class='bi bi-trash'></i>
                  </button>
              </td>
            </tr>
          ";
        }

        break;
    }

    echo json_encode($HTML_Output);
  }

  // System Settings Lookup Table Edit Data (Showing)
  else if (isset($_POST['SystemSettings']) && $_POST['SystemSettings'] == 'LookupTableData_EditData') {
    $table = $_POST['table'];
    $id = $_POST['id'];

    switch ($table) {

      // ---------- Prefix ----------
      case 'prefix' :
        $sql = "SELECT * FROM prefix WHERE prefix_id = '$id'";
        $query = $con->query($sql);
        $result = $query->fetch_array();

        $prefix_id = $result['prefix_id'];
        $prefix_name = $result['prefix_name'];

        $HTML_Output = "
          <table class='table table-stripped table-hover align-middle'>
              <tr>
                  <th>prefix_id (PK)</th>
                  <th>prefix_name</th>
              </tr>
              <tr>
                  <td>
                      <input type='text' id='Edit_PK' class='form-control bg-body-secondary' placeholder='ลำดับ (prefix_id)' required value='$prefix_id' readonly>
                  </td>
                  <td>
                      <input type='text' id='Edit_Col1' class='form-control' placeholder='คำนำหน้าชื่อ (prefix_name)' required value='$prefix_name'>
                  </td>
              </tr>
          </table>

          <div class='my-3'>
              <button type='button' class='btn btn-primary' onclick='EditData(\"$table\", \"$prefix_id\")'>แก้ไข</button>
          </div>
        ";
        break;
      // ---------- Department ----------
      case 'department' :
        $sql = "SELECT * FROM department WHERE dept_id = '$id'";
        $query = $con->query($sql);
        $result = $query->fetch_array();

        $dept_id = $result['dept_id'];
        $dept_name = $result['dept_name'];

        $HTML_Output = "
          <table class='table table-stripped table-hover align-middle'>
              <tr>
                  <th>dept_id (PK)</th>
                  <th>dept_name</th>
              </tr>
              <tr>
                  <td>
                      <input type='text' id='Edit_PK' class='form-control bg-body-secondary' placeholder='ลำดับ (dept_id)' required value='$dept_id' readonly>
                  </td>
                  <td>
                      <input type='text' id='Edit_Col1' class='form-control' placeholder='แผนกวิชา (dept_name)' required value='$dept_name'>
                  </td>
              </tr>
          </table>

          <div class='my-3'>
              <button type='button' class='btn btn-primary' onclick='EditData(\"$table\", \"$dept_id\")'>แก้ไข</button>
          </div>
        ";
        break;
      // ---------- Major ----------
      case 'major' :
        $sql = "SELECT major.* FROM major WHERE major_id = '$id'";
        $query = $con->query($sql);
        $result = $query->fetch_array();

        $major_id = $result['major_id'];
        $major_name = $result['major_name'];
        $dept_id = $result['dept_id'];

        // Get Old Department Data
        $sql_department = "SELECT * FROM department";
        $query_department = $con->query($sql_department);
        $HTML_Output_department = "<option value=''>-- แผนกวิชา --</option>";
        while ($department_data = $query_department->fetch_array()) {

          $temp_dept_id = $department_data['dept_id'];
          $temp_dept_name = $department_data['dept_name'];

          if ($department_data['dept_id'] == $dept_id) {
            $HTML_Output_department .= "<option value='$temp_dept_id' selected>$temp_dept_name</option>";
          }
          else {
            $HTML_Output_department .= "<option value='$temp_dept_id'>$temp_dept_name</option>";
          }

        }

        $HTML_Output = "
          <table class='table table-stripped table-hover align-middle'>
              <tr>
                  <th>major_id (PK)</th>
                  <th>major_name</th>
                  <th>dept_id (FK)</th>
              </tr>
              <tr>
                  <td>
                      <input type='text' id='Edit_PK' class='form-control bg-body-secondary' placeholder='ลำดับ (major_id)' required value='$major_id' readonly>
                  </td>
                  <td>
                      <input type='text' id='Edit_Col1' class='form-control' placeholder='สาขาวิชา (major_name)' required value='$major_name'>
                  </td>
                  <td>
                    <select id='Edit_Col2' class='form-select'>$HTML_Output_department</select>
                  </td>
              </tr>
          </table>

          <div class='my-3'>
              <button type='button' class='btn btn-primary' onclick='EditData(\"$table\", \"$major_id\")'>แก้ไข</button>
          </div>
        ";
        break;
      // ---------- Class Level ----------
      case 'class_level' :
        $sql = "SELECT * FROM class_level WHERE level_id = '$id'";
        $query = $con->query($sql);
        $result = $query->fetch_array();

        $level_id = $result['level_id'];
        $level_name = $result['level_name'];

        $HTML_Output = "
          <table class='table table-stripped table-hover align-middle'>
              <tr>
                  <th>level_id (PK)</th>
                  <th>level_name</th>
              </tr>
              <tr>
                  <td>
                      <input type='text' id='Edit_PK' class='form-control bg-body-secondary' placeholder='ลำดับ (level_id)' required value='$level_id' readonly>
                  </td>
                  <td>
                      <input type='text' id='Edit_Col1' class='form-control' placeholder='ระดับชั้น (level_name)' required value='$level_name'>
                  </td>
              </tr>
          </table>

          <div class='my-3'>
              <button type='button' class='btn btn-primary' onclick='EditData(\"$table\", \"$level_id\")'>แก้ไข</button>
          </div>
        ";
        break;
      // ---------- Vehicle Type ----------
      case 'vehicle_type' :
        $sql = "SELECT * FROM vehicle_type WHERE vehiType_id = '$id'";
        $query = $con->query($sql);
        $result = $query->fetch_array();

        $vehiType_id = $result['vehiType_id'];
        $vehiType_name = $result['vehiType_name'];

        $HTML_Output = "
          <table class='table table-stripped table-hover align-middle'>
              <tr>
                  <th>vehiType_id (PK)</th>
                  <th>vehiType_name</th>
              </tr>
              <tr>
                  <td>
                      <input type='text' id='Edit_PK' class='form-control bg-body-secondary' placeholder='ลำดับ (vehiType_id)' required value='$vehiType_id' readonly>
                  </td>
                  <td>
                      <input type='text' id='Edit_Col1' class='form-control' placeholder='ประเภทยานพาหนะ (vehiType_name)' required value='$vehiType_name'>
                  </td>
              </tr>
          </table>

          <div class='my-3'>
              <button type='button' class='btn btn-primary' onclick='EditData(\"$table\", \"$vehiType_id\")'>แก้ไข</button>
          </div>
        ";
        break;
      // ---------- Vehicle Brand ----------
      case 'vehicle_brand' :
        $sql = "SELECT * FROM vehicle_brand WHERE vehiBrand_id = '$id'";
        $query = $con->query($sql);
        $result = $query->fetch_array();

        $vehiBrand_id = $result['vehiBrand_id'];
        $vehiBrand_name = $result['vehiBrand_name'];

        $HTML_Output = "
          <table class='table table-stripped table-hover align-middle'>
              <tr>
                  <th>vehiBrand_id (PK)</th>
                  <th>vehiBrand_name</th>
              </tr>
              <tr>
                  <td>
                      <input type='text' id='Edit_PK' class='form-control bg-body-secondary' placeholder='ลำดับ (vehiBrand_id)' required value='$vehiBrand_id' readonly>
                  </td>
                  <td>
                      <input type='text' id='Edit_Col1' class='form-control' placeholder='ยี่ห้อยานพาหนะ (vehiBrand_name)' required value='$vehiBrand_name'>
                  </td>
              </tr>
          </table>

          <div class='my-3'>
              <button type='button' class='btn btn-primary' onclick='EditData(\"$table\", \"$vehiBrand_id\")'>แก้ไข</button>
          </div>
        ";
        break;
      // ---------- Color ----------
      case 'color' :
        $sql = "SELECT * FROM color WHERE color_id = '$id'";
        $query = $con->query($sql);
        $result = $query->fetch_array();

        $color_id = $result['color_id'];
        $color_name = $result['color_name'];

        $HTML_Output = "
          <table class='table table-stripped table-hover align-middle'>
              <tr>
                  <th>color_id (PK)</th>
                  <th>color_name</th>
              </tr>
              <tr>
                  <td>
                      <input type='text' id='Edit_PK' class='form-control bg-body-secondary' placeholder='ลำดับ (color_id)' required value='$color_id' readonly>
                  </td>
                  <td>
                      <input type='text' id='Edit_Col1' class='form-control' placeholder='สี (color_name)' required value='$color_name'>
                  </td>
              </tr>
          </table>

          <div class='my-3'>
              <button type='button' class='btn btn-primary' onclick='EditData(\"$table\", \"$color_id\")'>แก้ไข</button>
          </div>
        ";
        break;
      
    }

    echo json_encode($HTML_Output);
  }

  // System Settings Lookup Table Add New Data (Query)
  else if (isset($_POST['SystemSettings']) && $_POST['SystemSettings'] == 'LookupTableData_Add') {
    $table = $_POST['table'];
    $PK_value = $_POST['PK_value'];
    $Col1_value = $_POST['Col1_value'];

    $Col2_value = $_POST['Col2_value']; // For FK of major ONLY!!! (BRUH)

    switch ($table) {
      // ---------- Prefix ----------
      case 'prefix':
        $sql = "INSERT INTO prefix (prefix_id, prefix_name) VALUES ('$PK_value', '$Col1_value')";
        break;
      // ---------- Department ----------
      case 'department':
        $sql = "INSERT INTO department (dept_id, dept_name) VALUES ('$PK_value', '$Col1_value')";
        break;
      // ---------- Major ----------
      case 'major':
        $sql = "INSERT INTO major (major_id, major_name, dept_id) VALUES ('$PK_value', '$Col1_value', '$Col2_value')";
        break;
      // ---------- Class Level ----------
      case 'class_level':
        $sql = "INSERT INTO class_level (level_id, level_name) VALUES ('$PK_value', '$Col1_value')";
        break;
      // ---------- Vehicle Type ----------
      case 'vehicle_type':
        $sql = "INSERT INTO vehicle_type (vehiType_id, vehiType_name) VALUES ('$PK_value', '$Col1_value')";
        break;
      // ---------- Vehicle Brand ----------
      case 'vehicle_brand':
        $sql = "INSERT INTO vehicle_brand (vehiBrand_id, vehiBrand_name) VALUES ('$PK_value', '$Col1_value')";
        break;
      // ---------- Color ----------
      case 'color':
        $sql = "INSERT INTO color (color_id, color_name) VALUES ('$PK_value', '$Col1_value')";
        break;
    }

    // Query
    if ($con->query($sql) === true) {
      echo json_encode(true);
    } else {
      $error_message = $con->error;
      echo json_encode($error_message);
    }

  }

  // System Settings Lookup Table Edit Data (Query)
  else if (isset($_POST['SystemSettings']) && $_POST['SystemSettings'] == 'LookupTableData_Edit') {
    $table = $_POST['table'];
    $PK_value = $_POST['PK_value'];
    $Col1_value = $_POST['Col1_value'];

    $Col2_value = $_POST['Col2_value']; // For FK of major ONLY!!! (BRUH)

    switch ($table) {
      case 'prefix':
        $sql = "UPDATE prefix SET prefix_name = '$Col1_value' WHERE prefix_id = '$PK_value'";
        break;
      case 'department':
        $sql = "UPDATE department SET dept_name = '$Col1_value' WHERE dept_id = '$PK_value'";
        break;
      case 'major':
        $sql = "UPDATE major SET major_name = '$Col1_value', dept_id = '$Col2_value' WHERE major_id = '$PK_value'";
        break;
      case 'class_level':
        $sql = "UPDATE class_level SET level_name = '$Col1_value' WHERE level_id = '$PK_value'";
        break;
      case 'vehicle_type':
        $sql = "UPDATE vehicle_type SET vehiType_name = '$Col1_value' WHERE vehiType_id = '$PK_value'";
        break;
      case 'vehicle_brand':
        $sql = "UPDATE vehicle_brand SET vehiBrand_name = '$Col1_value' WHERE vehiBrand_id = '$PK_value'";
        break;
      case 'color':
        $sql = "UPDATE color SET color_name = '$Col1_value' WHERE color_id = '$PK_value'";
        break;
    }

    // Query
    if ($con->query($sql) === true) {
      echo json_encode(true);
    } else {
      $error_message = $con->error;
      echo json_encode($error_message);
    }
  }

  // System Settings Lookup Table Delete Data (Query)
  else if (isset($_POST['SystemSettings']) && $_POST['SystemSettings'] == 'LookupTableData_Delete') {
    $table = $_POST['table'];
    $PK_value = $_POST['PK_value'];

    switch ($table) {
      case 'prefix':
        $sql = "DELETE FROM prefix WHERE prefix_id = '$PK_value'";
        break;
      case 'department':
        $sql = "DELETE FROM department WHERE dept_id = '$PK_value'";
        break;
      case 'major':
        $sql = "DELETE FROM major WHERE major_id = '$PK_value'";
        break;
      case 'class_level':
        $sql = "DELETE FROM class_level WHERE level_id = '$PK_value'";
        break;
      case 'vehicle_type':
        $sql = "DELETE FROM vehicle_type WHERE vehiType_id = '$PK_value'";
        break;
      case 'vehicle_brand':
        $sql = "DELETE FROM vehicle_brand WHERE vehiBrand_id = '$PK_value'";
        break;
      case 'color':
        $sql = "DELETE FROM color WHERE color_id = '$PK_value'";
        break;
    }

    // Query
    if ($con->query($sql) === true) {
      echo json_encode(true);
    } else {
      $error_message = $con->error;
      echo json_encode($error_message);
    }
  }

  
?>