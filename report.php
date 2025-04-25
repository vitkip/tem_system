<?php
session_start();
require 'db.php';
require 'functions.php';
include 'header.php';

checkPermission();
?>

<!-- Add custom styles -->
<style>
.report-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.report-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(to bottom, #4f46e5, #7c3aed);
    opacity: 0;
    transition: all 0.3s ease;
}

.report-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.report-card:hover::before {
    opacity: 1;
}

.report-icon {
    transition: all 0.3s ease;
    font-size: 1.5rem;
}

.report-card:hover .report-icon {
    transform: scale(1.2);
}
</style>

<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 mb-4">
                ລະບົບລາຍງານ
            </h1>
            <p class="text-gray-600 text-lg">ເລືອກປະເພດລາຍງານທີ່ຕ້ອງການ</p>
        </div>

        <!-- Report Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Event Reports -->
            <a href="report_events.php" class="report-card rounded-xl p-8 group">
                <div class="flex items-start space-x-4">
                    <div class="report-icon bg-indigo-100 p-4 rounded-lg text-indigo-600 group-hover:bg-indigo-200">
                        📅
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-indigo-600 mb-2 group-hover:text-indigo-700">
                            ລາຍງານງານກິດນິມນ
                        </h2>
                        <p class="text-gray-600">ສະແດງງານທັງໝົດ, ງານທີ່ຜ່ານມາ, ງານໃນອະນາຄົດ</p>
                    </div>
                    <div class="text-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Monk Reports -->
            <a href="report_monks.php" class="report-card rounded-xl p-8 group">
                <div class="flex items-start space-x-4">
                    <div class="report-icon bg-purple-100 p-4 rounded-lg text-purple-600 group-hover:bg-purple-200">
                        👨‍🦲
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-purple-600 mb-2 group-hover:text-purple-700">
                            ລາຍງານພຣະ
                        </h2>
                        <p class="text-gray-600">ສະແດງຈໍານວນພຣະ, ແມ່ຊີ, ເນນ, ພັນສາ, ສະຖານະ</p>
                    </div>
                    <div class="text-purple-500 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Individual Reports -->
            <a href="report_by_monk.php" class="report-card rounded-xl p-8 group">
                <div class="flex items-start space-x-4">
                    <div class="report-icon bg-green-100 p-4 rounded-lg text-green-600 group-hover:bg-green-200">
                        🧾
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-green-600 mb-2 group-hover:text-green-700">
                            ລາຍງານລາຍຄົນ
                        </h2>
                        <p class="text-gray-600">ຄົ້ນຫາພຣະຕາມຊື່ເພື່ອດູປະຫວັດ ແລະ ປະຫວັດການໄປຮ່ວມງານ</p>
                    </div>
                    <div class="text-green-500 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Export Reports -->
            <a href="report_export.php" class="report-card rounded-xl p-8 group">
                <div class="flex items-start space-x-4">
                    <div class="report-icon bg-red-100 p-4 rounded-lg text-red-600 group-hover:bg-red-200">
                        📥
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-red-600 mb-2 group-hover:text-red-700">
                            ດາວໂຫຼດລາຍງານ
                        </h2>
                        <p class="text-gray-600">ສົ່ງອອກລາຍງານເປັນ PDF ຫຼື Excel</p>
                    </div>
                    <div class="text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>