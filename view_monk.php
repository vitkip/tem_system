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

<div class="p-8 max-w-4xl mx-auto space-y-8">
    <h1 class="text-3xl font-bold text-indigo-700 text-center mb-8">ຂໍ້ມູນພຣະ|ແມ່ຂາວ|ສາມະເນນ|</h1>

    <div class="bg-white rounded-lg shadow-md p-8 space-y-8">

        <!-- ຮູບຖ່າຍ -->
        <div class="flex justify-center">
            <?php if ($monk['photo']): ?>
                <img src="uploads/<?= htmlspecialchars($monk['photo']) ?>" class="h-40 w-40 rounded-full object-cover border-4 border-indigo-200 shadow">
            <?php else: ?>
                <div class="h-40 w-40 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 border">
                    ບໍ່ມີຮູບ
                </div>
            <?php endif; ?>
        </div>
            <div class="inline-flex items-center justify-center w-full">
                <hr class="w-64 h-1 my-8 bg-gray-200 border-0 rounded-sm white:bg-gray-700">
                <div class="absolute px-4 -translate-x-1/2 bg-white left-1/2 white:bg-gray-900">
                    <div>
                        <span class="font-semibold text-gray-600">ຊື່:</span> <?= htmlspecialchars($monk['first_name']) ?> <?= htmlspecialchars($monk['last_name']) ?></div>
                </div>
            </div>
            <?php if (isAdmin()): ?>
        <!-- ຂໍ້ມູນ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div><span class="font-semibold text-gray-600">ປະເພດ:</span> <?= htmlspecialchars($monk['prefix']) ?></div>
                <div><span class="font-semibold text-gray-600">ຊື່:</span> <?= htmlspecialchars($monk['first_name']) ?> <?= htmlspecialchars($monk['last_name']) ?></div>
                <div><span class="font-semibold text-gray-600">ວັນເກີດ:</span> <?= htmlspecialchars($monk['birth_date']) ?></div>
                <div><span class="font-semibold text-gray-600">ສັນຊາດ:</span> <?= htmlspecialchars($monk['nationality']) ?></div>
                <div><span class="font-semibold text-gray-600">ຊົນເຜົ່າ:</span> <?= htmlspecialchars($monk['ethnicity']) ?></div>
                <div><span class="font-semibold text-gray-600">ວັດປະຈຸບັນ:</span> <?= htmlspecialchars($monk['current_temple']) ?></div>
            </div>

            <div class="space-y-4">
                <div><span class="font-semibold text-gray-600">ບ້ານເກີດ:</span> <?= htmlspecialchars($monk['birthplace_village']) ?>, ເມືອງ <?= htmlspecialchars($monk['birthplace_district']) ?>, ແຂວງ <?= htmlspecialchars($monk['birthplace_province']) ?></div>
                <div><span class="font-semibold text-gray-600">ຊື່ພໍ່:</span> <?= htmlspecialchars($monk['father_name']) ?></div>
                <div><span class="font-semibold text-gray-600">ຊື່ແມ່:</span> <?= htmlspecialchars($monk['mother_name']) ?></div>
                <div><span class="font-semibold text-gray-600">ວັນບວດ:</span> <?= htmlspecialchars($monk['ordination_date']) ?></div>
                <div><span class="font-semibold text-gray-600">ພັນສາ:</span> <?= htmlspecialchars($monk['age_pansa']) ?> ພັນສາ</div>
                <div><span class="font-semibold text-gray-600">ເລກໃບສຸດທິ:</span> <?= htmlspecialchars($monk['certificate_number']) ?></div>
            </div>
        </div>
        <?php endif; ?>
        <!-- ໝາຍເຫດ -->
        <?php if (!empty($monk['notes'])): ?>
            <div class="bg-gray-100 p-4 rounded">
                <h2 class="font-bold text-gray-700 mb-2">ໝາຍເຫດ:</h2>
                <p><?= nl2br(htmlspecialchars($monk['notes'])) ?></p>
            </div>
        <?php endif; ?>

    </div>

    <!-- ປຸ່ມກັບໄປ-ແກ້ໄຂ -->
    <div class="flex justify-center gap-4">
        <a href="list_monks.php" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
            ← ກັບໄປລາຍການ
        </a>
        <?php if (isAdmin()): ?>
        <a href="edit_monk.php?id=<?= $monk['id'] ?>" class="bg-yellow-400 text-white px-6 py-2 rounded hover:bg-yellow-500 transition">
            ✏️ ແກ້ໄຂຂໍ້ມູນ
        </a>
        <?php endif; ?>

    </div>
</div>

<?php include 'footer.php'; ?>
