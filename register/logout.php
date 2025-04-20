<?php
session_start();
session_destroy(); // ล้าง session ทั้งหมด
header('Location: login.php'); // กลับไปหน้า login
exit();
?>
