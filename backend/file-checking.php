<?php

    //Import connect.php file
    include_once('../import/connect.php');

    //Import SQL Query file
    include_once('../import/SQLQuery.php');

    //Logged in checking
    include_once('LoginChecking.php');

    if (isset($_GET['regis_id'])) {
        $regis_id = $_GET['regis_id'];

        if (isset($_GET['filename'])) {
            $GET_file = $_GET['filename'];

            $sql = "SELECT * FROM registration WHERE regis_id = $regis_id";

            // echo $GET_file . ", " . $GET_filename;
            if (SQLSelectNumRow($con, $sql) > 0) {
                $data = SQLSelect($con, $sql);

                switch ($GET_file) {
                    case 'img_front' :
                        $file = $data['img_front'];
                        $filename = $data['front_filename'];
                        break;
                    case 'img_back' :
                        $file = $data['img_back'];
                        $filename = $data['back_filename'];
                        break;
                    case 'img_side' :
                        $file = $data['img_side'];
                        $filename = $data['side_filename'];
                        break;
                    case 'copyOf_VehicleRegis' :
                        $file = $data['copyOf_VehicleRegis'];
                        $filename = $data['VehicleRegis_filename'];
                        break;
                    case 'copyOf_ownerIdCard' :
                        $file = $data['copyOf_ownerIdCard'];
                        $filename = $data['ownerIdCard_filename'];
                        break;
                    case 'copyOf_userIdCard' :
                        $file = $data['copyOf_userIdCard'];
                        $filename = $data['userIdCard_filename'];
                        break;
                    case 'pdfSignature':
                        $file = $data['pdfSignature'];
                        $filename = $data['pdfSign_filename'];
                        break;
                    default :
                        $file = $data['copyOf_VehicleRegis'];
                        $filename = $data['VehicleRegis_filename'];
                        break;
                }
            
                // ตรวจสอบว่าเป็นการเรียกดูไฟล์ หรือ ดาวน์โหลดไฟล์
                if (isset($_GET['Download'])) {
                    header('Content-Type: application/octet-stream');
                    header("Content-Disposition: inline; filename=\"" . $filename . "\"");
                }
                else {
                    // ตรวจสอบประเภทไฟล์จากนามสกุล (เช่น .pdf, .jpg, .png, .docx)
                    $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                    // ตั้งค่า Content-Type ตามประเภทไฟล์
                    switch ($file_extension) {
                        case "pdf":
                            header('Content-Type: application/pdf');
                            break;
                        case "jpg":
                        case "jpeg":
                            header('Content-Type: image/jpeg');
                            break;
                        case "png":
                            header('Content-Type: image/png');
                            break;
                        case "docx":
                            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                            break;
                        // เพิ่มประเภทไฟล์อื่น ๆ ตามความต้องการ
                        default:
                            header('Content-Type: application/octet-stream');
                            header("Content-Disposition: inline; filename=\"" . $filename . "\"");
                            break;
                    }
                }
            
                // แสดงไฟล์หรือรูปภาพ
                echo $file;
            } else {
                ?>
                <script>
                    alert('ไม่พบไฟล์');
                    window.close();
                </script>
                <?php
            }

        }
        else {
            ?>
                <script>
                    alert('ไม่พบไฟล์');
                    window.close();
                </script>
            <?php
        }

    }
    else {
        $regis_id = 0;
    }

?>