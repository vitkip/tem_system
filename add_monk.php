<?php
session_start();
require 'db.php';
include 'header.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: register/login.php');
    exit();
}
// ✅ เช็กสิทธิ์
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die('<div class="text-center text-red-600 mt-10 text-xl font-bold">ເຂົ້າໃຊ້ໄດ້ສຳລັບ Admin ເທົ່ານັ້ນ!</div>');
}

// ຕົວເລືອກປະເພດ
$prefixOptions = [
    'ພຣະ' => 'ພຣະ',
    'ຄຸນແມ່ຂາວ' => 'ຄຸນແມ່ຂາວ',
    'ສ.ນ' => 'ສ.ນ',
    'ສັງກະລີ' => 'ສັງກະລີ',
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ຮັບຂໍ້ມູນຈາກຟອມ
    $prefix = trim($_POST['prefix']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $birth_date = trim($_POST['birth_date']);
    $nationality = trim($_POST['nationality']);
    $ethnicity = trim($_POST['ethnicity']);
    $birthplace_village = trim($_POST['birthplace_village']);
    $birthplace_district = trim($_POST['birthplace_district']);
    $birthplace_province = trim($_POST['birthplace_province']);
    $father_name = trim($_POST['father_name']);
    $mother_name = trim($_POST['mother_name']);
    $current_temple = trim($_POST['current_temple']);
    $ordination_date = trim($_POST['ordination_date']);
    //$age_pansa = trim($_POST['age_pansa']);
    $certificate_number = trim($_POST['certificate_number']);
    $notes = trim($_POST['notes']);

    // ກວດສອບຂໍ້ມູນຈໍາເປັນ
    if (empty($prefix)) $errors[] = "ກະລຸນາເລືອກປະເພດ.";
    if (empty($first_name)) $errors[] = "ກະລຸນາໃສ່ຊື່.";
    if (empty($last_name)) $errors[] = "ກະລຸນາໃສ່ນາມສະກຸນ.";
    if (empty($birth_date)) $errors[] = "ກະລຸນາໃສ່ວັນເກີດ.";
    if (empty($current_temple)) $errors[] = "ກະລຸນາໃສ່ຊື່ວັດປະຈຸບັນ.";

    // ກວດສອບຊື່ຊ້ໍາ
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM monks WHERE prefix = ? AND first_name = ? AND last_name = ?");
    $stmt->execute([$prefix, $first_name, $last_name]);
    $duplicateName = $stmt->fetchColumn();
    if ($duplicateName > 0) {
        $errors[] = "ມີຊື່ນີ້ໃນລະບົບແລ້ວ!";
    }

    // ກວດສອບເລກໃບສຸດທິ
    if (!empty($certificate_number)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM monks WHERE certificate_number = ?");
        $stmt->execute([$certificate_number]);
        $duplicateCert = $stmt->fetchColumn();
        if ($duplicateCert > 0) {
            $errors[] = "ເລກໃບສຸດທິຊ້ໍາກັບຂໍ້ມູນໃນລະບົບ!";
        }
    }

    // ກວດຮູບ
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = time() . "_" . basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($fileType, $allowTypes)) {
            if ($_FILES['photo']['size'] <= 2 * 1024 * 1024) {
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                    $photo = $fileName;
                } else {
                    $errors[] = "ເກີດຂໍ້ຜິດພາດໃນການອັບໂຫລດ.";
                }
            } else {
                $errors[] = "ໄຟລ໌ຮູບຕ້ອງບໍ່ເກີນ 2MB.";
            }
        } else {
            $errors[] = "ກະລຸນາໃສ່ຮູບ .jpg .jpeg .png .gif ເທົ່ານັ້ນ.";
        }
    }
// คำนวณพรรษาอัตโนมัติ
$age_pansa = null;
if (!empty($ordination_date)) {
    $ordination = new DateTime($ordination_date);
    $now = new DateTime();
    $diff = $ordination->diff($now);
    $age_pansa = $diff->y; // ปีที่ต่างกันคือพรรษา
}
    // ຖ້າບໍ່ມີ error ບັນທຶກໃສ່ database
    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO monks 
            (prefix, first_name, last_name, birth_date, nationality, ethnicity, birthplace_village, birthplace_district, birthplace_province, father_name, mother_name, current_temple, ordination_date, age_pansa, certificate_number, photo, notes)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $prefix, $first_name, $last_name, $birth_date, $nationality, $ethnicity,
            $birthplace_village, $birthplace_district, $birthplace_province,
            $father_name, $mother_name, $current_temple,
            $ordination_date, $age_pansa, $certificate_number, $photo, $notes
        ]);

        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'ບັນທຶກຂໍ້ມູນສໍາເລັດ!',
                showConfirmButton: false,
                timer: 1500
            }).then(function(){
                window.location = 'list_monks.php';
            });
        </script>";
        exit;
    }
}
?>

<!-- ແບບຟອມ -->
<div class="p-8 max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-center text-indigo-700 mb-8">ເພີ່ມຂໍ້ມູນພຣະ|ແມ່ຂາວ|ສາມະເນນ</h1>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
            <ul class="list-disc pl-5">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow space-y-6">
        <div class="flex justify-center mb-6">
            <img id="preview-image" src="https://via.placeholder.com/150" class="h-32 w-32 rounded-full object-cover border">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 text-gray-700">ປະເພດ:</label>
                <select name="prefix" required class="border border-gray-300 rounded px-3 py-2 w-full">
                    <option value="">-- ເລືອກປະເພດ --</option>
                    <?php foreach ($prefixOptions as $key => $label): ?>
                        <option value="<?= $key ?>"><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ຊື່:</label>
                <input type="text" name="first_name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ນາມສະກຸນ:</label>
                <input type="text" name="last_name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ວັນເກີດ:</label>
                <input type="date" name="birth_date" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ສັນຊາດ:</label>
                <input type="text" name="nationality" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ຊົນເຜົ່າ:</label>
                <input type="text" name="ethnicity" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ບ້ານເກີດ:</label>
                <input type="text" name="birthplace_village" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ເມືອງເກີດ:</label>
                <input type="text" name="birthplace_district" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ແຂວງເກີດ:</label>
                <input type="text" name="birthplace_province" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ຊື່ພໍ່:</label>
                <input type="text" name="father_name" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ຊື່ແມ່:</label>
                <input type="text" name="mother_name" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ຊື່ວັດປະຈຸບັນ:</label>
                <input type="text" name="current_temple" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ວັນບວດ:</label>
                <input type="date" name="ordination_date" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ຈໍານວນພັນສາ:</label>
                <input type="number" name="age_pansa" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ເລກໃບສຸດທິ:</label>
                <input type="text" name="certificate_number" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ອັບໂຫຼດຮູບ:</label>
                <input type="file" name="photo" id="photoInput" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block mb-2 text-gray-700">ໝາຍເຫດເພີ່ມເຕີມ:</label>
            <textarea name="notes" rows="4" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="text-center mt-8">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                💾 ບັນທຶກຂໍ້ມູນ
            </button>
            <a href="list_monks.php" class="ml-4 text-indigo-600 underline">← ກັບໄປລາຍການ</a>
        </div>

    </form>
</div>

<!-- Preview image -->
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
document.querySelector('[name="ordination_date"]').addEventListener('change', function () {
    const ordinationDate = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - ordinationDate.getFullYear();
    const m = today.getMonth() - ordinationDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < ordinationDate.getDate())) {
        age--;
    }
    if (age >= 0) {
        document.querySelector('[name="age_pansa"]').value = age;
    } else {
        document.querySelector('[name="age_pansa"]').value = '';
    }
});
</script>

<?php include 'footer.php'; ?>
