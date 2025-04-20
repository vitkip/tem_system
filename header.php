<?php
// ต้องเริ่ม session ก่อนใช้ session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจัดเก็บข้อมูลพระสงฆ์-แม่ชี-เณร</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- jQuery และ DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-50">
    <!-- Logo + Menu -->
    <div class="flex items-center space-x-6">
        <div class="flex-shrink-0">
            <img class="h-8 w-8" src="assets/logo.png" alt="Logo">
        </div>
        <div class="hidden md:flex space-x-4">
            <a href="dashboard.php" class="text-gray-700 hover:text-indigo-600 font-medium">Dashboard</a>
            <a href="list_monks.php" class="text-gray-700 hover:text-indigo-600 font-medium">ລວມລາຍຊື່</a>
            <a href="add_monk.php" class="text-gray-700 hover:text-indigo-600 font-medium">ເພີ່ມ</a>
            <a href="#" class="text-gray-700 hover:text-indigo-600 font-medium">Calendar</a>
        </div>
    </div>


    <!-- Profile menu -->
    <div class="flex items-center space-x-4">
        <button type="button" class="relative">
            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405M15 17a3.5 3.5 0 01-6.586-1.586A3.5 3.5 0 0115 17zM7 7h10M7 7a4 4 0 114 4h-1a4 4 0 01-3-4z"/>
            </svg>
        </button>

        <!-- Profile dropdown -->
        <div class="relative">
            <button type="button" class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                <span class="sr-only">Open user menu</span>              
                <img src="uploads/<?= htmlspecialchars($_SESSION['profile_image'] ?? 'default.png') ?>" alt="โปรไฟล์" class="h-10 w-10 rounded-full border">
                <span class="font-medium"><?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?></span>
            </button>

            <!-- Dropdown menu -->
            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50">
                <a href="profile/profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ໂປຣຟາຍ</a>
                <a href="profile/edit_profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ຕັ້ງຄ່າ</a>
                <a href="register/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ອອກຈາກລະບົບ</a>
            </div>
        </div>
    </div>
</nav>

<!-- Script toggle dropdown -->
<script>
    const userButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('userDropdown');

    userButton.addEventListener('click', () => {
        userDropdown.classList.toggle('hidden');
    });
</script>

<!-- Main content เริ่มตรงนี้ -->
<div class="p-6">
