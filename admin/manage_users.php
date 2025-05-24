<?php
require '../db.php';
include '../header.php';

// ເຊັກສິດກ່ອນ
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ເຂົ້າໃຊ້ໄດ້ແຕ່ Admin ເທົ່ານັ້ນ</div>');
}

// ການປ່ຽນສິດ
if (isset($_GET['id']) && isset($_GET['change_role'])) {
    $userId = $_GET['id'];
    $newRole = $_GET['change_role'];

    if (in_array($newRole, ['admin', 'member'])) {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$newRole, $userId]);

        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'ປ່ຽນສິດສຳເລັດ!',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location = '".BASE_URL."admin/manage_users.php';
            });
        </script>";
        exit;
    }
}

// ດຶງຂໍ້ມູນຜູ້ໃຊ້
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>

<!-- CSS Styles -->
<style>
    .user-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .user-card:hover {
        transform: translateY(-2px);
        border-left: 4px solid #4f46e5;
    }
    .user-card.admin {
        border-left-color: #10b981;
    }
    .user-card.member {
        border-left-color: #f59e0b;
    }
    .role-badge {
        transition: all 0.2s ease;
    }
    .role-badge:hover {
        transform: scale(1.05);
    }
    .action-button {
        transition: all 0.2s ease;
    }
    .action-button:hover {
        transform: translateY(-1px);
    }
    .avatar {
        background-size: cover;
        background-position: center;
    }
    .search-box {
        transition: all 0.3s ease;
    }
    .search-box:focus {
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }
</style>

<div class="p-8 max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-indigo-700">ຈັດການຜູ້ໃຊ້</h1>
            <p class="text-gray-600 mt-1">ຈັດການບັນຊີຜູ້ໃຊ້ ແລະ ສິດທິການເຂົ້າເຖິງລະບົບ</p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="relative">
                <input type="text" id="searchUser" placeholder="ຄົ້ນຫາຜູ້ໃຊ້..." class="search-box pl-10 pr-4 py-2 rounded-lg border focus:outline-none focus:border-indigo-500">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 flex items-start space-x-4">
            <div class="bg-indigo-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">ຜູ້ໃຊ້ທັງໝົດ</p>
                <p class="text-2xl font-bold text-gray-800"><?= count($users) ?></p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 flex items-start space-x-4">
            <div class="bg-green-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">ແອດມິນ</p>
                <p class="text-2xl font-bold text-gray-800">
                    <?= count(array_filter($users, function($user) { return $user['role'] === 'admin'; })) ?>
                </p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 flex items-start space-x-4">
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">ສະມາຊິກ</p>
                <p class="text-2xl font-bold text-gray-800">
                    <?= count(array_filter($users, function($user) { return $user['role'] === 'member'; })) ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Users List -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 divide-y divide-gray-100">
            <?php foreach ($users as $i => $user): ?>
                <div class="user-card <?= $user['role'] ?> p-4 md:p-6 hover:bg-gray-50">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex items-center">
                            <div class="avatar h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center mr-4">
                                <?php if (!empty($user['profile_image'])): ?>
                                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($user['profile_image']) ?>" class="h-12 w-12 rounded-full object-cover" alt="Profile">
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="font-medium text-lg text-gray-800"><?= htmlspecialchars($user['username']) ?></h3>
                                <p class="text-gray-500 text-sm"><?= htmlspecialchars($user['email']) ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center mt-4 md:mt-0">
                            <div class="mr-4">
                                <span class="role-badge inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    <?= $user['role'] === 'admin' 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-yellow-100 text-yellow-800' ?>">
                                    <?php if ($user['role'] === 'admin'): ?>
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                    <?php else: ?>
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </div>
                            
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <a href="<?= BASE_URL ?>admin/manage_users.php?id=<?= $user['id'] ?>&change_role=member" 
                                       class="action-button inline-flex items-center px-3 py-2 border border-yellow-500 text-yellow-600 
                                              bg-white hover:bg-yellow-50 rounded-lg text-sm font-medium shadow-sm">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        ເປັນ Member
                                    </a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>admin/manage_users.php?id=<?= $user['id'] ?>&change_role=admin" 
                                       class="action-button inline-flex items-center px-3 py-2 border border-green-500 text-green-600 
                                              bg-white hover:bg-green-50 rounded-lg text-sm font-medium shadow-sm">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        ເປັນ Admin
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-gray-400 text-sm italic px-3 py-2">(ບໍ່ສາມາດແກ້ໄຂໄດ້)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Back Button -->
    <div class="text-center mt-8">
        <a href="<?= BASE_URL ?>dashboard.php" class="action-button inline-flex items-center px-5 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            ກັບໄປ Dashboard
        </a>
    </div>
</div>

<!-- JavaScript for search functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchUser');
    const userCards = document.querySelectorAll('.user-card');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        userCards.forEach(card => {
            const username = card.querySelector('h3').textContent.toLowerCase();
            const email = card.querySelector('p').textContent.toLowerCase();
            
            if (username.includes(searchTerm) || email.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>

<?php include '../footer.php'; ?>