<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['user_id'])) {
    header('Location: ../register/login.php');
    exit();
}

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
    <link rel="icon" type="image/x-icon" href="/tem_system/assets/favicons.ico">
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao+Looped:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Lao Looped', sans-serif;
            font-size: 16px;
        }
    </style>
</head>

<body class="bg-gray-100">
<!-- START: Responsive Modern Navbar -->
<nav class="bg-white border-b border-gray-200 shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo + Main Nav -->
            <div class="flex items-center space-x-6">
                <!-- Logo -->
                <a href="/tem_system/dashboard.php">
                    <img src="/tem_system/assets/logo.png" alt="Logo" class="h-10 w-10">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-6">
                    <a href="/tem_system/dashboard.php"
                       class="text-sm font-medium <?= ($current_page == 'dashboard.php') ? 'text-indigo-600 underline' : 'text-gray-700 hover:text-indigo-600' ?>">
                        Dashboard
                    </a>
                    <a href="/tem_system/list_monks.php"
                       class="text-sm font-medium <?= ($current_page == 'list_monks.php') ? 'text-indigo-600 underline' : 'text-gray-700 hover:text-indigo-600' ?>">
                        ລວມລາຍຊື່
                    </a>
                    <a href="/tem_system/event/calendar.php"
                       class="text-sm font-medium <?= ($current_page == 'calendar.php') ? 'text-indigo-600 underline' : 'text-gray-700 hover:text-indigo-600' ?>">
                        ປະຕິທິນ
                    </a>
                </div>
            </div>

            <!-- Right side: User dropdown + hamburger -->
            <div class="flex items-center space-x-4">
                <!-- Hamburger (Mobile) -->
                <button class="md:hidden focus:outline-none" id="mobileMenuButton">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- User Menu -->
                <div class="relative">
                    <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                        <img src="/tem_system/uploads/<?= htmlspecialchars($_SESSION['profile_image'] ?? 'default.png') ?>"
                             class="h-9 w-9 rounded-full object-cover border" alt="Profile">
                        <span class="hidden md:inline font-medium text-gray-700 text-sm">
                            <?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?>
                        </span>
                    </button>

                    <div id="userDropdown"
                         class="hidden absolute right-0 mt-2 w-52 bg-white border border-gray-200 rounded-md shadow-lg py-2 z-50">
                        <a href="/tem_system/profile/profile.php"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ໂປຣໄຟລ໌</a>
                        <a href="/tem_system/profile/edit_profile.php"
                        
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ຕັ້ງຄ່າ</a>
                           
                           <a href="/tem_system/dashboard.php"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?= ($current_page == 'dashboard.php') ?>">
                        Dashboard
                            </a>

                        <?php if (isAdmin()): ?>
                            <div class="border-t border-gray-100 my-1"></div>
                            <a href="/tem_system/admin/manage_users.php"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ຈັດການຜູ້ໃຊ້</a>
                            <a href="/tem_system/event/add_event.php"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ເພີ່ມກິດນິມົນ</a>
                            <a href="/tem_system/event/assign_monks.php"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ເລືອກພຣະໄປງານ</a>
                        <?php endif; ?>
                        <div class="border-t border-gray-100 my-1"></div>
                        <a href="/tem_system/register/logout.php"
                           class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">ອອກຈາກລະບົບ</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden mt-2 space-y-1">
            <a href="/tem_system/dashboard.php"
               class="block px-4 py-2 rounded-md text-sm font-medium <?= ($current_page == 'dashboard.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' ?>">
                Dashboard
            </a>
            <a href="/tem_system/list_monks.php"
               class="block px-4 py-2 rounded-md text-sm font-medium <?= ($current_page == 'list_monks.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' ?>">
                ລວມລາຍຊື່
            </a>
            <a href="/tem_system/event/calendar.php"
               class="block px-4 py-2 rounded-md text-sm font-medium <?= ($current_page == 'calendar.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' ?>">
                ປະຕິທິນ
            </a>
        </div>
    </div>
</nav>
<!-- END: Responsive Modern Navbar -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const userMenuButton = document.getElementById('userMenuButton');
    const userDropdown = document.getElementById('userDropdown');

    mobileMenuButton?.addEventListener('click', function () {
        mobileMenu.classList.toggle('hidden');
    });

    userMenuButton?.addEventListener('click', function (e) {
        e.stopPropagation();
        userDropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', function (e) {
        if (!userDropdown.contains(e.target) && !userMenuButton.contains(e.target)) {
            userDropdown.classList.add('hidden');
        }
        if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
            mobileMenu.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            userDropdown.classList.add('hidden');
            mobileMenu.classList.add('hidden');
        }
    });
});
</script>


<!-- Content เริ่มที่นี่ -->
<div class="p-6">
