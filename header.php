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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดเก็บข้อมูลพระสงฆ์-แม่ชี-เณร</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- jQuery และ DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between">
            <!-- Logo + Menu -->
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-8 w-8" src="assets/logo.png" alt="Logo">
                </div>
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-4 ml-6">
                    <a href="dashboard.php" class="text-gray-700 hover:text-indigo-600 font-medium px-3 py-2 rounded-md">Dashboard</a>
                    <a href="list_monks.php" class="text-gray-700 hover:text-indigo-600 font-medium px-3 py-2 rounded-md">ລວມລາຍຊື່</a>
                    <a href="add_monk.php" class="text-gray-700 hover:text-indigo-600 font-medium px-3 py-2 rounded-md">ເພີ່ມ</a>
                    <a href="#" class="text-gray-700 hover:text-indigo-600 font-medium px-3 py-2 rounded-md">Calendar</a>
                </div>
            </div>

            <!-- Search - Hidden on mobile -->
            <div class="hidden md:flex-1 md:flex md:justify-center px-2 max-w-lg">
                <div class="w-full">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0a7 7 0 1110 0 7 7 0 01-10 0z"/>
                            </svg>
                        </div>
                        <input id="search" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search" type="search">
                    </div>
                </div>
            </div>

            <!-- Right side menu -->
            <div class="flex items-center space-x-4">
                <!-- Mobile menu button -->
                <button type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-indigo-600 focus:outline-none" id="mobile-menu-button">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <button type="button" class="relative hidden md:inline-flex">
                    <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405M15 17a3.5 3.5 0 01-6.586-1.586A3.5 3.5 0 0115 17zM7 7h10M7 7a4 4 0 114 4h-1a4 4 0 01-3-4z"/>
                    </svg>
                </button>

                <!-- Profile dropdown -->
                <div class="relative">
                    <button type="button" class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button">
                        <span class="sr-only">Open user menu</span>              
                        <img src="uploads/<?= htmlspecialchars($_SESSION['profile_image'] ?? 'default.png') ?>" alt="โปรไฟล์" class="h-8 w-8 rounded-full border">
                        <span class="font-medium hidden md:inline-block"><?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?></span>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50">
                        <a href="profile/profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ໂປຣຟາຍ</a>
                        <a href="profile/edit_profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ຕັ້ງຄ່າ</a>
                        <a href="register/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ອອກຈາກລະບົບ</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="dashboard.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Dashboard</a>
                <a href="list_monks.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">ລວມລາຍຊື່</a>
                <a href="add_monk.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">ເພີ່ມ</a>
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Calendar</a>
            </div>
            <!-- Mobile Search -->
            <div class="px-2 pt-2 pb-3">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0a7 7 0 1110 0 7 7 0 01-10 0z"/>
                        </svg>
                    </div>
                    <input id="mobile-search" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search" type="search">
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Updated toggle scripts -->
<script>
    // Toggle user dropdown
    const userButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('userDropdown');

    userButton.addEventListener('click', () => {
        userDropdown.classList.toggle('hidden');
    });

    // Toggle mobile menu
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (event) => {
        if (!userButton.contains(event.target) && !userDropdown.contains(event.target)) {
            userDropdown.classList.add('hidden');
        }
        if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
            mobileMenu.classList.add('hidden');
        }
    });
</script>

<!-- Main content เริ่มตรงนี้ -->
<div class="p-6">
