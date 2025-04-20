<?php
session_start();
require '../db.php';

// ถ้าไม่ได้ล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header('Location: ../register/login.php');
    exit();
}

$error = '';
$success = '';

$id = $_SESSION['user_id'];

// ดึงข้อมูล user ปัจจุบัน
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

// อัปเดตข้อมูลส่วนตัว + รูปโปรไฟล์
if (isset($_POST['update_profile'])) {
    $new_username = trim($_POST['username']);
    $profile_image = $_FILES['profile_image'];

    $image_filename = $user['profile_image']; // ใช้รูปเดิมก่อน

    if ($profile_image['error'] === 0) {
        $image_filename = uniqid() . '_' . basename($profile_image['name']);
        move_uploaded_file($profile_image['tmp_name'], '../uploads/' . $image_filename);

        // ลบรูปเก่าออก
        if (!empty($user['profile_image']) && file_exists('../uploads/' . $user['profile_image'])) {
            unlink('../uploads/' . $user['profile_image']);
        }
    }

    $stmt = $pdo->prepare("UPDATE users SET username = ?, profile_image = ? WHERE id = ?");
    $stmt->execute([$new_username, $image_filename, $id]);

    $_SESSION['username'] = $new_username;

    $success = '✅ อัปเดตข้อมูลสำเร็จ!';
    $_SESSION['profile_image'] = $image_filename;
}

// เปลี่ยนรหัสผ่าน
if (isset($_POST['change_password'])) {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    $stmt_pw = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt_pw->execute([$id]);
    $user_pw = $stmt_pw->fetch();

    if ($user_pw && password_verify($current_password, $user_pw['password'])) {
        if ($new_password === $confirm_password) {
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $id]);

            $success = '✅ เปลี่ยนรหัสผ่านสำเร็จ!';
        } else {
            $error = '❌ รหัสผ่านใหม่ไม่ตรงกัน';
        }
    } else {
        $error = '❌ รหัสผ่านปัจจุบันไม่ถูกต้อง';
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลส่วนตัวและเปลี่ยนรหัสผ่าน</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md space-y-8">

    <h2 class="text-2xl font-bold text-center">แก้ไขข้อมูลส่วนตัว</h2>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- แสดงรูปโปรไฟล์ -->
    <div class="flex justify-center">
        <img src="../uploads/<?= htmlspecialchars($user['profile_image'] ?? 'default.png') ?>" alt="รูปโปรไฟล์" class="h-24 w-24 rounded-full mb-4">
    </div>

    <!-- แบบฟอร์มแก้ไขชื่อ + อัปโหลดรูป -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="update_profile" value="1">

        <div class="mb-4">
            <label class="block text-gray-600">ชื่อผู้ใช้</label>
            <input type="text" name="username" value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-6">
            <label class="block text-gray-600">เลือกรูปโปรไฟล์ใหม่</label>
            <input type="file" name="profile_image" accept="image/*" class="w-full border rounded px-3 py-2 bg-white">
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">บันทึกข้อมูลส่วนตัว</button>
    </form>

    <hr class="my-6">

    <h2 class="text-2xl font-bold text-center">เปลี่ยนรหัสผ่าน</h2>

    <!-- แบบฟอร์มเปลี่ยนรหัสผ่าน -->
    <form method="POST">
        <input type="hidden" name="change_password" value="1">

        <div class="mb-4">
            <label class="block text-gray-600">รหัสผ่านปัจจุบัน</label>
            <input type="password" name="current_password" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-gray-600">รหัสผ่านใหม่</label>
            <input type="password" name="new_password" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-6">
            <label class="block text-gray-600">ยืนยันรหัสผ่านใหม่</label>
            <input type="password" name="confirm_password" required class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">เปลี่ยนรหัสผ่าน</button>
    </form>

    <div class="text-center mt-4">
        <a href="../profile/profile.php" class="text-blue-500 underline">กลับไปโปรไฟล์</a>
    </div>

</div>

</body>
</html>
