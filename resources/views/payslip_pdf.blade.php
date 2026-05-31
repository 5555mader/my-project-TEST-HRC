<!DOCTYPE html>
<html lang="th">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สลิปเงินเดือน {{ $data['month'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 🌟 ดึงฟอนต์ Sarabun ผ่าน @import สำหรับการประมวลผล PDF 🌟 */
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap');

        /* กำหนดให้โครงสร้างทั้งหมดในหน้า PDF ใช้ฟอนต์ Sarabun */
        body,
        table,
        tr,
        td,
        th,
        p,
        span,
        div,
        h1,
        h2,
        h3 {
            font-family: 'Sarabun', sans-serif !important;
            font-size: 14px;
            /* ปรับขนาดเริ่มต้นให้พอดีกับเอกสาร A4 */
            line-height: 1.6;
        }

        /* สิทธิ์เพิ่มเติมสำหรับตัวหนาในระบบ PDF */
        .fw-bold,
        b,
        strong,
        th {
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background-color: white;
            }
        }
    </style>
</head>

<body class="bg-gray-100 p-5">

    <div class="max-w-3xl mx-auto bg-white p-8 border border-gray-200 rounded-lg shadow-md">
        <div class="text-center mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">สลิปเงินเดือน</h1>
            <p class="text-gray-600">ประจำเดือน: {{ $data['month'] }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-8 text-sm">
            <div>
                <p class="font-semibold text-gray-700">ชื่อพนักงาน:</p>
                <p class="text-gray-900">{{ $data['user']->name }}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-700">ตำแหน่ง:</p>
                <p class="text-gray-900">พนักงานบริษัท</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="font-bold text-green-600 border-b pb-1 mb-2">รายได้</h3>
                <div class="flex justify-between py-1">
                    <span>เงินเดือนพื้นฐาน</span>
                    <span>{{ number_format($data['base_salary'], 2) }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span>เบี้ยเลี้ยง/อื่นๆ</span>
                    <span>{{ number_format($data['allowance'], 2) }}</span>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-red-600 border-b pb-1 mb-2">รายการหัก</h3>
                <div class="flex justify-between py-1">
                    <span>ภาษีหัก ณ ที่จ่าย</span>
                    <span>{{ number_format($data['tax'], 2) }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span>ประกันสังคม</span>
                    <span>{{ number_format($data['social_security'], 2) }}</span>
                </div>
            </div>
        </div>

        <div class="border-t pt-4 bg-gray-50 p-4 rounded">
            <div class="flex justify-between text-lg font-bold">
                <span>รายรับสุทธิ</span>
                <span class="text-blue-700">{{ number_format($data['net_total'], 2) }} บาท</span>
            </div>
        </div>

        <div class="mt-8 text-center no-print">
            <button onclick="window.print()"
                class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
                พิมพ์สลิป (Print)
            </button>
        </div>
    </div>

</body>

</html>
