<?php
require '../db.php';
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header('Location: ../register/login.php');
    exit();
}

// ต้องเป็น admin เท่านั้นที่ลบได้
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ບໍ່ມີສິດລົບຂໍ້ມູນ</div>');
}

// รับค่า id
if (!isset($_GET['id'])) {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ບໍ່ພົບງານທີ່ຈະລົບ</div>');
}

$id = (int)$_GET['id'];

// ลบงานจากฐานข้อมูล
$stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
$stmt->execute([$id]);

// หลังลบเสร็จ กลับไปยังรายการ
header('Location: list_events.php?deleted=success');
exit();
?>
