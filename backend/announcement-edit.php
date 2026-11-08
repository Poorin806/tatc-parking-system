<?php

    //Import connect.php file
    include_once('../import/connect.php');

    //Import SQL Query file
    include_once('../import/SQLQuery.php');

    //Logged in checking
    include_once('LoginChecking.php');

    //Check Announcement Edit ID
    if (!isset($_GET['ID'])) {
        echo "<script>window.location = 'announcement.php';</script>";
    }
    else {
        $an_id = $_GET['ID'];
    }

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
                        
                        // //Have details and file upload
                        $sql = "UPDATE announcement
                                SET title = '$title',
                                    content = '$detail',
                                    file = '$file_upload',
                                    an_date = '$FullDate',
                                    filename = '$file_name'
                                WHERE an_id = '$an_id'

                        ";
                    }
                    else {
                        //Have details but no have file upload
                        $sql = "UPDATE announcement
                                SET title = '$title',
                                    content = '$detail',
                                    file = null,
                                    an_date = '$FullDate',
                                    filename = null
                                WHERE an_id = '$an_id'

                        ";
                    }
                }
                else {
                    if (isset($_FILES['file']) && $_FILES['file']['tmp_name'] != null) {
                        $file_data = file_get_contents($_FILES['file']['tmp_name']);
                        $file_upload = base64_encode($file_data);

                        //No have details but have file upload
                        $sql = "UPDATE announcement
                                SET title = '$title',
                                    content = null,
                                    file = '$file_upload',
                                    an_date = '$FullDate',
                                    filename = '$file_name'
                                WHERE an_id = '$an_id'

                        ";
                    }
                    else {
                        //No have details and file upload
                        $sql = "UPDATE announcement
                                SET title = '$title',
                                    content = null,
                                    file = null,
                                    an_date = '$FullDate',
                                    filename = null
                                WHERE an_id = '$an_id'

                        ";
                    }
                }

                //Query for neww announcement
                SQLQuery($con, $sql);
                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'แก้ไขข้อมูลสำเร็จ',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            window.location='announcement.php'
                        });
                    </script>";
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
                <form action="<?php echo $_SERVER['PHP_SELF'] . "?ID=$an_id" ?>" method="POST" enctype="multipart/form-data">
                    <?php
                        $sql = "SELECT * FROM announcement WHERE an_id = $an_id";
                        $data = SQLSelect($con, $sql);
                    ?>
                    <div class="m-5">
                        <!-- Announcement ID -->
                        <div class="row align-items-center mb-3 column-gap-3">
                            <div class="col-2"><label for="" class="form-label m-0 p-0">ลำดับที่</label></div>
                            <div class="col">
                                <input type="text" name="an_id" id="" class="form-control bg-body-secondary" required readonly style="width: 250px;" value="<?php echo $data['an_id'] ?>">
                            </div>
                        </div>

                        <!-- Announcement Title -->
                        <div class="row align-items-center mb-3 column-gap-3">
                            <div class="col-2"><label for="" class="form-label m-0 p-0">เรื่อง</label></div>
                            <div class="col"><input type="text" name="title" id="" class="form-control" style="width: 400px;" required value="<?php echo $data['title'] ?>"></div>
                        </div>

                        <!-- Announcement Details -->
                        <div class="mb-3">
                            <label for="" class="form-label m-0 p-0 mb-2">เนื้อหา (ไม่บังคับ)</label>
                            <textarea name="detail" id="" cols="30" rows="5" class="form-control"><?php echo $data['content'] ?></textarea>
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
                            <div class="col-2"><label for="" class="form-label m-0 p-0">วันที่แก้ไขประกาศ</label></div>
                            <div class="col">
                                <input type="text" class="form-control bg-body-secondary" readonly style="width: 200px;" value="<?php echo formatdatethai(date("Y-m-d")) ?>">
                                <input type="text" name="date" style="width: 200px;" value="<?php echo date("Y-m-d") ?>" hidden>
                            </div>
                        </div>

                        <!-- Form Button -->
                        <div class="mb-3 text-end">
                            <a href="announcement.php" class="btn btn-maintheme-outline">ยกเลิก</a>
                            <input type="submit" name="Submit" value="แก้ไข" class="btn btn-maintheme">
                        </div>

                    </div>
                </form>
            </div>

        </div>

    </div>

</body>
</html>