<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);

require_once 'functions.php';
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
        *{
            font-family: 'Noto Sans Lao', sans-serif;
        }
        
        /* Mobile Menu Styles */
        #mobileMenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        #mobileMenu.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (min-width: 768px) {
            #mobileMenu {
                display: none !important;
            }
        }

        .custom-swal-container {
            z-index: 9999;
        }

        .custom-swal-popup {
            border-radius: 1rem;
            padding: 0;
        }

        .swal2-close:focus {
            box-shadow: none;
        }

        .swal2-popup {
            font-family: 'Noto Sans Lao', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
<!-- START: Responsive Modern Navbar -->
<nav class="bg-white border-b border-gray-200 shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo + Main Nav -->
            <div class="flex items-center justify-between flex-1 md:justify-start">
                <!-- Logo -->
                <a href="/tem_system/dashboard.php" class="flex-shrink-0">
                    <img src="/tem_system/assets/logo.png" alt="Logo" class="h-10 w-10">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex md:ml-6 md:space-x-6">
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

                <!-- Mobile Menu Button -->
                <button class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" 
                        id="mobileMenuButton"
                        aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- User Menu -->
            <div class="flex items-center">
                <div class="relative">
                    <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                        <img src="/tem_system/uploads/<?= htmlspecialchars($_SESSION['profile_image'] ?? 'default.png') ?>" 
                             class="h-9 w-9 rounded-full object-cover border" 
                             alt="Profile">
                        <span class="hidden md:inline font-medium text-gray-700 text-sm">
                            <?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?>
                        </span>
                    </button>

                    <!-- User Dropdown Menu -->
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-52 bg-white border border-gray-200 rounded-md shadow-lg py-2 z-50">
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
                               <a href="/tem_system/report.php"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ອອກບັດ</a>
                            
                        <?php endif; ?>
                        <div class="border-t border-gray-100 my-1"></div>
                        <a href="/tem_system/register/logout.php"
                           class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">ອອກຈາກລະບົບ</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Slide Down) -->
        <div id="mobileMenu" class="hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
                <a href="/tem_system/dashboard.php" 
                   class="block px-3 py-2 rounded-md text-base font-medium <?= ($current_page == 'dashboard.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' ?>">
                    Dashboard
                </a>
                <a href="/tem_system/list_monks.php" 
                   class="block px-3 py-2 rounded-md text-base font-medium <?= ($current_page == 'list_monks.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' ?>">
                    ລວມລາຍຊື່
                </a>
                <a href="/tem_system/event/calendar.php" 
                   class="block px-3 py-2 rounded-md text-base font-medium <?= ($current_page == 'calendar.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' ?>">
                    ປະຕິທິນ
                </a>
            </div>
        </div>
    </div>
</nav>
<!-- END: Responsive Modern Navbar -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const userMenuButton = document.getElementById('userMenuButton');
    const userDropdown = document.getElementById('userDropdown');

    // Toggle mobile menu
    mobileMenuButton?.addEventListener('click', function(e) {
        e.stopPropagation();
        mobileMenu.classList.toggle('show');
        
        // Update button state
        const isExpanded = mobileMenu.classList.contains('show');
        this.setAttribute('aria-expanded', isExpanded);
    });

    // Toggle user dropdown
    userMenuButton?.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('hidden');
    });

    // Close menus when clicking outside
    document.addEventListener('click', function(e) {
        if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
            mobileMenu.classList.remove('show');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
        }
        
        if (!userDropdown.contains(e.target) && !userMenuButton.contains(e.target)) {
            userDropdown.classList.add('hidden');
        }
    });

    // Close menus on window resize (if switching to desktop view)
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            mobileMenu.classList.remove('show');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            mobileMenu.classList.remove('show');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
            userDropdown.classList.add('hidden');
        }
    });
});
</script>


<!-- Content เริ่มที่นี่ -->
<div class="p-6">
