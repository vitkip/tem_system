<?php
session_start();
require '../db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error = '❌ รหัสผ่านไม่ตรงกัน';
    } else {
        // เข้ารหัส password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $email, 'member']);
            $success = '✅ สมัครสมาชิกสำเร็จ! ไปที่หน้า Login ได้เลย';
        } catch (PDOException $e) {
            $error = '❌ มีข้อผิดพลาดในการสมัคร: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <div class="flex justify-center mb-6">
        <img src="../assets/logo.png" alt="โลโก้" class="h-20 w-16 rounded-full">
    </div>

    <h2 class="text-2xl font-bold text-center mb-6">สมัครสมาชิก</h2>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label class="block text-gray-600">ชื่อผู้ใช้</label>
            <input type="text" name="username" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-gray-600">อีเมล</label>
            <input type="email" name="email" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-gray-600">รหัสผ่าน</label>
            <input type="password" name="password" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-6">
            <label class="block text-gray-600">ยืนยันรหัสผ่าน</label>
            <input type="password" name="confirm_password" required class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">สมัครสมาชิก</button>
    </form>

    <div class="text-center mt-4">
        <a href="../register/login.php" class="text-blue-500 underline">มีบัญชีแล้ว? เข้าสู่ระบบ</a>
    </div>
</div>

</body>
</html>
