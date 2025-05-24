<?php
session_start();
require_once '../db.php'; // Include db.php to access BASE_URL constant
session_destroy(); // ล้าง session ทั้งหมด
header('Location: ' . BASE_URL . 'index.php'); // กลับไปหน้า login
exit();
?>
