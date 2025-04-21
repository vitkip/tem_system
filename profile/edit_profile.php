<?php
session_start();
require '../db.php';

// ຖ້າບໍ່ໄດ້ login
if (!isset($_SESSION['user_id'])) {
    header('Location: register/login.php');
    exit();
}

$error = '';
$success = '';

$id = $_SESSION['user_id'];

// ດຶງຂໍ້ມູນຜູ້ໃຊ້ປະຈຸບັນ
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

// ອັບເດດຂໍ້ມູນສ່ວນໂຕ + ຮູບໂປຣໄຟລ໌
if (isset($_POST['update_profile'])) {
    $new_username = trim($_POST['username']);
    $profile_image = $_FILES['profile_image'];

    $image_filename = $user['profile_image'];

    if ($profile_image['error'] === 0) {
        $image_filename = uniqid() . '_' . basename($profile_image['name']);
        move_uploaded_file($profile_image['tmp_name'], '../uploads/' . $image_filename);

        // ລົບຮູບເກົ່າອອກ
        if (!empty($user['profile_image']) && file_exists('../uploads/' . $user['profile_image'])) {
            unlink('../uploads/' . $user['profile_image']);
        }
    }

    $stmt = $pdo->prepare("UPDATE users SET username = ?, profile_image = ? WHERE id = ?");
    $stmt->execute([$new_username, $image_filename, $id]);

    $_SESSION['username'] = $new_username;
    $_SESSION['profile_image'] = $image_filename;
    $success = '✅ ອັບເດດຂໍ້ມູນສໍາເລັດ!';
}

// ປ່ຽນລະຫັດຜ່ານ
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

            $success = '✅ ປ່ຽນລະຫັດຜ່ານສໍາເລັດ!';
        } else {
            $error = '❌ ລະຫັດໃໝ່ບໍ່ກົງກັນ';
        }
    } else {
        $error = '❌ ລະຫັດປະຈຸບັນບໍ່ຖືກຕ້ອງ';
    }
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>ແກ້ໄຂຂໍ້ມູນສ່ວນໂຕ ແລະ ປ່ຽນລະຫັດຜ່ານ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Lao', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md space-y-8">

    <h2 class="text-2xl font-bold text-center">ແກ້ໄຂຂໍ້ມູນສ່ວນໂຕ</h2>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- ແສດງຮູບໂປຣໄຟລ໌ -->
    <div class="flex justify-center">
        <img src="../uploads/<?= htmlspecialchars($user['profile_image'] ?? 'default.png') ?>" alt="ຮູບໂປຣໄຟລ໌" class="h-24 w-24 rounded-full mb-4">
    </div>

    <!-- ຟອມແກ້ໄຂຊື່ + ອັບໂຫລດຮູບ -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="update_profile" value="1">

        <div class="mb-4">
            <label class="block text-gray-600">ຊື່ຜູ້ໃຊ້</label>
            <input type="text" name="username" value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-6">
            <label class="block text-gray-600">ເລືອກຮູບໂປຣໄຟລ໌ໃໝ່</label>
            <input type="file" name="profile_image" accept="image/*" class="w-full border rounded px-3 py-2 bg-white">
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">ບັນທຶກຂໍ້ມູນສ່ວນໂຕ</button>
    </form>

    <hr class="my-6">

    <h2 class="text-2xl font-bold text-center">ປ່ຽນລະຫັດຜ່ານ</h2>

    <!-- ຟອມປ່ຽນລະຫັດ -->
    <form method="POST">
        <input type="hidden" name="change_password" value="1">

        <div class="mb-4">
            <label class="block text-gray-600">ລະຫັດຜ່ານປະຈຸບັນ</label>
            <input type="password" name="current_password" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-gray-600">ລະຫັດຜ່ານໃໝ່</label>
            <input type="password" name="new_password" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-6">
            <label class="block text-gray-600">ຢືນຢັນລະຫັດຜ່ານໃໝ່</label>
            <input type="password" name="confirm_password" required class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">ປ່ຽນລະຫັດຜ່ານ</button>
    </form>

    <div class="text-center mt-4">
        <a href="../profile/profile.php" class="text-blue-500 underline">ກັບໄປໂປຣໄຟລ໌</a>
    </div>

</div>

</body>
</html>
