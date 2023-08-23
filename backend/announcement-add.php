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

            //When Submitted
            if (isset($_POST['Submit'])) {
                $an_id = $_POST['an_id'];
                $title = $_POST['title'];
                $date = $_POST['date'];
                $CurrentTime = date("H:i:s");

                $FullDate = $date . " " . $CurrentTime;
                
                $detail = $_POST['detail'];
                // $file = $_FILES['file'];

                if ($detail != null) {
                    if (isset($_FILES['file']) && $_FILES['file']['tmp_name'] != null) {
                        $file_name = $_FILES['file']['name'];
                        $file_data = file_get_contents($_FILES['file']['tmp_name']);
                        $file_upload = base64_encode($file_data);

                        //Have details and file upload
                        $sql = "INSERT INTO announcement VALUES ($an_id, '$title', '$detail', '$file_upload', '$FullDate', '$file_name')";
                    }
                    else {
                        //Have details but no have file upload
                        $sql = "INSERT INTO announcement VALUES ($an_id, '$title', '$detail', null, '$FullDate', null)";
                    }
                }
                else {
                    if (isset($_FILES['file']) && $_FILES['file']['tmp_name'] != null) {
                        $file_data = file_get_contents($_FILES['file']['tmp_name']);
                        $file_upload = base64_encode($file_data);

                        //No have details but have file upload
                        $sql = "INSERT INTO announcement VALUES ($an_id, '$title', null, '$file_upload', '$FullDate', '$file_name')";
                    }
                    else {
                        //No have details and file upload
                        $sql = "INSERT INTO announcement VALUES ($an_id, '$title', null, null, '$FullDate', null)";
                    }
                }

                //Query for neww announcement
                try {
                    echo "<script>console.log('$sql')</script>";
                    SQLQuery($con, $sql);
                    echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'เพิ่มข้อมูลการประกาศสำเร็จ',
                                confirmButtonText: 'ตกลง'
                            }).then((result) => {
                                window.location='announcement.php'
                            });
                        </script>";
                }
                catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }



                // echo "<script>console.log('$an_id, $title, $detail, $date, $file');</script>";
            }
        ?>

        <div class="content">
            
            <!-- Title -->
            <div class="my-card">
                <div class="row align-items-center justify-content-between row-gap-3">
                    <div class="col-8">
                        <h1 class="m-0 p-0 fs-5 fw-bold">การประชาสัมพันธ์ > เพิ่มข้อมูล</h1>
                    </div>
                    <div class="col-4 text-end">
                        <a href="announcement.php" class="btn btn-maintheme">ย้อนกลับ</a>
                    </div>
                </div>
            </div>

            <div class="mt-4 my-card p-4">
                <h2 class="m-0 fw-bold fs-5">เพิ่มข้อมูลการประกาศ</h2>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="m-5">
                        <!-- Announcement ID -->
                        <div class="row align-items-center mb-3 column-gap-3">
                            <?php
                                $sql = "SELECT an_id FROM announcement";
                                $total_announcement = SQLSelectNumRow($con, $sql) + 1;
                            ?>
                            <div class="col-2"><label for="" class="form-label m-0 p-0">ลำดับที่</label></div>
                            <div class="col">
                                <input type="text" name="an_id" id="" class="form-control bg-body-secondary" required readonly style="width: 250px;" value="<?php echo $total_announcement ?>">
                            </div>
                        </div>

                        <!-- Announcement Title -->
                        <div class="row align-items-center mb-3 column-gap-3">
                            <div class="col-2"><label for="" class="form-label m-0 p-0">เรื่อง</label></div>
                            <div class="col"><input type="text" name="title" id="" class="form-control" style="width: 400px;" required></div>
                        </div>

                        <!-- Announcement Details -->
                        <div class="mb-3">
                            <label for="" class="form-label m-0 p-0 mb-2">เนื้อหา (ไม่บังคับ)</label>
                            <textarea name="detail" id="" cols="30" rows="5" class="form-control"></textarea>
                        </div>

                        <!-- Announcement Files -->
                        <div class="row align-items-center mb-3 column-gap-3">
                            <div class="col-2"><label for="" class="form-label m-0 p-0">แนบไฟล์ (ไม่บังคับ)</label></div>
                            <div class="col">
                                <input type="file" name="file" id="" class="form-control">
                                <div id="" class="form-text">การแนบไฟล์อาจเป็นเอกสาร หรือคู่มือประกอบในการอธิบาย เช่น ไฟล์ รูปภาพ, Docs, Word หรือ PDF.</div>
                            </div>
                        </div>

                        <!-- Announcements Date -->
                        <div class="row align-items-center mb-3 column-gap-3">
                            <div class="col-2"><label for="" class="form-label m-0 p-0">วันที่ประกาศ</label></div>
                            <div class="col">
                                <input type="text" class="form-control bg-body-secondary" readonly style="width: 200px;" value="<?php echo formatdatethai(date("Y-m-d")) ?>">
                                <input type="text" name="date" style="width: 200px;" value="<?php echo date("Y-m-d") ?>" hidden>
                            </div>
                        </div>

                        <!-- Form Button -->
                        <div class="mb-3 text-end">
                            <a href="announcement.php" class="btn btn-maintheme-outline">ยกเลิก</a>
                            <input type="submit" name="Submit" value="เพิ่มข้อมูล" class="btn btn-maintheme">
                        </div>

                    </div>
                </form>
            </div>

        </div>

    </div>

</body>
</html>