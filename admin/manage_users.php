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
                window.location = 'manage_users.php';
            });
        </script>";
        exit;
    }
}

// ດຶງຂໍ້ມູນຜູ້ໃຊ້
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>

<div class="p-8 max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-center text-indigo-700 mb-6">ຈັດການຜູ້ໃຊ້</h1>

    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-indigo-50 text-gray-800">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">ຊື່ຜູ້ໃຊ້</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">ສິດ</th>
                    <th class="px-4 py-2">ຈັດການສິດ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $i => $user): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2"><?= $i + 1 ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($user['username']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-4 py-2">
                            <span class="inline-block bg-<?= $user['role'] === 'admin' ? 'green' : 'yellow' ?>-100 text-<?= $user['role'] === 'admin' ? 'green' : 'yellow' ?>-800 px-2 py-1 rounded text-sm">
                                <?= htmlspecialchars($user['role']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <a href="?id=<?= $user['id'] ?>&change_role=member" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">ເປລຽນເປັນ Member</a>
                                <?php else: ?>
                                    <a href="?id=<?= $user['id'] ?>&change_role=admin" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">ເປລຽນເປັນ Admin</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-gray-400 text-sm">(ບໍ່ແກ້ໄດ້)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-6">
        <a href="../dashboard.php" class="bg-blue-500 text-white px-5 py-2 rounded hover:bg-blue-600">← ກັບໄປ Dashboard</a>
    </div>
</div>


<?php include '../footer.php'; ?>