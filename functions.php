<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// เพิ่มฟังก์ชันอื่นๆ ที่ต้องใช้ร่วมกัน
function checkPermission() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /tem_system/register/login.php');
        exit();
    }
}

function checkAdminPermission() {
    if (!isAdmin()) {
        die('ທ່ານບໍ່ມີສິດເຂົ້າໃຊ້ງານ');
    }
}