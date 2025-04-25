<?php
require 'db.php';
include 'header.php';

checkPermission();
checkAdminPermission();

if (!isset($_GET['id'])) {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ບໍ່ພົບຂໍ້ມູນທີ່ຈະແກ້ໄຂ</div>');
}

$id = $_GET['id'];

// ດຶງຂໍ້ມູນພະ/ແມ່ຊີ/ເນນ
$stmt = $pdo->prepare("SELECT * FROM monks WHERE id = ?");
$stmt->execute([$id]);
$monk = $stmt->fetch();

if (!$monk) {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ບໍ່ພົບຂໍ້ມູນໃນຖານຂໍ້ມູນ</div>');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prefix = $_POST['prefix'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birth_date = $_POST['birth_date'];
    $nationality = $_POST['nationality'];
    $ethnicity = $_POST['ethnicity'];
    $birthplace_village = $_POST['birthplace_village'];
    $birthplace_district = $_POST['birthplace_district'];
    $birthplace_province = $_POST['birthplace_province'];
    $father_name = $_POST['father_name'];
    $mother_name = $_POST['mother_name'];
    $current_temple = $_POST['current_temple'];
    $ordination_date = $_POST['ordination_date'];
    $age_pansa = $_POST['age_pansa'];
    $certificate_number = $_POST['certificate_number'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    // ຈັດການອັບໂຫຼດຮູບ
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = time() . "_" . basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowTypes = array('jpg','jpeg','png','gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                $photo = $fileName;
                if (!empty($monk['photo']) && file_exists("uploads/" . $monk['photo'])) {
                    unlink("uploads/" . $monk['photo']);
                }
            }
        }
    } else {
        $photo = $monk['photo'];
    }

    // ອັບເດດຂໍ້ມູນ
    $sql = "UPDATE monks SET
        prefix = :prefix,
        first_name = :first_name,
        last_name = :last_name,
        birth_date = :birth_date,
        nationality = :nationality,
        ethnicity = :ethnicity,
        birthplace_village = :birthplace_village,
        birthplace_district = :birthplace_district,
        birthplace_province = :birthplace_province,
        father_name = :father_name,
        mother_name = :mother_name,
        current_temple = :current_temple,
        ordination_date = :ordination_date,
        age_pansa = :age_pansa,
        certificate_number = :certificate_number,
        photo = :photo,
        status = :status,
        notes = :notes
        WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':prefix' => $prefix,
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':birth_date' => $birth_date,
        ':nationality' => $nationality,
        ':ethnicity' => $ethnicity,
        ':birthplace_village' => $birthplace_village,
        ':birthplace_district' => $birthplace_district,
        ':birthplace_province' => $birthplace_province,
        ':father_name' => $father_name,
        ':mother_name' => $mother_name,
        ':current_temple' => $current_temple,
        ':ordination_date' => $ordination_date,
        ':age_pansa' => $age_pansa,
        ':certificate_number' => $certificate_number,
        ':status' => $status,
        ':photo' => $photo,
        ':notes' => $notes,
        ':id' => $id
    ]);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'ແກ້ໄຂຂໍ້ມູນສຳເລັດ!',
            showConfirmButton: false,
            timer: 1500
        }).then(function(){
            window.location = 'list_monks.php';
        });
    </script>";
    exit;
}
?>
<!-- ຫນ້າຟອມແກ້ໄຂ -->
<div class="p-8 max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-center text-indigo-700 mb-8">ແກ້ໄຂຂໍ້ມູນພະ/ແມ່ຊີ/ເນນ</h1>

    <form method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow space-y-10">

    <!-- ຮູບ Preview -->
    <div class="flex justify-center">
        <div class="flex flex-col items-center">
            <img id="preview-image" src="<?= $monk['photo'] ? 'uploads/' . htmlspecialchars($monk['photo']) : 'https://via.placeholder.com/150' ?>" class="h-32 w-32 rounded-full object-cover border mb-3">
            <label for="photoInput" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded cursor-pointer hover:bg-indigo-700 transition">
                📷 ແກ້ໄຂຮູບ
            </label>
            <input type="file" name="photo" id="photoInput" class="hidden">
        </div>
    </div>

    <!-- ຂໍ້ມູນສ່ວນໂຕ -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">📋 ຂໍ້ມູນສ່ວນໂຕ</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block mb-1 text-gray-700">ປະເພດ:</label>
                <select name="prefix" required class="w-full border rounded px-3 py-2">
                    <option value="ພຣະ" <?= $monk['prefix'] == 'ພຣະ' ? 'selected' : '' ?>>ພຣະ</option>
                    <option value="ຄຸນແມ່ຂາວ" <?= $monk['prefix'] == 'ຄຸນແມ່ຂາວ' ? 'selected' : '' ?>>ຄຸນແມ່ຂາວ</option>
                    <option value="ສ.ນ" <?= $monk['prefix'] == 'ສ.ນ' ? 'selected' : '' ?>>ສ.ນ</option>
                    <option value="ສັງກະລີ" <?= $monk['prefix'] == 'ສັງກະລີ' ? 'selected' : '' ?>>ສັງກະລີ</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 text-gray-700">ຊື່:</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($monk['first_name']) ?>" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-1 text-gray-700">ນາມສະກຸນ:</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($monk['last_name']) ?>" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-1 text-gray-700">ວັນເກີດ:</label>
                <input type="date" name="birth_date" value="<?= htmlspecialchars($monk['birth_date']) ?>" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-1 text-gray-700">ສັນຊາດ:</label>
                <input type="text" name="nationality" value="<?= htmlspecialchars($monk['nationality']) ?>" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-1 text-gray-700">ຊົນເຜົ່າ:</label>
                <input type="text" name="ethnicity" value="<?= htmlspecialchars($monk['ethnicity']) ?>" class="w-full border rounded px-3 py-2">
            </div>
        </div>
    </div>

    <!-- ສະຖານທີ່ເກີດ -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">🗺️ ສະຖານທີ່ເກີດ</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block mb-1 text-gray-700">ບ້ານເກີດ:</label>
                <input type="text" name="birthplace_village" value="<?= htmlspecialchars($monk['birthplace_village']) ?>" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 text-gray-700">ເມືອງເກີດ:</label>
                <input type="text" name="birthplace_district" value="<?= htmlspecialchars($monk['birthplace_district']) ?>" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 text-gray-700">ແຂວງເກີດ:</label>
                <input type="text" name="birthplace_province" value="<?= htmlspecialchars($monk['birthplace_province']) ?>" class="w-full border rounded px-3 py-2">
            </div>
        </div>
    </div>

    <!-- ຄອບຄົວ & ວັດ -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">👨‍👩‍👧‍👦 ຄອບຄົວ & ວັດ</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block mb-1 text-gray-700">ຊື່ພໍ່:</label>
                <input type="text" name="father_name" value="<?= htmlspecialchars($monk['father_name']) ?>" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 text-gray-700">ຊື່ແມ່:</label>
                <input type="text" name="mother_name" value="<?= htmlspecialchars($monk['mother_name']) ?>" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 text-gray-700">ວັດທີ່ສັງກັດ:</label>
                <input type="text" name="current_temple" value="<?= htmlspecialchars($monk['current_temple']) ?>" class="w-full border rounded px-3 py-2">
            </div>
        </div>
    </div>

    <!-- ຂໍ້ມູນການບວດ -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">🛕 ຂໍ້ມູນການບວດ</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block mb-1 text-gray-700">ວັນບວດ:</label>
                <input type="date" name="ordination_date" value="<?= htmlspecialchars($monk['ordination_date']) ?>" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 text-gray-700">ຈໍານວນພັນສາ:</label>
                <input type="number" name="age_pansa" value="<?= htmlspecialchars($monk['age_pansa']) ?>" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 text-gray-700">ເລກໃບສຸດທິ:</label>
                <input type="text" name="certificate_number" value="<?= htmlspecialchars($monk['certificate_number']) ?>" class="w-full border rounded px-3 py-2">
            </div>
        </div>
    </div>

    <!-- ສະຖານະ -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">📌 ສະຖານະ & ໝາຍເຫດ</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block mb-1 text-gray-700">ສະຖານະ:</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="ບວດຢູ່" <?= trim($monk['status']) == 'ບວດຢູ່' ? 'selected' : '' ?>>🟢 ບວດຢູ່</option>
                    <option value="ສຶກແລ້ວ" <?= trim($monk['status']) == 'ສຶກແລ້ວ' ? 'selected' : '' ?>>⚪ ສຶກແລ້ວ</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block mb-1 text-gray-700">ໝາຍເຫດເພີ່ມເຕີມ:</label>
                <textarea name="notes" rows="4" class="w-full border rounded px-3 py-2"><?= htmlspecialchars($monk['notes']) ?></textarea>
            </div>
        </div>
    </div>

    <!-- ປຸ່ມ -->
    <div class="text-center mt-8">
        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded hover:bg-indigo-700 transition">
            💾 ບັນທຶກການແກ້ໄຂ
        </button>
        <a href="list_monks.php" class="ml-4 text-indigo-600 underline">← ກັບໄປລາຍການ</a>
    </div>
</form>

</div>

<!-- ສະແດງ Preview -->
<script>
document.getElementById('photoInput')?.addEventListener('change', function(event){
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('preview-image').src = reader.result;
    }
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
});
</script>

<?php include 'footer.php'; ?>
