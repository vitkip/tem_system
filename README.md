<p align="center"><a href="https://www.facebook.com/phathasira" target="_blank"><img src="https://github.com/vitkip/tem_system/blob/main/assets/logo.png" width="400" alt=" Logo"></a></p>

## 📂 ຜູ້ພັດທະນາ |ພັດທະນາຈາກ AI chatgpt|
<p align="center">
<img src="https://github.com/vitkip/tem_system/blob/main/uploads/68063716a4ef8_von.png" width="50" style="max-width: 50%; alt="Build Status">
<br>
<a href="https://www.facebook.com/phathasira" align="center">facebook:ວຣ ນັນທິວັດທະໂນ</a>
</p>

# ລະບົບຈັດການຂໍ້ມູນພຣະສົງ (Temple Management System)

ເປົ້າໝາຍຂອງລະບົບນີ້ແມ່ນ ການບໍລິຫານ ແລະ ຈັດການຂໍ້ມູນພຣະ, ແມ່ຊີ, ເນນ ແລະ ນັດຫມາຍງານກິດນິມົນ ຢ່າງມີລະບົບ.

---

## 📂 ໂຄງສ້າງໂຟນເດີ

tem_system/ ├── db.php ການເຊື່ອມຕໍ່ຖານຂໍ້ມູນ ├── header.php ໂຄງສ້າງສ່ວນເທິງ (Navbar, Session Check) ├── footer.php ສ່ວນທ້າຍໜ້າ ├── dashboard.php ໜ້າ Dashboard ສະແດງສະຖິຕິ ├── list_monks.php ລາຍການຂໍ້ມູນພຣະ/ແມ່ຊີ/ເນນ ├── add_monk.php ເພີ່ມຂໍ້ມູນພຣະ/ແມ່ຊີ/ເນນ ├── edit_monk.php ແກ້ໄຂຂໍ້ມູນ ├── view_monk.php ເບິ່ງລາຍລະອຽດ ├── delete_monk.php ລົບຂໍ້ມູນ ├── event/ ໂຟນເດີການຈັດການງານກິດນິມົນ │ ├── list_events.php │ ├── add_event.php │ ├── edit_event.php │ ├── view_event.php │ ├── calendar.php │ ├── assign_monks.php ├── fonts/ ຟອນລາວ (NotoSansLao-Regular.ttf, NotoSansLao-Bold.ttf) ├── uploads/ ຮູບພາບພຣະ ແລະ ໂປຣໄຟລ໌ ├── assets/ ຮູບໂລໂກ້ ແລະ ຟາຍ static ├── register/ ຫນ້າ Login/Register/Logout ├── profile/ ຂໍ້ມູນໂປຣໄຟລ໌ຜູ້ໃຊ້ ├── admin/ ຈັດການຜູ້ໃຊ້ (Admin Only)

---

## 🛠️ ຄຸນສົມບັດຂອງລະບົບ

- 🔐 ຈັດການສິດທິ Admin / Member
- 📋 ເບິ່ງ ແລະ ຄົ້ນຫາລາຍຊື່ພຣະ / ແມ່ຊີ / ເນນ
- ➕ ເພີ່ມ - ✏️ ແກ້ໄຂ - 🗑️ ລົບ ຂໍ້ມູນພຣະ
- 📅 ປະຕິທິນງານກິດນິມົນ (FullCalendar)
- 📑 ເພີ່ມ-ແກ້ໄຂງານກິດນິມົນ
- 🔗 ເລືອກພຣະໄປງານ (Assign Monks)
- 📥 ສົ່ງອອກຂໍ້ມູນລາຍການ (PDF/Excel)
- 📱 Responsive ຮອງຮັບມືຖື ແລະ ເຄື່ອງໃຫຍ່
- 🚨 Popup Notification (SweetAlert)
- 🖨️ ເອກະສານ PDF ໃຊ້ຟອນ Noto Sans Lao ເພື່ອຮອງຮັບພາສາລາວ


### 📂 ຮ່ວມສະໜັບສະໜຸນຜູ້ພັດທະນາ
<p align="center">
<img src="https://github.com/vitkip/tem_system/blob/main/assets/QRCODE.PNG"  alt="Build Status">

### 📂 ຮ່ວມສະໜັບສະໜຸນຜູ້ພັດທະນາ
<p align="center">
<img src="https://github.com/vitkip/tem_system/blob/main/assets/QRCODE.PNG"  alt="Build Status">

---

## 🚀 ການໃຊ້ງານລະບົບ

### ຕິດຕັ້ງ
1. ດາວໂຫລດໂຄງງານ
2. ຕິດຕັ້ງ XAMPP (Apache + MySQL)
3. ສ້າງຖານຂໍ້ມູນ `tem_system` ແລະ import `tem_system.sql`
4. ຕັ້ງຄ່າການເຊື່ອມຕໍ່ DB ໃນ `db.php`

```php
$host = 'localhost';
$dbname = 'tem_system';
$username = 'root';
$password = '';


## 🛠️ ຄຸນສົມບັດຂອງລະບົບ

ເຄື່ອງມື | ໃຊ້ສໍາລັບ
PHP (PDO) | ຈັດການຖານຂໍ້ມູນ
MySQL | ຖານຂໍ້ມູນຂໍ້ມູນ
HTML, Tailwind CSS | ສ້າງ UI/UX
DataTables | ຕາຕະລາງລາຍການທີ່ຄົ້ນຫາໄດ້
SweetAlert2 | ປ໋ອບອັບແຈ້ງເຕືອນ
FullCalendar.js | ປະຕິທິນງານກິດ
pdfMake + JSZip | ສົ່ງອອກ PDF/Excel

 📂 ຮ່ວມສະໜັບສະໜຸນຜູ້ພັດທະນາ
<p align="center">
<img src="https://github.com/vitkip/tem_system/blob/main/uploads/68063716a4ef8_von.png" width="50" style="max-width: 50%; alt="Build Status">

