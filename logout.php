<?php

    session_start();
    session_destroy();

    echo "<scirpt>alert('ออกจากระบบ สำเร็จ!');</scirpt>";
    echo "<script>window.location='login.php';</script>";

?>