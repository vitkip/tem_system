<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ຖ້າບໍ່ໄດ້ login ໃຫ້ກັບໄປຫນ້າ login
if (!isset($_SESSION['user_id'])) {
    header('Location: register/login.php');
    exit();
}

require '../db.php'; // ເຊື່ອມຕໍ່ຖານຂໍ້ມູນ

// ດຶງຂໍ້ມູນຜູ້ໃຊ້ຈາກ session
$username = $_SESSION['username'];
$email = $_SESSION['email'] ?? '';
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>ໂປຣໄຟລ໌ຂອງຂ້ອຍ</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao+Looped:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Lao Looped', sans-serif;
            font-size: 16px;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <div class="flex justify-center mb-6">
        <img src="../uploads/<?= htmlspecialchars($_SESSION['profile_image'] ?? 'default.png') ?>" alt="ໂປຣໄຟລ໌" class="h-30 w-25 rounded-full border">
    </div>

    <h2 class="text-2xl font-bold text-center mb-4 uppercase"><?= htmlspecialchars($username) ?></h2>

    <div class="space-y-3 text-gray-700">
        <p><strong>ຊື່ຜູ້ໃຊ້:</strong> <?= htmlspecialchars($username) ?></p>
        <p><strong>ອີເມວ:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>ສິດທິ:</strong> <?= htmlspecialchars($role) ?></p>
    </div>

    <div class="mt-6 text-center">
        <a href="../dashboard.php" class="inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">ກັບໄປໜ້າ Dashboard</a>
        <a href="../register/logout.php" class="inline-block bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 ml-2">ອອກຈາກລະບົບ</a>
    </div>
</div>

</body>
</html>
