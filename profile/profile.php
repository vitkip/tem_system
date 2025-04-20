<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ถ้าไม่ได้ล็อกอิน กลับไปหน้า login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../register/login.php');
    exit();
}

require '../db.php'; // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลผู้ใช้จาก session
$username = $_SESSION['username'];
$email = $_SESSION['email'] ?? '';
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>โปรไฟล์ของฉัน</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <div class="flex justify-center mb-6">
    <img src="../uploads/<?= htmlspecialchars($_SESSION['profile_image'] ?? 'default.png') ?>" alt="โปรไฟล์" class="h-30 w-25 rounded-full border">
    </div>

    <h2 class="text-2xl font-bold text-center mb-4 uppercase"><?= htmlspecialchars($username) ?></h2>

    <div class="space-y-3 text-gray-700">
        <p><strong>ชื่อผู้ใช้:</strong> <?= htmlspecialchars($username) ?></p>
        <p><strong>อีเมล:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>สิทธิ์:</strong> <?= htmlspecialchars($role) ?></p>
    </div>

    <div class="mt-6 text-center">
        <a href="../dashboard.php" class="inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">กลับหน้าแดชบอร์ด</a>
        <a href="../register/logout.php" class="inline-block bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 ml-2">ออกจากระบบ</a>
    </div>
</div>

</body>
</html>
