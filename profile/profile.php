<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ຖ້າບໍ່ໄດ້ login ໃຫ້ກັບໄປຫນ້າ login
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'register/login.php');
    exit();
}

require '../db.php'; // ເຊື່ອມຕໍ່ຖານຂໍ້ມູນ

// ດຶງຂໍ້ມູນຜູ່ໃຊ້ຈາກ session
$username = $_SESSION['username'];
$email = $_SESSION['email'] ?? '';
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ໂປຣໄຟລ໌ຂອງຂ້ອຍ | <?= htmlspecialchars($username) ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao+Looped:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Noto Sans Lao Looped', sans-serif;
            font-size: 16px;
            background: linear-gradient(to bottom right, #e0f2fe, #f0fdf4);
        }
        .profile-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 
                        0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .profile-image-container {
            position: relative;
            display: inline-block;
        }
        .edit-button {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #4f46e5;
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .edit-button:hover {
            transform: scale(1.1);
            background: #4338ca;
        }
        .badge {
            transition: all 0.2s;
        }
        .badge:hover {
            transform: scale(1.05);
        }
        .button-animated {
            transition: all 0.3s ease;
        }
        .button-animated:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 md:p-0">

<div class="profile-card rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
    <!-- Header Banner -->
    <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
        <div class="absolute -bottom-16 w-full flex justify-center">
            <div class="profile-image-container">
                <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($_SESSION['profile_image'] ?? 'default.png') ?>" 
                     alt="ໂປຣໄຟລ໌" 
                     class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg">
                <a href="<?= BASE_URL ?>profile/edit_profile.php" class="edit-button">
                    <i class="fa-solid fa-pencil"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="mt-20 px-8 pb-8">
        <h2 class="text-2xl font-bold text-center text-gray-800">
            <?= htmlspecialchars($username) ?>
        </h2>
        
        <div class="flex justify-center mt-2 mb-6">
            <span class="<?= $role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?> badge px-3 py-1 rounded-full text-xs font-medium">
                <?= $role === 'admin' ? '<i class="fa-solid fa-crown mr-1"></i>' : '<i class="fa-solid fa-user mr-1"></i>' ?>
                <?= htmlspecialchars($role) ?>
            </span>
        </div>
        
        <div class="space-y-4 mt-6">
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="bg-indigo-100 p-2 rounded-full mr-3">
                    <i class="fa-solid fa-user text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">ຊື່ຜູ້ໃຊ້</p>
                    <p class="font-medium text-gray-800"><?= htmlspecialchars($username) ?></p>
                </div>
            </div>
            
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="bg-indigo-100 p-2 rounded-full mr-3">
                    <i class="fa-solid fa-envelope text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">ອີເມວ</p>
                    <p class="font-medium text-gray-800"><?= htmlspecialchars($email) ?></p>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-4">
            <a href="<?= BASE_URL ?>dashboard.php" 
               class="button-animated flex justify-center items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-4 rounded-lg shadow hover:shadow-lg">
                <i class="fa-solid fa-gauge-high"></i>
                ໜ້າຫຼັກ
            </a>
            <a href="<?= BASE_URL ?>profile/edit_profile.php" 
               class="button-animated flex justify-center items-center gap-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white py-3 px-4 rounded-lg shadow hover:shadow-lg">
                <i class="fa-solid fa-user-pen"></i>
                ແກ້ໄຂຂໍ້ມູນ
            </a>
        </div>
        
        <div class="mt-4 text-center">
            <a href="<?= BASE_URL ?>register/logout.php" 
               class="button-animated inline-flex items-center text-red-600 gap-1 text-sm hover:text-red-700">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                ອອກຈາກລະບົບ
            </a>
        </div>
    </div>
</div>

<script>
    // Add some simple animations
    document.addEventListener('DOMContentLoaded', () => {
        const card = document.querySelector('.profile-card');
        setTimeout(() => {
            card.classList.add('translate-y-0', 'opacity-100');
            card.classList.remove('translate-y-4', 'opacity-0');
        }, 100);
    });
</script>

</body>
</html>
