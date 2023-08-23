<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/qrcode.js"></script>
</head>
<body>

    <input id="text" type="text" value="" style="width:80%" /><br />
    <button onclick="makeCode()">Generate QR-Code</button>
    <button onclick="saveQRCodeToDatabase()">Insert to Database</button>
    <div id="qrcode" style="width:100px; height:100px; margin-top:15px;"></div>
    
    <!-- Essentials JavaScript -->
    <?php include_once 'import/js.php'; ?>

    <script type="text/javascript">
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            width: 100,
            height: 100
        });

        function makeCode() {
            var elText = document.getElementById("text");

            if (!elText.value) {
                alert("กรุณาป้อนข้อความ");
                return;
            }

            qrcode.makeCode(elText.value);

            // เรียกใช้งาน AJAX เพื่อส่งข้อมูล QR-Code ไปบันทึกในฐานข้อมูล
            // var qrCodeData = document.getElementById("qrcode").getElementsByTagName("img")[0].src;
            // saveQRCodeToDatabase(qrCodeData);

        }

        function saveQRCodeToDatabase() {

            var qrCodeData = document.getElementById("qrcode").getElementsByTagName("img")[0].src;

            $.ajax({
                type: "POST",
                url: "ajax_db.php",
                data: {
                    qrCodeData: qrCodeData
                },
                success: function(response) {
                    alert(response);
                },
                error: function(xhr, status, error) {
                    alert("เกิดข้อผิดพลาดในการบันทึก QR-Code: " + error);
                }
            });
        }
    </script>


</body>
</html>