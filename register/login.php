<?php
session_start();
require '../db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            // ຕັ້ງ session ຫຼັງຈາກ login ສຳເລັດ
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profile_image'] = $user['profile_image'];

            header('Location:../dashboard.php');
            exit();
        } else {
            $error = '❌ ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ';
        }
    } else {
        $error = '❌ ບໍ່ພົບຊື່ຜູ້ໃຊ້ນີ້';
    }
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>ເຂົ້າລະບົບ</title>
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
        <img src="../assets/logo.png" alt="ໂລໂກ້" class="h-20 w-16 rounded-full">
    </div>

    <h2 class="text-2xl font-bold text-center mb-6">ເຂົ້າລະບົບ</h2>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label class="block text-gray-600">ຊື່ຜູ້ໃຊ້</label>
            <input type="text" name="username" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-gray-600">ລະຫັດຜ່ານ</label>
            <input type="password" name="password" required class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">ເຂົ້າລະບົບ</button>
    </form>

    <div class="text-center mt-4">
        <a href="../register/register.php" class="text-blue-500 underline">ຍັງບໍ່ມີບັນຊີ? ລົງທະບຽນ</a>
    </div>
</div>

</body>
</html>
