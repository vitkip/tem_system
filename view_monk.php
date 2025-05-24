<?php
require 'db.php';
include 'header.php';

if (!isset($_GET['id'])) {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ບໍ່ພົບຂໍ້ມູນທີ່ຈະເບິ່ງ</div>');
}

$id = $_GET['id'];

// ດຶງຂໍ້ມູນພະ/ແມ່ຊີ/ເນນ
$stmt = $pdo->prepare("SELECT * FROM monks WHERE id = ?");
$stmt->execute([$id]);
$monk = $stmt->fetch();

if (!$monk) {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ບໍ່ພົບຂໍ້ມູນໃນຖານຂໍ້ມູນ</div>');
}
?>

<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-white py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Profile Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header Section with Gradient -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8">
                <div class="flex flex-col items-center">
                    <!-- Profile Image -->
                    <div class="relative">
                        <?php if ($monk['photo']): ?>
                            <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($monk['photo']) ?>" 
                                 class="h-32 w-32 md:h-40 md:w-40 rounded-full object-cover border-4 border-white shadow-lg"
                                 alt="<?= htmlspecialchars($monk['first_name']) ?>">
                        <?php else: ?>
                            <div class="h-32 w-32 md:h-40 md:w-40 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white border-4 border-white">
                                <i class="fas fa-user-circle text-4xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Name and Title -->
                    <h1 class="mt-4 text-2xl md:text-3xl font-bold text-white">
                        <?= htmlspecialchars($monk['prefix']) ?> <?= htmlspecialchars($monk['first_name']) ?> <?= htmlspecialchars($monk['last_name']) ?>
                    </h1>
                    <p class="text-indigo-100 mt-2"><?= htmlspecialchars($monk['current_temple']) ?></p>
                </div>
            </div>

            <!-- Information Sections -->
            <div class="p-6 md:p-8">
                <!-- Main Info Grid -->
                <?php if (isAdmin()): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-indigo-600"></i>
                            ຂໍ້ມູນສ່ວນຕົວ
                        </h2>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-birthday-cake text-indigo-500 w-5"></i>
                                <span class="text-gray-600">ວັນເກີດ:</span>
                                <span class="font-medium"><?= htmlspecialchars($monk['birth_date']) ?></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-flag text-indigo-500 w-5"></i>
                                <span class="text-gray-600">ສັນຊາດ:</span>
                                <span class="font-medium"><?= htmlspecialchars($monk['nationality']) ?></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-users text-indigo-500 w-5"></i>
                                <span class="text-gray-600">ຊົນເຜົ່າ:</span>
                                <span class="font-medium"><?= htmlspecialchars($monk['ethnicity']) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Ordination Information -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-pray mr-2 text-indigo-600"></i>
                            ຂໍ້ມູນການບວດ
                        </h2>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar-alt text-indigo-500 w-5"></i>
                                <span class="text-gray-600">ວັນບວດ:</span>
                                <span class="font-medium"><?= htmlspecialchars($monk['ordination_date']) ?></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-history text-indigo-500 w-5"></i>
                                <span class="text-gray-600">ພັນສາ:</span>
                                <span class="font-medium"><?= htmlspecialchars($monk['age_pansa']) ?> ພັນສາ</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-id-card text-indigo-500 w-5"></i>
                                <span class="text-gray-600">ເລກໃບສຸດທິ:</span>
                                <span class="font-medium"><?= htmlspecialchars($monk['certificate_number']) ?></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-id-card text-indigo-500 w-5"></i>
                                <span class="text-gray-600">ສະຖານະ:</span>
                                <span class="font-medium"><?= htmlspecialchars($monk['status']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Notes Section -->
                <?php if (!empty($monk['notes'])): ?>
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center mb-4">
                        <i class="fas fa-sticky-note mr-2 text-indigo-600"></i>
                        ໝາຍເຫດ
                    </h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 leading-relaxed">
                            <?= nl2br(htmlspecialchars($monk['notes'])) ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="<?= BASE_URL ?>list_monks.php" 
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 
                              transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>
                        ກັບໄປລາຍການ
                    </a>
                    <?php if (isAdmin()): ?>
                    <a href="<?= BASE_URL ?>edit_monk.php?id=<?= $monk['id'] ?>" 
                       class="inline-flex items-center px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 
                              transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        ແກ້ໄຂຂໍ້ມູນ
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php include 'footer.php'; ?>
