<?php
require 'db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
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
    $notes = $_POST['notes'];

    // รูปถ่าย
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = time() . "_" . basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowTypes = array('jpg','jpeg','png','gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                $photo = $fileName;
            }
        }
    }

    // บันทึกลงฐานข้อมูล
    $sql = "INSERT INTO monks (prefix, first_name, last_name, birth_date, nationality, ethnicity, birthplace_village, birthplace_district, birthplace_province, father_name, mother_name, current_temple, ordination_date, age_pansa, certificate_number, photo, notes) 
            VALUES (:prefix, :first_name, :last_name, :birth_date, :nationality, :ethnicity, :birthplace_village, :birthplace_district, :birthplace_province, :father_name, :mother_name, :current_temple, :ordination_date, :age_pansa, :certificate_number, :photo, :notes)";
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
        ':photo' => $photo,
        ':notes' => $notes,
    ]);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'บันทึกข้อมูลเรียบร้อย!',
            showConfirmButton: false,
            timer: 1500
        }).then(function(){
            window.location = 'list_monks.php';
        });
    </script>";
    exit;
}
?>

<div class="p-8 max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-center text-indigo-700 mb-8">เพิ่มข้อมูลพระ/แม่ชี/เณร</h1>

    <form method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow space-y-6">

        <!-- รูป Preview -->
        <div class="flex justify-center mb-6">
            <img id="preview-image" src="https://via.placeholder.com/150" class="h-32 w-32 rounded-full object-cover border">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-2 text-gray-700">ประเภท:</label>
                <select name="prefix" required class="w-full border rounded px-3 py-2">
                    <option value="พระ">พระ</option>
                    <option value="แม่ชี">แม่ชี</option>
                    <option value="เณร">เณร</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ชื่อ:</label>
                <input type="text" name="first_name" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">นามสกุล:</label>
                <input type="text" name="last_name" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">วันเกิด:</label>
                <input type="date" name="birth_date" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">สัญชาติ:</label>
                <input type="text" name="nationality" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ชนเผ่า:</label>
                <input type="text" name="ethnicity" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">หมู่บ้านเกิด:</label>
                <input type="text" name="birthplace_village" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">อำเภอเกิด:</label>
                <input type="text" name="birthplace_district" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">จังหวัดเกิด:</label>
                <input type="text" name="birthplace_province" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ชื่อพ่อ:</label>
                <input type="text" name="father_name" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">ชื่อแม่:</label>
                <input type="text" name="mother_name" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">วัดที่สังกัด:</label>
                <input type="text" name="current_temple" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">วันบวช:</label>
                <input type="date" name="ordination_date" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">พรรษา:</label>
                <input type="number" name="age_pansa" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">เลขที่ใบสุทธิ:</label>
                <input type="text" name="certificate_number" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-gray-700">รูปถ่าย:</label>
                <input type="file" name="photo" id="photoInput" class="w-full border rounded px-3 py-2">
            </div>

        </div>

        <div>
            <label class="block mb-2 text-gray-700">หมายเหตุเพิ่มเติม:</label>
            <textarea name="notes" rows="4" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="text-center mt-8">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                💾 บันทึกข้อมูล
            </button>
            <a href="list_monks.php" class="ml-4 text-indigo-600 underline">← กลับไปยังรายการ</a>
        </div>

    </form>
</div>

<!-- Preview รูป -->
<script>
document.getElementById('photoInput').addEventListener('change', function(event){
    const reader = new FileReader();
    reader.onload = function(){
        const img = document.getElementById('preview-image');
        img.src = reader.result;
    }
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
});
</script>

<?php include 'footer.php'; ?>
