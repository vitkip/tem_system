<?php
session_start();
require_once 'db.php'; // Include db.php to access BASE_URL constant

// ถ้า login แล้ว ส่งเข้า dashboard เลย
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ລະບົບຈັດການຂໍ້ມູນພຣະສົງ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Lao', sans-serif;
            background: linear-gradient(135deg, #c3e0e5, #f9f9f9);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            animation: fadeIn 1.5s ease-in;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .button-animated {
            transition: all 0.3s ease;
        }

        .button-animated:hover {
            transform: translateY(-2px) scale(1.05);
        }
    </style>
</head>

<body class="text-center p-6">

    <img src="<?= BASE_URL ?>assets/logo.png" alt="Logo" class="h-40 w-30 mb-6 rounded-full shadow-xl animate-pulse">

    <h2 class="text-5xl md:text-4xl font-bold text-indigo-500 mb-4 animate-bounce">ວັດປ່າໜອງບົວທອງໃຕ້ |ມະຫາຣຸກຂາວະຣາຣາມ|</h2>
    <p class="text-gray-600 text-lg mb-8 animate-fadeIn delay-500">ບໍລິຫານຂໍ້ມູນພຣະ, ແມ່ຂາວ, ສາມະເນນ ຢ່າງມືອາຊີບ ແລະປອດໄພ</p>

    <div class="flex flex-col md:flex-row gap-6">
        <a href="<?= BASE_URL ?>register/login.php" class="px-8 py-3 bg-indigo-600 text-white rounded-full button-animated shadow-lg hover:bg-indigo-700 text-lg">
            ເຂົ້າລະບົບ
        </a>
        <a href="<?= BASE_URL ?>register/register.php" class="px-8 py-3 bg-white border border-indigo-600 text-indigo-600 rounded-full button-animated shadow-lg hover:bg-indigo-100 text-lg">
            ສະໝັກສະມາຊິກ
        </a>
    </div>

    <footer class="mt-12 text-gray-400 text-sm">
        © <?= date('Y') ?> ວັດປ່າໜອງບົວທອງໃຕ້. All rights reserved.
    </footer>

</body>
</html>
