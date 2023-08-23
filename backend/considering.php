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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TATC Parking - ระบบลงทะเบียนจอดรถฯ วิทยาลัยเทคนิคสัตหีบ</title>

    <!-- Default Essentials css -->
    <?php include_once '../import/css.php'; ?>

    <!-- Essentials JavaScript -->
    <?php include_once '../import/js.php'; ?>
</head>
<body>
    <?php

        if (isset($_POST['Considering'])) {
            
            $regis_id = $_POST['regis_id'];
            $CurrentDate = date("Y-m-d");
            $CurrentTime = date("H:i:s");
            
            // Approved or Disapproval
            $Considering_Result = $_POST['Considering_radio'];

            // Result of Disapproval
            $ResultOfDisApprovals = array();
            if (isset($_POST['ResultOfDisApproval'])) {
                $ResultOfDisApprovals = $_POST['ResultOfDisApproval'];
            }
            $Comment = $_POST['Comment'];

            // Update Status (4 = Successfully Checking)
            // If Considering Result is approved
            if ($Considering_Result == '1') {
                $sql = "INSERT INTO registration_status_detail VALUES($regis_id, 4, null, '$CurrentDate', '$CurrentTime', null)";
                SQLQuery($con, $sql);
            }
            else {
                $sql = "INSERT INTO registration_status_detail VALUES($regis_id, 4, null, '$CurrentDate', '$CurrentTime', null)";
                SQLQuery($con, $sql);


                $sql = "INSERT INTO registration_status_detail VALUES($regis_id, 4, null, '$CurrentDate', '$CurrentTime', null)";
                for ($i = 0; $i < count($ResultOfDisApprovals); $i++) {
                    $sql = "INSERT INTO registration_status_detail VALUES($regis_id, $ResultOfDisApprovals[$i], null, '$CurrentDate', '$CurrentTime', null)";
                    SQLQuery($con, $sql);
                }
                // If have comments
                if ($Comment != null) {
                    $sql = "INSERT INTO registration_status_detail VALUES($regis_id, 9, null, '$CurrentDate', '$CurrentTime', '$Comment')";
                    SQLQuery($con, $sql);
                }
            }

            // Considering Completed
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'พิจารณาเสร็จสิ้น',
                    text: 'พิจารณาคำขอที่ ' + <?php echo $regis_id ?> + " เสร็จสิ้น",
                    confirmButtonText: 'ตกลง'
                }).then((result) => {
                    window.location='registration-checking.php'
                });
            </script>
            <?php
        }
        else {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    confirmButtonText: 'ตกลง'
                }).then((result) => {
                    window.location='registration-checking.php'
                });
            </script>
            <?php
        }

    ?>
</body>
</html>