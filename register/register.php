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
        $error = '❌ ລະຫັດຜ່ານບໍ່ກົງກັນ';
    } else {
        // ເຂົ້າລະຫັດ password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $email, 'member']);
            $success = '✅ ສະໝັກສະມາຊິກສຳເລັດ! ສາມາດໄປໜ້າເຂົ້າລະບົບໄດ້';
        } catch (PDOException $e) {
            $error = '❌ ເກີດຂໍ້ຜິດພາດໃນການສະໝັກ: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>ລົງທະບຽນສະມາຊິກ</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Lao', sans-serif;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <div class="flex justify-center mb-6">
        <img src="<?= BASE_URL ?>assets/logo.png" alt="ໂລໂກ້" class="h-20 w-16 rounded-full">
    </div>

    <h2 class="text-2xl font-bold text-center mb-6">ລົງທະບຽນສະມາຊິກ</h2>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label class="block text-gray-600">ຊື່ຜູ້ໃຊ້</label>
            <input type="text" name="username" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-gray-600">ອີເມວ</label>
            <input type="email" name="email" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-gray-600">ລະຫັດຜ່ານ</label>
            <input type="password" name="password" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-6">
            <label class="block text-gray-600">ຢືນຢັນລະຫັດຜ່ານ</label>
            <input type="password" name="confirm_password" required class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">ລົງທະບຽນ</button>
    </form>

    <div class="text-center mt-4">
        <a href="<?= BASE_URL ?>register/login.php" class="text-blue-500 underline">ມີບັນຊີແລ້ວ? ເຂົ້າລະບົບ</a>
    </div>
</div>

</body>
</html>
