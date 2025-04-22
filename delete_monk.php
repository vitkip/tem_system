<?php
session_start();
require 'db.php';
include 'header.php';

// ✅ เช็กสิทธิ์
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ເຂົ້າໃຊ້ໄດ້ສຳລັບ Admin ເທົ່ານັ້ນ!</div>');
}


if (!isset($_SESSION['user_id'])) {
    header('Location: register/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: list_monks.php');
    exit();
}

$id = $_GET['id'];

// ດຶງຊື່ໄຟລ໌ຮູບຖ່າຍເພື່ອຈະລົບໄຟລ໌ໃນ server ດ້ວຍ
$stmt = $pdo->prepare("SELECT photo FROM monks WHERE id = ?");
$stmt->execute([$id]);
$monk = $stmt->fetch();

if ($monk && !empty($monk['photo'])) {
    $photoPath = "uploads/" . $monk['photo'];
    if (file_exists($photoPath)) {
        unlink($photoPath); // ລົບໄຟລ໌ຮູບອອກ
    }
}

// ລົບຂໍ້ມູນໃນຖານຂໍ້ມູນ
$stmt = $pdo->prepare("DELETE FROM monks WHERE id = ?");
$stmt->execute([$id]);

// ຫຼັງຈາກລົບແລ້ວ ➔ redirect ກັບໄປໜ້າ list ພ້ອມສົ່ງຂໍ້ຄວາມ deleted=success
header('Location: list_monks.php?deleted=success');
exit();
?>
