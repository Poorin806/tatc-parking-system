<?php

    //Import connect.php file
    include_once('connect.php');

    function SQLSelectLoop($con, $sql) {
        $result = $con->query($sql);
        $data = array();
        while ($row = mysqli_fetch_array($result)) {
            $data[] = $row;
        }

        return $data;
    }

    function SQLSelect($con, $sql) {
        $result = $con->query($sql);
        $data = mysqli_fetch_array($result);

        return $data;
    }

    function SQLSelectAssoc($con, $sql) {
        $result = $con->query($sql);
        $data = mysqli_fetch_assoc($result);

        return $data;
    }

    function SQLSelectNumRow($con, $sql) {
        $result = $con->query($sql);
        $num = mysqli_num_rows($result);

        return $num;
    }

    function SQLQuery($con, $sql) {
        $result = $con->query($sql);

        return $result;
    }

    // $sql = "SELECT * FROM student";
    // foreach (SQLSelectLoop($con, $sql) as $data) {
    //     echo $data['std_id']."<br>";
    // }

    function formatDateThai($date) {
        // แปลงวันที่สากลเป็น timestamp
        $timestamp = strtotime($date);

        // สร้างรูปแบบวันที่ใหม่สำหรับแสดงผล (ใช้ d, M, Y และแทนที่เดือนแบบอังกฤษเป็นไทย)
        $formatThai = 'd M Y'; // รูปแบบวันที่แบบไทย (เช่น "25 ก.ค. 2566")

        // ใช้ฟังก์ชัน date() เพื่อแปลง timestamp เป็นวันที่แบบไทย
        $formattedDate = date($formatThai, $timestamp);

        // แปลงปีให้เป็น พ.ศ.
        $yearThai = date('Y', $timestamp) + 543;
        $formattedDate = str_replace(date('Y', $timestamp), $yearThai, $formattedDate);

        // แทนที่ชื่อเดือนแบบอังกฤษเป็นชื่อเดือนแบบไทย
        $formattedDate = str_replace(
            array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
            array('ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'),
            $formattedDate
        );

        return $formattedDate;
    }

    function formatDateThai_Full($date) {
        // แปลงวันที่สากลเป็น timestamp
        $timestamp = strtotime($date);

        // สร้างรูปแบบวันที่ใหม่สำหรับแสดงผล (ใช้ d, M, Y และแทนที่เดือนแบบอังกฤษเป็นไทย)
        $formatThai = 'd M Y'; // รูปแบบวันที่แบบไทย (เช่น "25 ก.ค. 2566")

        // ใช้ฟังก์ชัน date() เพื่อแปลง timestamp เป็นวันที่แบบไทย
        $formattedDate = date($formatThai, $timestamp);

        // แปลงปีให้เป็น พ.ศ.
        $yearThai = date('Y', $timestamp) + 543;
        $formattedDate = str_replace(date('Y', $timestamp), $yearThai, $formattedDate);

        // แทนที่ชื่อเดือนแบบอังกฤษเป็นชื่อเดือนแบบไทย
        $formattedDate = str_replace(
            array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
            array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'),
            $formattedDate
        );

        return $formattedDate;
    }

    function formatDateTimeThai($date) {
        // แปลงวันที่สากลเป็น timestamp
        $timestamp = strtotime($date);

        // สร้างรูปแบบวันที่ใหม่สำหรับแสดงผล (ใช้ d, M, Y และแทนที่เดือนแบบอังกฤษเป็นไทย)
        $formatThai = 'd M Y (H:m:s น.)'; // รูปแบบวันที่แบบไทย (เช่น "25 ก.ค. 2566")

        // ใช้ฟังก์ชัน date() เพื่อแปลง timestamp เป็นวันที่แบบไทย
        $formattedDate = date($formatThai, $timestamp);

        // แปลงปีให้เป็น พ.ศ.
        $yearThai = date('Y', $timestamp) + 543;
        $formattedDate = str_replace(date('Y', $timestamp), $yearThai, $formattedDate);

        // แทนที่ชื่อเดือนแบบอังกฤษเป็นชื่อเดือนแบบไทย
        $formattedDate = str_replace(
            array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
            array('ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'),
            $formattedDate
        );

        return $formattedDate;
    }

    function formatDateTimeThai_Full($date) {
        // แปลงวันที่สากลเป็น timestamp
        $timestamp = strtotime($date);

        // สร้างรูปแบบวันที่ใหม่สำหรับแสดงผล (ใช้ d, M, Y และแทนที่เดือนแบบอังกฤษเป็นไทย)
        $formatThai = 'd M Y (H:m:s น.)'; // รูปแบบวันที่แบบไทย (เช่น "25 ก.ค. 2566")

        // ใช้ฟังก์ชัน date() เพื่อแปลง timestamp เป็นวันที่แบบไทย
        $formattedDate = date($formatThai, $timestamp);

        // แปลงปีให้เป็น พ.ศ.
        $yearThai = date('Y', $timestamp) + 543;
        $formattedDate = str_replace(date('Y', $timestamp), $yearThai, $formattedDate);

        // แทนที่ชื่อเดือนแบบอังกฤษเป็นชื่อเดือนแบบไทย
        $formattedDate = str_replace(
            array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
            array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'),
            $formattedDate
        );

        return $formattedDate;
    }

?>