<?php
ob_start(); // เริ่มจับ buffer output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header('Location: ../register/login.php');
    exit();
}
// ฟังก์ชันเช็ก Admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
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
        }
    </style>
</head>

<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <!-- Logo + Menu -->
        <div class="flex items-center">
            <img src="/tem_system/assets/logo.png" alt="Logo" class="h-10 w-10 mr-4">
            <div class="hidden md:flex space-x-6">

                <a href="/tem_system/dashboard.php" class="px-3 py-2 rounded-md font-medium 
                    <?= ($current_page == 'dashboard.php') ? 'text-indigo-600 underline' : 'text-gray-700 hover:text-indigo-600' ?>">
                     Dashboard</a>

                <a href="/tem_system/list_monks.php" class="px-3 py-2 rounded-md font-medium 
                    <?= ($current_page == 'list_monks.php') ? 'text-indigo-600 underline' : 'text-gray-700 hover:text-indigo-600' ?>">
                    ລວມລາຍຊື່</a>

                <?php if (isAdmin()): ?>
                <a href="/tem_system/add_monk.php" class="px-3 py-2 rounded-md font-medium 
                    <?= ($current_page == 'add_monk.php') ? 'text-indigo-600 underline' : 'text-gray-700 hover:text-indigo-600' ?>">
                    ເພີ່ມ</a>
                

                <a href="/tem_system/event/add_event.php" class="px-3 py-2 rounded-md font-medium 
                    <?= ($current_page == 'add_event.php') ? 'text-indigo-600 underline' : 'text-gray-700 hover:text-indigo-600' ?>">
                    ເພີ່ມງານກິດ</a>
                    <?php endif; ?>
            </div>
            
        </div>

        <!-- User dropdown -->
        <div class="relative">
            <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                <img src="/tem_system/uploads/<?= htmlspecialchars($_SESSION['profile_image'] ?? 'default.png') ?>" class="h-10 w-10 rounded-full border" alt="Profile">
                <span class="font-semibold text-gray-700 hidden md:inline"><?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?></span>
            </button>

            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded shadow-lg py-2 z-50">
                <a href="/tem_system/profile/profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">ໂປຣໄຟລ໌</a>
                <a href="/tem_system/profile/edit_profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">ຕັ້ງຄ່າ</a>
                <?php if (isAdmin()): ?>
                <a href="/tem_system/admin/manage_users.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">ຈັດການຜູ້ໃຊ້</a>
                <a href="/tem_system/event/add_event.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">ເພີ່ມກິດນິມົນ</a>
                <a href="/tem_system/event/assign_monks.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">ເລືອກພຣະໄປງານ</a>
                <a href="/tem_system/event/calendar.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">ປະຕິທິນງານ</a>
                <?php endif; ?>
                <a href="/tem_system/register/logout.php" class="block px-4 py-2 text-red-500 hover:bg-red-100">ອອກຈາກລະບົບ</a>
            </div>
        </div>
    </div>
</nav>

<!-- Script: Toggle dropdown -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        const userMenuButton = document.getElementById('userMenuButton');
        const userDropdown = document.getElementById('userDropdown');
        
        if (userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', function() {
                userDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }
    } catch (error) {
        console.error('Error in dropdown functionality:', error);
    }
});
</script>

<!-- Main Content เริ่มตรงนี้ -->
<div class="p-6">
