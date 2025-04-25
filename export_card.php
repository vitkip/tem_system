<?php
session_start();
require 'db.php';
require 'functions.php';
require 'vendor/autoload.php';

// ตรวจสอบสิทธิ์การเข้าถึง
checkPermission();

// เส้นทางไฟล์ฟอนต์ลาวพร้อมการจัดการข้อผิดพลาด
$fontPath = dirname(__FILE__) . '/fonts/Phetsarath OT.ttf';

// ตรวจสอบการมีอยู่ของไฟล์ฟอนต์
if (!file_exists($fontPath)) {
    error_log('ไม่พบไฟล์ฟอนต์: ' . $fontPath);
    die('ไม่พบไฟล์ฟอนต์ กรุณาตรวจสอบการติดตั้งฟอนต์');
}

// เพิ่มฟอนต์พร้อมการจัดการข้อผิดพลาด
$fontname = TCPDF_FONTS::addTTFfont($fontPath, 'TrueTypeUnicode');
if (!$fontname) {
    error_log('ไม่สามารถเพิ่มฟอนต์ได้: ' . $fontPath);
    die('ไม่สามารถเพิ่มฟอนต์ได้ กรุณาตรวจสอบไฟล์ฟอนต์');
}

function generateMonkCard($monk) {
    global $fontname;
    
    // สร้าง PDF พร้อมกำหนดขนาดที่แน่นอน
    $pdf = new TCPDF('L', 'mm', array(85.6, 54), true, 'UTF-8');
    $pdf->SetCreator('ระบบ TEM');
    $pdf->SetAuthor('ระบบจัดการวัด');
    $pdf->SetTitle('บัตรประจำตัวพระสงฆ์');

    // ลบส่วนหัวและส่วนท้าย
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(false);
    $pdf->AddPage();

    // พื้นหลังแบบไล่สี
    $pdf->Rect(0, 0, 85.6, 14, 'F', array(), array(79, 70, 229)); // สีน้ำเงินอินดิโก-600
    $pdf->Rect(85.6, 0, -85.6, 14, 'F', array(), array(147, 51, 234)); // สีม่วง-600
    
    // พื้นหลังสีขาวสำหรับส่วนเนื้อหา
    $pdf->Rect(0, 14, 85.6, 40, 'F', array(), array(255, 255, 255));
    
    // ข้อความคอมเมนต์ - ตัวอย่างการใช้รูปภาพเป็นพื้นหลัง
    // if (file_exists('assets/card-bg.png')) {
    //     $pdf->Image('assets/card-bg.png', 0, 0, 85.6, 40, '', '', '', false,);
    // }

    // ขอบบัตรที่มีความหนาคงที่
    $pdf->SetLineWidth(0.5);
    $pdf->RoundedRect(2, 2, 81.6, 50, 3.5, '1111', 'D', array('color' => array(255, 255, 255)));

    // โลโก้วัดและการวางตำแหน่ง
    if (file_exists('assets/temple-logo.png')) {
        // ตำแหน่ง x คำนวณจากความกว้างของบัตร (85.6) ลบความกว้างของโลโก้ (10) ลบระยะขอบ (3)
        $pdf->Image('assets/temple-logo.png', 72.6, 3, 10, 10, '', '', '', false, 300);
    }

    // หัวข้อบัตรพร้อมการจัดตำแหน่งที่ดีขึ้น
    $pdf->SetFont($fontname, 'B', 14);
    $pdf->SetTextColor(255, 255, 255);
    // ใช้ Cell พร้อมความกว้างและการจัดตำแหน่งที่ชัดเจน
    $pdf->SetXY(15, 4);
    $pdf->Cell(55, 6, 'ວັດປ່າໜອງບົວທອງໃຕ້', 0, 1, 'C');

    // กรอบรูปภาพพร้อมการจัดตำแหน่งที่ดีขึ้น - ขยับลงด้านล่างอีกเล็กน้อย
    if (!empty($monk['photo']) && file_exists('uploads/' . $monk['photo'])) {
        // พื้นหลังสีขาวสำหรับกรอบรูป - เพิ่มค่า Y จาก 9 เป็น 11
        $pdf->SetFillColor(255, 255, 255);
        $pdf->RoundedRect(5, 11, 26, 31, 2, '1111', 'DF');
        
        // พื้นหลังวงกลมสีขาวสำหรับรูปภาพ - เพิ่มค่า Y จาก 24 เป็น 26
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Circle(18, 26, 12, 0, 360, 'DF');
        
        // การวางรูปภาพ - เพิ่มค่า Y จาก 9 เป็น 11
        $pdf->Image(
            'uploads/' . $monk['photo'], 
            6.4,  // x - ตำแหน่งแกน x
            11,   // y - ปรับลงด้านล่างเพิ่มอีก 2 หน่วย
            23,   // ความกว้าง
            30,   // ความสูง
            '',   // รูปแบบ (อัตโนมัติ)
            '',   // ลิงก์ URL
            'T',  // การจัดตำแหน่ง
            false, // ปรับขนาด
            300,   // ความละเอียด dpi
            '',    // การจัดตำแหน่งหน้า
            false, // เป็นมาส์กหรือไม่
            false, // มาส์กรูปภาพ
            0,     // เส้นขอบ
            false  // พอดีกรอบ - คงอัตราส่วนภาพ
        );
        
    }

    // ข้อมูลพระสงฆ์พร้อมระยะห่างที่สม่ำเสมอ
    $pdf->SetTextColor(75, 85, 99); // สีเทาข้อความ-600
    $x = 34; // ตำแหน่ง x เริ่มต้นสำหรับข้อความทั้งหมด
    $y = 17; // ปรับตำแหน่ง y เริ่มต้น
    $lh = 5; // ความสูงบรรทัดระหว่างองค์ประกอบ

    // ชื่อพร้อมการจัดตำแหน่งที่เหมาะสม
    $pdf->SetFont($fontname, 'B', 11);
    $pdf->SetXY($x, $y);
    // คำนวณความกว้างชื่อเพื่อให้แน่ใจว่าพอดี
    $nameText = $monk['prefix'] . ' ' . $monk['first_name'] . ' ' . $monk['last_name'];
    $nameWidth = $pdf->GetStringWidth($nameText);
    
    // ถ้าชื่อยาวเกินไป ใช้ฟอนต์ขนาดเล็กลง
    if ($nameWidth > 45) {
        $pdf->SetFont($fontname, 'B', 9);
    }
    $pdf->Cell(45, $lh, $nameText, 0, 1);

    // ข้อมูลวัด
    $y += $lh;
    addIconAndText($pdf, $fontname, $x, $y, 'ວັດ:', $monk['current_temple'], '🏛️');

    // วันเกีด
    function formatLaoDate($dateStr) {
        // แปลง string เป็น timestamp
        $timestamp = strtotime($dateStr);
    
        // รายชื่อเดือนเป็นภาษาลาว
        $laoMonths = [
            1 => 'ມັງກອນ', 'ກຸມພາ', 'ມີນາ', 'ເມສາ',
            'ພຶດສະພາ', 'ມິຖຸນາ', 'ກໍລະກົດ', 'ສິງຫາ',
            'ກັນຍາ', 'ຕຸລາ', 'ພະຈິກ', 'ທັນວາ'
        ];
    
        $day = date('j', $timestamp); // ไม่มี 0 นำหน้า
        $month = $laoMonths[(int)date('n', $timestamp)];
        $year = date('Y', $timestamp);
    
        return "$day $month ປີ $year";
    }
    $birthFormatted = formatLaoDate($monk['birth_date']);
    $y += $lh;
    addIconAndText($pdf, $fontname, $x, $y, 'ເກີດ:', $birthFormatted, '⏳');
   // addIconAndText($pdf, $fontname, $x, $y, 'ເກີດ:', $monk['birth_date'], '⏳');

    // เลขบัตร
    $y += $lh;
    addIconAndText($pdf, $fontname, $x, $y, 'ເລກບັດ:', $monk['certificate_number'], '🆔');

  // สไตล์ของ QR Code
        $style = array(
            'border' => false,
            'padding' => 2,
            'fgcolor' => array(0, 0, 128), // น้ำเงินเข้ม
            'bgcolor' => array(255, 255, 255), // ขาว
            'module_width' => 1, // ความกว้างของบล็อก
            'module_height' => 1 // ความสูงของบล็อก
        );
    
    // ข้อมูลสำหรับ QR โค้ด
    $qrData = json_encode([
        'id' => $monk['id'],
        'cert' => $monk['certificate_number'],
        'name' => $monk['prefix'] . ' ' . $monk['first_name'] . ' ' . $monk['last_name']
    ]);

    // QR code ที่มีขอบลดลง
    $pdf->SetFillColor(255, 255, 255);
    // ตัวอย่างคอมเมนต์ - พื้นหลังขาวสำหรับ QR code
    // $pdf->RoundedRect(61, 32, 15, 15, 1, '1111', 'F');
    // วางตำแหน่ง QR code โดยเหลือขอบน้อยที่สุด
    $pdf->write2DBarcode($qrData, 'QRCODE,L', 68, 38, 15, 15, $style);

    // 
    
    $pdf->SetFont($fontname, '', 8);
    $pdf->SetTextColor(156, 163, 175); // สีเทาข้อความ-400
    $pdf->SetXY(5, 47);
    $pdf->Cell(75.6, 4, 'ອອກໃຫ້ ວັນທີ: ' . date('d/m/Y'), 0, 0, 'C');

    return $pdf;
}

// ฟังก์ชันช่วยเหลือสำหรับการจัดแนวข้อความที่ดีขึ้น
function addIconAndText($pdf, $fontname, $x, $y, $label, $value, $icon) {
    // ตั้งค่าขนาดฟอนต์ที่สม่ำเสมอ
    $pdf->SetFont($fontname, '', 9); // เล็กลงเล็กน้อยเพื่อการจัดแนวที่ดีขึ้น
    $pdf->SetXY($x, $y);
    $pdf->Cell(5, 6, $icon, 0, 0); // ไอคอน
    $pdf->Cell(12, 6, $label, 0, 0); // ความกว้างคงที่สำหรับป้ายกำกับ
    
    // ตรวจสอบว่าค่ายาวเกินไปหรือไม่และปรับขนาดฟอนต์หากจำเป็น
    $pdf->SetFont($fontname, 'B', 9);
    $valueWidth = $pdf->GetStringWidth($value);
    if ($valueWidth > 30) {
        $pdf->SetFont($fontname, 'B', 8); // ฟอนต์ขนาดเล็กสำหรับค่าที่ยาว
    }
    $pdf->Cell(40, 6, $value, 0, 1);
}

// ตรวจสอบความถูกต้องของพารามิเตอร์ monk_id
if (!isset($_GET['monk_id']) || !is_numeric($_GET['monk_id'])) {
    die('คำขอไม่ถูกต้อง: ไม่มีหรือรหัสพระสงฆ์ไม่ถูกต้อง');
}

// ดึงข้อมูลพระสงฆ์พร้อมการจัดการข้อผิดพลาดที่เหมาะสม
try {
    // เตรียมและประมวลผลคำสั่ง SQL
    $stmt = $pdo->prepare("SELECT * FROM monks WHERE id = ?");
    $stmt->execute([$_GET['monk_id']]);
    $monk = $stmt->fetch();

    // ตรวจสอบว่าพบข้อมูลพระสงฆ์หรือไม่
    if (!$monk) {
        die('ไม่พบพระสงฆ์รหัส: ' . htmlspecialchars($_GET['monk_id']));
    }

    // สร้างและส่งออกบัตร
    $pdf = generateMonkCard($monk);
    
    // ตรวจสอบว่าสร้าง PDF สำเร็จหรือไม่
    if (!$pdf) {
        throw new Exception('การสร้าง PDF ล้มเหลว');
    }

    // ส่งออก PDF ไปยังเบราว์เซอร์
    $pdf->Output('monk_card_' . $monk['id'] . '.pdf', 'I');
    
} catch (Exception $e) {
    // บันทึกข้อผิดพลาดและแสดงข้อความแจ้งผู้ใช้
    error_log('ข้อผิดพลาดในการสร้าง PDF: ' . $e->getMessage());
    die('ເກີດຂໍ້ຜິດພາດໃນການສ້າງບັດ. ກະລຸນາລອງໃໝ່ອີກຄັ້ງ.');
}