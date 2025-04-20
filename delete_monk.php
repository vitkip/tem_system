<?php
require 'db.php';

if (!isset($_GET['id'])) {
    die('ไม่พบข้อมูลที่ต้องการลบ');
}

$id = $_GET['id'];

// ดึงข้อมูลรูปก่อนลบ
$stmt = $pdo->prepare("SELECT photo FROM monks WHERE id = ?");
$stmt->execute([$id]);
$monk = $stmt->fetch();

if (!$monk) {
    die('ไม่พบข้อมูลในฐานข้อมูล');
}

// ลบรูปถ้ามี
if (!empty($monk['photo']) && file_exists("uploads/" . $monk['photo'])) {
    unlink("uploads/" . $monk['photo']);
}

// ลบข้อมูลในฐานข้อมูล
$stmt = $pdo->prepare("DELETE FROM monks WHERE id = ?");
$stmt->execute([$id]);

echo "ลบข้อมูลเรียบร้อยแล้ว!";
header("refresh:1; url=list_monks.php");
exit;
?>
