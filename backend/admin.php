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

            if (isset($_GET['DeleteID'])) {
                $admin_id = $_GET['DeleteID'];
                
                $sql = "DELETE FROM admin WHERE admin_id = '$admin_id'";
                SQLQuery($con, $sql);

                ?>
                <script>
                    Swal.fire({
                        title: 'ลบข้อมูลสำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ยืนยัน',
                    }).then((result) => {
                        window.location = "admin.php";
                    });
                </script>
                <?php
            }

        ?>

        <div class="content">
            
            <!-- Title -->
            <div class="my-card">
                <div class="row align-items-center justify-content-between row-gap-3">
                    <div class="col-sm-3">
                        <h1 class="m-0 p-0 fs-5 fw-bold">ผู้ดูแลระบบ</h1>
                    </div>
                    <div class="col-sm-9 text-end">
                        <a href="index.php" class="btn btn-maintheme">
                            ย้อนกลับ
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-4 my-card">
                <table class="table table-stripped table-hover table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>ตำแหน่ง</th>
                            <th class="text-end">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            $sql = "SELECT admin.*, prefix.prefix_name, admin_position.* 
                                    FROM admin
                                    JOIN prefix ON admin.prefix_id = prefix.prefix_id
                                    LEFT JOIN admin_position ON admin.adminPos_id = admin_position.adminPos_id
                            ";

                            $total_admin = SQLSelectNumRow($con, $sql);

                            if ($total_admin > 0) {          
                                $counter = 1;                      
                                foreach (SQLSelectLoop($con, $sql) as $data) {
                                    ?>
                                    <tr>
                                        <td><?php echo $counter ?></td>
                                        <td><?php echo $data['admin_id'] ?></td>
                                        <td><?php echo $data['prefix_name'] . $data['name'] . " " . $data['lastname'] ?></td>
                                        <td>
                                            <?php
                                                if ($data['adminPos_id'] != 1) {
                                                    ?>
                                                    <p class="m-0 p-1 btn btn-primary text-light"><?php echo $data['adminPos_name'] ?></p>
                                                    <?php
                                                }
                                                else {
                                                    ?>
                                                    <p class="m-0 p-0 btn btn-success text-light p-1"><?php echo $data['adminPos_name'] ?></p>
                                                    <?php
                                                }
                                            ?>
                                        </td>
                                        <td class="text-end">
                                            <?php
                                                if ($_SESSION['AdminID'] == $data['admin_id']) {
                                                    ?>
                                                    <span class="bg-secondary text-light p-1 rounded fw-bold">บัญชีที่ใช้งานอยู่ ณ ตอนนี้</span>
                                                    <a href="admin-edit.php?admin_id=<?php echo $data['admin_id'] ?>" class="btn btn-warning text-light">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a>
                                                    <?php
                                                }
                                                else {
                                                    if ($_SESSION['AdminPosition'] == 1) {
                                                        ?>
                                                        <a href="admin-edit.php?admin_id=<?php echo $data['admin_id'] ?>" class="btn btn-warning text-light">
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </a>
                                                        <button class="btn btn-danger text-light" onclick="AdminDelete('<?php echo $data['admin_id'] ?>')">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                        <?php
                                                    }
                                                    else {
                                                        ?>
                                                        <span class="bg-danger text-light p-1 rounded fw-bold">เป็นสิทธิ์สำหรับผู้ดูแลสูงสุด</span>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $counter++; 
                                }
                            }
                            else {
                                ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="alert alert-warning m-0" role="alert">
                                            ไม่มีข้อมูล
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
                <?php
                    if ($_SESSION['AdminPosition'] == 1) {
                        ?>
                        <div class="text-end">
                            <a href="admin-add.php" class="btn btn-maintheme">เพิ่มข้อมูลผู้ดูแล</a>
                        </div>
                        <?php
                    }
                ?>
            </div>

        </div>

        <!-- Totoal Summarize -->
        <div class="container fixed-bottom mb-5">
            <p class="bg-primary text-light p-2 m-0 rounded fw-bolder fs-6 position-absolute end-0 bottom-50" style="width: fit-content;">
                ผู้ดูแลทั้งหมด <?php echo $total_admin; ?> บัญชี
            </p>
        </div>

    </div>

    <!-- Js -->
    <script>
        function AdminDelete(id) {
            Swal.fire({
                title: 'การลบข้อมูล',
                text: "ยืนยันการลบผู้ดูแลรหัส :" + id,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'admin.php?DeleteID=' + id;
                }
            });
        }
    </script>
    

</body>
</html>