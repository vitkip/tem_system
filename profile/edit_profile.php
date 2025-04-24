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
    <title>ແກ້ໄຂຂໍ້ມູນສ່ວນໂຕ</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao+Looped:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Lao Looped', sans-serif;
            font-size: 16px;
        }
    </style>

</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 to-white py-12">

<div class="max-w-4xl mx-auto px-4">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header with Gradient -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8">
            <div class="flex flex-col items-center">
                <!-- Current Profile Image -->
                <div class="relative group">
                    <div class="h-32 w-32 rounded-full overflow-hidden border-4 border-white shadow-lg">
                        <img src="../uploads/<?= htmlspecialchars($user['profile_image'] ?? 'default.png') ?>" 
                             alt="Profile" 
                             class="h-full w-full object-cover">
                    </div>
                </div>
                <h1 class="mt-4 text-2xl font-bold text-white">ແກ້ໄຂຂໍ້ມູນສ່ວນໂຕ</h1>
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-6 md:p-8">
            <?php if ($error): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= htmlspecialchars($success) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Profile Update Form -->
            <div class="bg-gray-50 rounded-xl p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-user-circle mr-2 text-indigo-600"></i>
                    ຂໍ້ມູນໂປຣໄຟລ໌
                </h2>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="update_profile" value="1">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-indigo-500"></i>
                                ຊື່ຜູ້ໃຊ້
                            </label>
                            <input type="text" 
                                   name="username" 
                                   value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>" 
                                   required 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">
                                <i class="fas fa-camera mr-2 text-indigo-500"></i>
                                ເລືອກຮູບໂປຣໄຟລ໌ໃໝ່
                            </label>
                            <input type="file" 
                                   name="profile_image" 
                                   accept="image/*" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 
                                       transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            ບັນທຶກຂໍ້ມູນ
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Change Form -->
            <div class="bg-gray-50 rounded-xl p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-key mr-2 text-indigo-600"></i>
                    ປ່ຽນລະຫັດຜ່ານ
                </h2>

                <form method="POST" class="space-y-6">
                    <input type="hidden" name="change_password" value="1">

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-indigo-500"></i>
                                ລະຫັດຜ່ານປະຈຸບັນ
                            </label>
                            <input type="password" 
                                   name="current_password" 
                                   required 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 mb-2">
                                    <i class="fas fa-key mr-2 text-indigo-500"></i>
                                    ລະຫັດຜ່ານໃໝ່
                                </label>
                                <input type="password" 
                                       name="new_password" 
                                       required 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2">
                                    <i class="fas fa-check-circle mr-2 text-indigo-500"></i>
                                    ຢືນຢັນລະຫັດຜ່ານໃໝ່
                                </label>
                                <input type="password" 
                                       name="confirm_password" 
                                       required 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 
                                       transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-key mr-2"></i>
                            ປ່ຽນລະຫັດຜ່ານ
                        </button>
                    </div>
                </form>
            </div>

            <!-- Back Button -->
            <div class="mt-8 flex justify-center">
                <a href="../profile/profile.php" 
                   class="inline-flex items-center px-6 py-3 border-2 border-indigo-600 text-indigo-600 rounded-lg 
                          hover:bg-indigo-50 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    ກັບໄປໂປຣໄຟລ໌
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
