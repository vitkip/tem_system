<script>
pdfMake.fonts = {
    NotoSansLao: {
        normal: '/tem_system/fonts/NotoSansLao-Regular.ttf',
        bold: '/tem_system/fonts/NotoSansLao-Bold.ttf',
        italics: '/tem_system/fonts/NotoSansLao-Regular.ttf',
        bolditalics: '/tem_system/fonts/NotoSansLao-Bold.ttf'
    }
};

$(document).ready(function () {
    var table = $('#monkTable').DataTable({
        responsive: true,
        scrollX: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pdfHtml5',
                text: 'ສົ່ງອອກ PDF',
                pageSize: 'A4',
                orientation: 'portrait',
                customize: function (doc) {
                    doc.defaultStyle = {
                        font: 'NotoSansLao',
                        fontSize: 14
                    };
                    
                    // โลโก้ (ใส่ base64 image หรือ URL ที่โหลดได้)
                    var logoBase64 = '<?= getLogoBase64() ?>'; 

                    // เพิ่มโลโก้และหัวกระดาษ
                    doc.content.splice(0, 0, {
                        columns: [
                            {
                                image: logoBase64,
                                width: 50
                            },
                            {
                                text: 'ລາຍງານລາຍຊື່ພຣະ/ແມ່ຊີ/ເນນ\n\n',
                                alignment: 'center',
                                fontSize: 18,
                                bold: true,
                                margin: [0, 15, 0, 0]
                            }
                        ],
                        columnGap: 10,
                        margin: [0, 0, 0, 20]
                    });

                    // ตั้งค่าระยะขอบหน้า
                    doc.pageMargins = [40, 100, 40, 60];
                },
                exportOptions: {
                    columns: [2, 3, 4]  // ส่งออกเฉพาะคอลัมน์ ชื่อ / วัดปัจจุบัน / ประเภท
                }
            },
            {
                extend: 'excelHtml5',
                text: 'ສົ່ງອອກ Excel',
                exportOptions: {
                    columns: [2, 3, 4]
                }
            }
        ],
        language: {
            search: "ຄົ້ນຫາ:",
            lengthMenu: "ສະແດງ _MENU_ ລາຍການ",
            info: "ສະແດງ _START_ ຫາ _END_ ຈາກ _TOTAL_ ລາຍການ",
            paginate: {
                first: "ໜ້າທໍາອິດ",
                last: "ໜ້າສຸດທ້າຍ",
                next: "ຖັດໄປ",
                previous: "ກ່ອນໜ້າ"
            }
        }
    });

    // Filter by Prefix
    $('#filterPrefix').on('change', function() {
        table.column(4).search(this.value).draw();
    });
});
</script>