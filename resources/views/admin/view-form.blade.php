<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกข้อความภายในองค์กร - เลขที่ {{ $document->doc_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">

    <style>
        /* บังคับให้โครงสร้างหน้าจอ ตาราง ฟอร์มกรอกข้อมูล และปุ่มต่างๆ ใช้ฟอนต์ Sarabun ทั้งหมด */
        body,
        html,
        table,
        thead,
        tbody,
        tr,
        th,
        td,
        input,
        select,
        textarea,
        button,
        p,
        span,
        div,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .card,
        .modal-content,
        .alert,
        .badge {
            font-family: 'Sarabun', sans-serif !important;
        }

        .print-a4-paper {
            background: white;
            width: 100%;
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 25mm 20mm 20mm 20mm;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            color: black;
        }

        /* ปรับสไตล์เนื้อหาที่มาจาก Quill Editor */
        .document-content {
            font-size: 16px;
            line-height: 1.8;
            min-height: 100mm;
        }

        .document-content p {
            margin-bottom: 10px;
        }

        .document-content ul {
            list-style-type: disc;
            margin-left: 20px;
        }

        .document-content ol {
            list-style-type: decimal;
            margin-left: 20px;
        }

        /* คลาสลายเซ็น */
        .signature-img {
            max-height: 50px;
            object-fit: contain;
            margin: -20px auto 5px auto;
            display: block;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            .print-a4-paper {
                box-shadow: none;
                margin: 0;
                padding: 10mm 15mm;
                width: 100%;
                max-width: 100%;
                min-height: auto;
            }
        }
    </style>
</head>

<body class="bg-gray-200 py-10">

    {{-- ปุ่มควบคุม (ไม่แสดงตอนพิมพ์) --}}
    <div class="max-w-[210mm] mx-auto mb-4 flex justify-between items-center no-print px-4 md:px-0">
        <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.close()" 
            class="text-sm px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
            ย้อนกลับ / ปิดหน้าต่าง
        </button>
        <button type="button" onclick="window.print()"
            class="text-sm px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            พิมพ์เอกสาร
        </button>
    </div>

    {{-- กระดาษ A4 --}}
    <div class="print-a4-paper">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold tracking-widest">บันทึกข้อความ</h2>
        </div>

        {{-- ส่วนหัวบันทึกข้อความ --}}
        <div class="border-b-2 border-black pb-4 mb-6 text-lg space-y-4">

            {{-- บรรทัดที่ 1: สาขา และ ส่วนงาน --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-end">
                    <span class="font-bold shrink-0 mr-3">สาขา</span>
                    <div class="w-full border-b border-dotted border-gray-600 py-1">{{ $document->branch ?? '-' }}</div>
                </div>
                <div class="flex items-end">
                    <span class="font-bold shrink-0 mr-3">ส่วนงาน/แผนก</span>
                    <div class="w-full border-b border-dotted border-gray-600 py-1">{{ $document->department }}</div>
                </div>
            </div>

            {{-- บรรทัดที่ 2: ที่ และ วันที่ --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-end">
                    <span class="font-bold shrink-0 mr-3">ที่</span>
                    <div class="w-full border-b border-dotted border-gray-600 py-1">{{ $document->doc_number }}</div>
                </div>
                <div class="flex items-end">
                    <span class="font-bold shrink-0 mr-3">วันที่</span>
                    @php
                        $thaiMonths = [
                            'มกราคม',
                            'กุมภาพันธ์',
                            'มีนาคม',
                            'เมษายน',
                            'พฤษภาคม',
                            'มิถุนายน',
                            'กรกฎาคม',
                            'สิงหาคม',
                            'กันยายน',
                            'ตุลาคม',
                            'พฤศจิกายน',
                            'ธันวาคม',
                        ];
                        $docDate = \Carbon\Carbon::parse($document->created_at);
                        $thaiDate =
                            $docDate->format('j') .
                            ' ' .
                            $thaiMonths[$docDate->format('n') - 1] .
                            ' ' .
                            ($docDate->format('Y') + 543);
                    @endphp
                    <div class="w-full border-b border-dotted border-gray-600 py-1 text-center">{{ $thaiDate }}
                    </div>
                </div>
            </div>

            {{-- บรรทัดที่ 3: เรื่อง --}}
            <div class="flex items-end">
                <span class="font-bold shrink-0 mr-3">เรื่อง</span>
                <div class="w-full border-b border-dotted border-gray-600 py-1">{{ $document->title }}</div>
            </div>

            {{-- บรรทัดที่ 4: เรียน --}}
            <div class="flex items-end">
                <span class="font-bold shrink-0 mr-3">เรียน</span>
                <div class="w-full border-b border-dotted border-gray-600 py-1">{{ $document->to_position }}</div>
            </div>

            {{-- บรรทัดที่ 5: จำนวนเงิน/งบประมาณ (แสดงเฉพาะถ้ามี) --}}
            @if ($document->amount)
                <div class="flex items-end">
                    <span class="font-bold shrink-0 mr-3">จำนวนเงิน/งบประมาณ</span>
                    <div
                        class="border-b border-dotted border-gray-600 py-1 font-semibold text-blue-700 min-w-[150px] text-center">
                        {{ number_format($document->amount, 2) }}
                    </div>
                    <span class="font-bold shrink-0 ml-3">บาท</span>
                </div>
            @endif

            {{-- บรรทัดที่ 6: CC --}}
            @if (!empty($document->cc_users))
                <div class="flex items-start mt-2">
                    <span class="font-bold shrink-0 mr-3">สำเนาส่ง (CC)</span>
                    <div class="w-full text-sm mt-1">
                        @php
                            // ดึงรายชื่อพนักงานที่ถูก CC มาแสดง
                            $ccUsers = \App\Models\User::whereIn('id', $document->cc_users)->pluck('name')->toArray();
                        @endphp
                        {{ implode(', ', $ccUsers) }}
                    </div>
                </div>
            @endif

            {{-- บรรทัดที่ 7: สิ่งที่ส่งมาด้วย (ไฟล์แนบ) --}}
            @php
                // ดึงไฟล์แนบจากตาราง documents_file (ถ้ามี)
                $attachedFiles = \App\Models\DocumentFile::where('document_id', $document->id)->get();
            @endphp
            @if ($attachedFiles->count() > 0)
                <div class="flex items-start mt-2">
                    <span class="font-bold shrink-0 mr-3">สิ่งที่ส่งมาด้วย</span>
                    <div class="w-full text-sm mt-1">
                        มีเอกสารแนบจำนวน {{ $attachedFiles->count() }} ไฟล์ (ดูได้ในระบบคลังเอกสาร)
                    </div>
                </div>
            @endif
        </div>

        {{-- เนื้อหาเอกสาร --}}
        <div class="document-content mt-6">
            {!! $document->content !!}
        </div>

        {{-- โซนลายเซ็นด้านล่าง (ปรับรองรับ 3 คน) --}}
        <div
            class="absolute bottom-[20mm] left-[15mm] right-[15mm] grid grid-cols-3 gap-2 text-center text-sm text-gray-700">

            {{-- 1. ลายเซ็นผู้ขออนุมัติ --}}
            <div>
                @if (
                    $document->user &&
                        $document->user->signature &&
                        file_exists(public_path('uploads/signatures/' . $document->user->signature)))
                    <p class="mb-2 text-left ml-6">ลงชื่อ</p>
                    <img src="{{ asset('uploads/signatures/' . $document->user->signature) }}" class="signature-img"
                        alt="Signature">
                    <p class="border-t border-dotted border-gray-500 mx-6 pt-1 text-xs">ผู้ขออนุมัติ</p>
                @else
                    <p class="mb-10">ลงชื่อ..............................................ผู้ขออนุมัติ</p>
                @endif
                <p class="font-medium text-gray-900">({{ $document->user->name ?? 'ไม่ระบุพนักงาน' }})</p>
                <p class="text-xs text-gray-500 mt-1">วันที่
                    {{ \Carbon\Carbon::parse($document->created_at)->format('d/m/Y') }}</p>
            </div>

            {{-- 2. ลายเซ็นผู้อนุมัติคนที่ 1 (Manager/HR) --}}
            <div>
                @if ($document->status == 'approved' || $document->status == 'pending_step_2')
                    {{-- อนุมัติผ่านขั้นแรกมาแล้ว แสดงลายเซ็นได้ --}}
                    @if (
                        $document->approver &&
                            $document->approver->signature &&
                            file_exists(public_path('uploads/signatures/' . $document->approver->signature)))
                        <p class="mb-2 text-left ml-6">ลงชื่อ</p>
                        <img src="{{ asset('uploads/signatures/' . $document->approver->signature) }}"
                            class="signature-img" alt="Signature">
                        <p class="border-t border-dotted border-gray-500 mx-6 pt-1 text-xs">ผู้อนุมัติ (1)</p>
                    @else
                        <p class="mb-10 text-green-600">✓ อนุมัติแล้ว (ไม่มีลายเซ็น)</p>
                    @endif
                    <p class="font-bold text-gray-900">({{ $document->approver->name ?? '-' }})</p>
                    <p class="text-xs text-gray-500 mt-1">วันที่
                        {{ \Carbon\Carbon::parse($document->updated_at)->format('d/m/Y') }}</p>
                @elseif ($document->status == 'rejected' && $document->approver_id == Auth::id())
                    <p class="mb-10 text-red-600 font-bold">✕ ปฏิเสธเอกสาร</p>
                    <p class="font-bold text-red-600">({{ $document->approver->name ?? '-' }})</p>
                @else
                    {{-- ยังรออนุมัติขั้นแรก --}}
                    <p class="mb-10 text-gray-400">ลงชื่อ..............................................ผู้อนุมัติ (1)
                    </p>
                    <p class="text-gray-400">
                        ({{ $document->approver->name ?? '..............................................' }})</p>
                @endif
            </div>

            {{-- 3. ลายเซ็นผู้อนุมัติคนที่ 2 (Director/CEO) --}}
            <div>
                @if ($document->approver_2_id)
                    {{-- กรณีมีผู้อนุมัติคนที่ 2 --}}
                    @if ($document->status == 'approved')
                        {{-- อนุมัติครบถ้วนแล้ว --}}
                        @if (
                            $document->approver2 &&
                                $document->approver2->signature &&
                                file_exists(public_path('uploads/signatures/' . $document->approver2->signature)))
                            <p class="mb-2 text-left ml-6">ลงชื่อ</p>
                            <img src="{{ asset('uploads/signatures/' . $document->approver2->signature) }}"
                                class="signature-img" alt="Signature">
                            <p class="border-t border-dotted border-gray-500 mx-6 pt-1 text-xs">ผู้อนุมัติ (2)</p>
                        @else
                            <p class="mb-10 text-green-600">✓ อนุมัติแล้ว (ไม่มีลายเซ็น)</p>
                        @endif
                        <p class="font-bold text-gray-900">({{ $document->approver2->name ?? '-' }})</p>
                        <p class="text-xs text-gray-500 mt-1">วันที่
                            {{ \Carbon\Carbon::parse($document->updated_at)->format('d/m/Y') }}</p>
                    @elseif ($document->status == 'rejected' && $document->approver_2_id == Auth::id())
                        <p class="mb-10 text-red-600 font-bold">✕ ปฏิเสธเอกสาร</p>
                        <p class="font-bold text-red-600">({{ $document->approver2->name ?? '-' }})</p>
                    @else
                        {{-- ยังรออนุมัติขั้นที่สอง (หรือขั้นแรกก็ยังไม่ผ่าน) --}}
                        <p class="mb-10 text-gray-400">ลงชื่อ..............................................ผู้อนุมัติ
                            (2)</p>
                        <p class="text-gray-400">
                            ({{ $document->approver2->name ?? '..............................................' }})</p>
                    @endif
                @else
                    {{-- กรณีเลือกให้จบการอนุมัติที่ 1 คน --}}
                    <p class="mb-10 text-gray-300">ไม่ต้องมีผู้อนุมัติคนที่ 2</p>
                    <p class="text-gray-300">-</p>
                @endif
            </div>

        </div>
    </div>

</body>

</html>
