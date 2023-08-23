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

    $data = [];
    $row = SQLSelectAssoc($con, $sql);

    foreach ($row as $key => $value) {
      switch($key) {
        case "copyOf_VehicleRegis" :
        case "copyOf_IdCard" :
        case "copyOf_StdID" :
        case "img_front" :
        case "img_side" :
        case "img_back" :
        case "pdfSignature" :
        case "img" :
          $data[$key] = base64_encode($value);
          break;
        default :
          $data[$key] = $value;
      }
    }

    echo json_encode($data);
  }
  
?>