<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TATC Parking - ระบบลงทะเบียนจอดรถฯ วิทยาลัยเทคนิคสัตหีบ</title>

  <!-- Essentials css -->
  <?php include_once 'import/css.php'; ?>

  <!-- Essentials JavaScript -->
  <?php include_once 'import/js.php'; ?>

</head>
<body>
<?php
//Import connect.php file
include_once('import/connect.php');

//Import SQL Query file
include_once('import/SQLQuery.php');

if (isset($_POST['ReportSubmit'])) {

  $CurrentDate = date("Y-m-d");
  $CurrentTime = date("H:i:s");

  $vehicle_id = $_POST['vehicle_id'];
  $UserType = $_POST['UserType'];
  $UserID = $_POST['UserID'];
  $ReportDetail = $_POST['ResultOfReport'];
  // var_dump($ReportDetail);

  if ($_FILES['Image']['error'] === UPLOAD_ERR_OK) {
    // Get the temporary file path
    $tmpFilePath = $_FILES['Image']['tmp_name'];
                    
    // Read the contents of the file
    $fileContent = file_get_contents($tmpFilePath);
    
    // Escape special characters in the file content
    $ImageFile = base64_encode($fileContent);
}

  if (isset($_POST['OtherReason']) && $_POST['OtherReason'] != null) {
    $OtherReason = $_POST['OtherReason'];
    // echo $OtherReason;
  }
  else {
    $OtherReason = null;
  }

  for ($i = 0; $i < count($ReportDetail); $i++) {

    $temp_ReportType = $ReportDetail[$i];

    //  If Student report
    if ($UserType == "Student") {
      // If have comments
      if ($temp_ReportType == "5") {
        $sql = "INSERT INTO notification VALUES (null, '$UserID', null, '$vehicle_id', $temp_ReportType, '$CurrentDate', '$CurrentTime', '0', '$OtherReason', '0', '$ImageFile')";
      }
      // have no comments
      else {
        $sql = "INSERT INTO notification VALUES (null, '$UserID', null, '$vehicle_id', $temp_ReportType, '$CurrentDate', '$CurrentTime', '0', null, '0', '$ImageFile')";
      }
    }
    //  It's a personnel report
    else {
      // If have comments
      if ($temp_ReportType == "5") {
        $sql = "INSERT INTO notification VALUES (null, null, '$UserID', '$vehicle_id', $temp_ReportType, '$CurrentDate', '$CurrentTime', '0', '$OtherReason', '0', '$ImageFile')";
      }
      // have no comments
      else {
        $sql = "INSERT INTO notification VALUES (null, null, '$UserID', '$vehicle_id', $temp_ReportType, '$CurrentDate', '$CurrentTime', '0', null, '0', '$ImageFile')";
      }
    }
    SQLQuery($con, $sql);
    // echo $sql . "<br>";
  }

  echo "<script>
          Swal.fire({
              icon: 'success',
              title: 'รายงานสำเร็จ',
              text: 'ผลการรายงานจะถูกส่งไปผู้ถูกรายงานและงานปกครอง',
              confirmButtonText: 'ตกลง',
              confirmButtonColor: '#3172f5',
          }).then((result) => {
              window.location = 'report.php';
          });
        </script>
  ";
}
else {
  echo "<script>window.location = 'index.php'</script>";
}
?>
</body>
</html>