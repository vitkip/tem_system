<?php
session_start();
require '../db.php'; // หรือ 'db.php' ถ้า login.php อยู่โฟลเดอร์เดียวกับ db.php

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            // ตั้ง session หลัง login สำเร็จ
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profile_image'] = $user['profile_image']; // <-- เพิ่มบรรทัดนี้


            header('Location:../dashboard.php');
            exit();
        } else {
            $error = '❌ รหัสผ่านไม่ถูกต้อง';
        }
    } else {
        $error = '❌ ไม่พบชื่อผู้ใช้งานนี้';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <div class="flex justify-center mb-6">
        <img src="../assets/logo.png" alt="โลโก้" class="h-20 w-16 rounded-full">
    </div>

    <h2 class="text-2xl font-bold text-center mb-6">เข้าสู่ระบบ</h2>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label class="block text-gray-600">ชื่อผู้ใช้</label>
            <input type="text" name="username" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-gray-600">รหัสผ่าน</label>
            <input type="password" name="password" required class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">เข้าสู่ระบบ</button>
    </form>

    <div class="text-center mt-4">
        <a href="../register/register.php" class="text-blue-500 underline">ยังไม่มีบัญชี? สมัครสมาชิก</a>
    </div>
</div>

</body>
</html>
