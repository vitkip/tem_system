<?php
require 'db.php';
include 'header.php';

if (!isset($_GET['id'])) {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ไม่พบข้อมูลที่ต้องการดู</div>');
}

$id = $_GET['id'];

// ดึงข้อมูลพระ/แม่ชี/เณร
$stmt = $pdo->prepare("SELECT * FROM monks WHERE id = ?");
$stmt->execute([$id]);
$monk = $stmt->fetch();

if (!$monk) {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ไม่พบข้อมูลในฐานข้อมูล</div>');
}
?>

<div class="p-8 max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-indigo-700 text-center mb-8">ข้อมูลพระ/แม่ชี/เณร</h1>

    <div class="bg-white rounded-lg shadow-md p-6 space-y-6">
        
        <!-- รูปถ่าย -->
        <div class="flex justify-center">
            <?php if ($monk['photo']): ?>
                <img src="uploads/<?= htmlspecialchars($monk['photo']) ?>" class="h-40 w-40 rounded-full object-cover border">
            <?php else: ?>
                <div class="text-gray-400">ไม่มีรูปถ่าย</div>
            <?php endif; ?>
        </div>

        <!-- ข้อมูล -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p><span class="font-bold">ประเภท:</span> <?= htmlspecialchars($monk['prefix']) ?></p>
                <p><span class="font-bold">ชื่อ:</span> <?= htmlspecialchars($monk['first_name']) . ' ' . htmlspecialchars($monk['last_name']) ?></p>
                <p><span class="font-bold">วันเกิด:</span> <?= htmlspecialchars($monk['birth_date']) ?></p>
                <p><span class="font-bold">สัญชาติ:</span> <?= htmlspecialchars($monk['nationality']) ?></p>
                <p><span class="font-bold">ชนเผ่า:</span> <?= htmlspecialchars($monk['ethnicity']) ?></p>
                <p><span class="font-bold">วัดสังกัด:</span> <?= htmlspecialchars($monk['current_temple']) ?></p>
            </div>

            <div>
                <p><span class="font-bold">บ้านเกิด:</span> หมู่บ้าน <?= htmlspecialchars($monk['birthplace_village']) ?>, อำเภอ <?= htmlspecialchars($monk['birthplace_district']) ?>, จังหวัด <?= htmlspecialchars($monk['birthplace_province']) ?></p>
                <p><span class="font-bold">ชื่อพ่อ:</span> <?= htmlspecialchars($monk['father_name']) ?></p>
                <p><span class="font-bold">ชื่อแม่:</span> <?= htmlspecialchars($monk['mother_name']) ?></p>
                <p><span class="font-bold">วันบวช:</span> <?= htmlspecialchars($monk['ordination_date']) ?></p>
                <p><span class="font-bold">พรรษา:</span> <?= htmlspecialchars($monk['age_pansa']) ?></p>
                <p><span class="font-bold">เลขที่ใบสุทธิ:</span> <?= htmlspecialchars($monk['certificate_number']) ?></p>
            </div>

        </div>

        <!-- หมายเหตุ -->
        <?php if (!empty($monk['notes'])): ?>
            <div class="mt-6">
                <p class="font-bold mb-2">หมายเหตุ:</p>
                <div class="bg-gray-100 p-4 rounded"><?= nl2br(htmlspecialchars($monk['notes'])) ?></div>
            </div>
        <?php endif; ?>

    </div>

    <!-- ปุ่มกลับ -->
    <div class="text-center mt-8">
        <a href="list_monks.php" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
            ← กลับไปยังรายการ
        </a>
    </div>
</div>

<?php include 'footer.php'; ?>
