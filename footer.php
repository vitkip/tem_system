</div> <!-- ปิด Main Content -->

<!-- Footer -->
<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto py-8 px-4 flex flex-col items-center space-y-6">

        <!-- โลโก้ -->
        <div>
            <img src="<?= BASE_URL ?>assets/logo.png" alt="Your Logo" class="h-50 w-16 rounded-full">
        </div>

        <!-- เมนู -->
        <nav class="flex flex-col md:flex-row md:space-x-6 items-center text-sm text-gray-500">
            <a href="dashboard.php" class="mb-2 md:mb-0 hover:text-indigo-600 transition">ແດດບອດ</a>
            <a href="list_monks.php" class="mb-2 md:mb-0 hover:text-indigo-600 transition">ລວມລາຍຊື່</a>
            <?php if (isAdmin()): ?>
            <a href="add_monk.php" class="mb-2 md:mb-0 hover:text-indigo-600 transition">ເພີ່ມ</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>profile/profile.php" class="mb-2 md:mb-0 hover:text-indigo-600 transition">ໂປຣຟາຍ</a>
            <a href="<?= BASE_URL ?>profile/edit_profile.php" class="mb-2 md:mb-0 hover:text-indigo-600 transition">ຕັ້ງຄ່າ</a>
            <a href="<?= BASE_URL ?>register/logout.php" class="mb-2 md:mb-0 hover:text-indigo-600 transition">ອອກຈາກລະບົບ</a>
        </nav>

        <!-- ไอคอนโซเชียล -->
        <div class="flex justify-center space-x-6">
            <a href="#" class="text-gray-400 hover:text-indigo-600 transition"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-gray-400 hover:text-indigo-600 transition"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-gray-400 hover:text-indigo-600 transition"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-gray-400 hover:text-indigo-600 transition"><i class="fab fa-github"></i></a>
            <a href="#" class="text-gray-400 hover:text-indigo-600 transition"><i class="fab fa-youtube"></i></a>
        </div>

        <!-- ลิขสิทธิ์ -->
        <p class="text-center text-gray-400 text-xs">&copy; 2025 ວັດປ່າໜອງບົວທອງໃຕ້</p>
    </div>
</footer>

<!-- Font Awesome สำหรับไอคอน -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>
</html>
