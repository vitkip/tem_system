<?php
session_start();
require 'db.php';
require 'functions.php';

// ตรวจสอบสิทธิ์ก่อนทำการลบ
checkPermission();
checkAdminPermission();

// ตรวจสอบ ID
if (!isset($_GET['id'])) {
    header('Location: list_monks.php');
    exit();
}

$id = $_GET['id'];

try {
    // ดึงข้อมูลรูปภาพ
    $stmt = $pdo->prepare("SELECT photo FROM monks WHERE id = ?");
    $stmt->execute([$id]);
    $monk = $stmt->fetch();

    // ลบไฟล์รูปภาพ
    if ($monk && !empty($monk['photo'])) {
        $photoPath = "uploads/" . $monk['photo'];
        if (file_exists($photoPath)) {
            unlink($photoPath);
        }
    }

    // ลบข้อมูลจากฐานข้อมูล
    $stmt = $pdo->prepare("DELETE FROM monks WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect กลับไปหน้า list
    header('Location: list_monks.php?deleted=success');
    exit();
    
} catch (PDOException $e) {
    // บันทึก error log
    error_log("Delete monk error: " . $e->getMessage());
    
    // ย้ายการ include header มาที่นี่หลังจากการ redirect ไม่สำเร็จ
    include 'header.php';
    echo '<div class="container mx-auto px-4 py-8">';
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">';
    echo 'ເກີດຂໍ້ຜິດພາດໃນການລົບຂໍ້ມູນ. ກະລຸນາລອງໃໝ່ອີກຄັ້ງ.';
    echo '</div>';
    echo '</div>';
}
?>
