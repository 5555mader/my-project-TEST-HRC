<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกข้อความภายในองค์กร</title>
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- นำเข้า Google Fonts: Sarabun --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <style>
        body,
        .page-a4,
        input,
        select,
        textarea,
        .ql-editor {
            font-family: 'Sarabun', sans-serif !important;
        }

        .ql-editor p {
            font-size: 16px !important;
            line-height: 1.8 !important;
        }

        .ql-editor {
            min-height: 120mm;
            line-height: 1.8;
        }

        /* ปรับแต่ง Select2 ให้เข้ากับโครงสร้างแบบฟอร์ม Tailwind */
        .select2-container .select2-selection--multiple,
        .select2-container .select2-selection--single {
            border: 0;
            border-bottom: 1px dotted #4b5563;
            border-radius: 0;
            min-height: 32px;
            background-color: transparent;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            color: #1f2937;
            line-height: 32px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 30px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border: 0;
            border-bottom: 1px dotted #2563eb;
        }
    </style>
</head>

<body class="bg-gray-100 py-10">

    <div class="max-w-4xl mx-auto mb-4 px-4 flex justify-between items-center">
        <button type="button" onclick="history.back()" class="text-sm text-blue-600 hover:underline">
            ← ย้อนกลับไปหน้าก่อนหน้า
        </button>
        <span class="text-xs text-gray-500">สร้างบันทึกข้อความใหม่ (Internal Memo Form)</span>
    </div>

    <div
        class="max-w-[210mm] min-h-[297mm] bg-white mx-auto p-[25mm_20mm_20mm_20mm] shadow-lg rounded-sm relative text-black">

        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold tracking-widest">บันทึกข้อความ</h2>
        </div>

        <form action="{{ route('admin.archives.store-memo') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ส่วนหัวบันทึกข้อความ --}}
            <div class="border-b-2 border-black pb-4 mb-6 text-lg space-y-5">

                {{-- 🌟 🌟 บรรทัดที่ 1: สาขา และ ส่วนงาน/แผนก 🌟 🌟 --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- สาขา --}}
                    <div class="flex items-end">
                        <span class="font-bold shrink-0 mr-3 mb-1">สาขา</span>
                        <select name="branch" id="branch_select" class="w-full" required>
                            <option value="">-- เลือก หรือ พิมพ์ชื่อสาขา --</option>
                            @php
                                $userBranch = Auth::user()->branch ?? '';
                                $branches = \App\Models\Branch::pluck('name')->toArray();
                            @endphp

                            @if ($userBranch && !in_array($userBranch, $branches))
                                <option value="{{ $userBranch }}" selected>{{ $userBranch }}</option>
                            @endif

                            @foreach (\App\Models\Branch::all() as $branch)
                                <option value="{{ $branch->name }}"
                                    {{ $userBranch == $branch->name ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ส่วนงาน/แผนก --}}
                    <div class="flex items-end">
                        <span class="font-bold shrink-0 mr-3 mb-1">ส่วนงาน/แผนก</span>
                        <select name="department" id="department_select" class="w-full" required>
                            <option value="">-- เลือก หรือ พิมพ์ชื่อแผนก --</option>
                            @php
                                $userDept = Auth::user()->department ?? '';
                                $departments = \App\Models\Department::pluck('name')->toArray();
                            @endphp

                            @if ($userDept && !in_array($userDept, $departments))
                                <option value="{{ $userDept }}" selected>{{ $userDept }}</option>
                            @endif

                            @foreach (\App\Models\Department::all() as $dept)
                                <option value="{{ $dept->name }}" {{ $userDept == $dept->name ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- ที่ และ วันที่ --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-end">
                        <span class="font-bold shrink-0 mr-3">ที่</span>
                        <input type="text" name="doc_number" value="{{ $nextDocNumber ?? 'อัตโนมัติ' }}"
                            class="w-full border-b border-dotted border-gray-600 focus:outline-none bg-transparent py-1 text-gray-500 cursor-not-allowed"
                            readonly required>
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
                            $currentDate = \Carbon\Carbon::now();
                            $thaiDate =
                                $currentDate->format('j') .
                                ' ' .
                                $thaiMonths[$currentDate->format('n') - 1] .
                                ' ' .
                                ($currentDate->format('Y') + 543);
                        @endphp
                        <div
                            class="w-full border-b border-dotted border-gray-600 bg-transparent py-1 text-center text-gray-800">
                            {{ $thaiDate }}
                        </div>
                    </div>
                </div>

                {{-- เรื่อง --}}
                <div class="flex flex-col space-y-2">
                    <div class="flex items-end">
                        <span class="font-bold shrink-0 mr-3">เรื่อง</span>
                        <select id="title_select"
                            class="w-full border-b border-dotted border-gray-600 focus:outline-none bg-transparent py-1 cursor-pointer text-gray-900"
                            required>
                            <option value="">-- กรุณาเลือกโครงการ/วัตถุประสงค์ --</option>
                            <optgroup label="จัดซื้อ / เบิกจ่าย / การเงิน">
                                <option value="ขอใบเสนอราคา (SO)">ขอใบเสนอราคา (SO)</option>
                                <option value="ขอ Quatation">ขอ Quatation</option>
                                <option value="ขอใบอนุมัติจัดซื้อ/จ้าง (PR)">ขอใบอนุมัติจัดซื้อ/จ้าง (PR)</option>
                                <option value="ขอใบสั่งซื้อ/จ้าง (PO)">ขอใบสั่งซื้อ/จ้าง (PO)</option>
                                <option value="ขอเบิกจ่าย">ขอเบิกจ่าย</option>
                                <option value="ขอเบิกเงินทดรองจ่าย">ขอเบิกเงินทดรองจ่าย</option>
                                <option value="ขอเบิกค่า Commission, Incentive">ขอเบิกค่า Commission, Incentive</option>
                                <option value="ขอคืนเงินประกันตามสัญญา">ขอคืนเงินประกันตามสัญญา</option>
                            </optgroup>
                            <optgroup label="นิติการ / สัญญา">
                                <option value="ขอทำสัญญาจ้าง/พัฒนา/ซื้อขาย">ขอทำสัญญาจ้าง/พัฒนา/ซื้อขาย</option>
                                <option value="ขอส่งมอบงานตามสัญญา">ขอส่งมอบงานตามสัญญา</option>
                                <option value="ขอหนังสือมอบอำนาจทั่วไป">ขอหนังสือมอบอำนาจทั่วไป</option>
                                <option value="ขอหนังสือมอบอำนาจที่มีภาระผูกพันบริษัท">
                                    ขอหนังสือมอบอำนาจที่มีภาระผูกพันบริษัท</option>
                            </optgroup>
                            <optgroup label="บุคคล / ธุรการ / สถานที่">
                                <option value="ขออัตรากำลังคน">ขออัตรากำลังคน</option>
                                <option value="ขอบุคลากรร่วมงาน">ขอบุคลากรร่วมงาน</option>
                                <option value="ขอฝึกอบรมพัฒนาบุคลากร">ขอฝึกอบรมพัฒนาบุคลากร</option>
                                <option value="ขอศึกษา/ดูงาน">ขอศึกษา/ดูงาน</option>
                                <option value="ขอซ่อมบำรุง/อาคาร/สถานที่">ขอซ่อมบำรุง/อาคาร/สถานที่</option>
                            </optgroup>
                            <optgroup label="ไอที / พัฒนาระบบ">
                                <option value="ขอ Project Code Name">ขอ Project Code Name</option>
                                <option value="ขอพัฒาระบบโปรแกรม">ขอพัฒาระบบโปรแกรม</option>
                                <option value="ขอเปิดระบบทดลองใช้งาน">ขอเปิดระบบทดลองใช้งาน</option>
                            </optgroup>
                            <optgroup label="ขออนุมัติจัดทำโครงการ">
                                <option value="ขอทำโครงการ ITI">ขอทำโครงการ ITI</option>
                                <option value="ขอทำโครงการ (การตลาด)">ขอทำโครงการ (การตลาด)</option>
                                <option value="ขอทำโครงการ (การเงิน)">ขอทำโครงการ (การเงิน)</option>
                                <option value="ขอทำโครงการ (บัญชี)">ขอทำโครงการ (บัญชี)</option>
                                <option value="ขอทำโครงการ (กฎหมาย)">ขอทำโครงการ (กฎหมาย)</option>
                                <option value="ขอทำโครงการ (จัดซื้อ)">ขอทำโครงการ (จัดซื้อ)</option>
                                <option value="ขอทำโครงการ (ธุรการ)">ขอทำโครงการ (ธุรการ)</option>
                                <option value="ขอทำโครงการ (ตรอ.)">ขอทำโครงการ (ตรอ.)</option>
                                <option value="ขอทำโครงการ (โรงเรียน)">ขอทำโครงการ (โรงเรียน)</option>
                            </optgroup>
                            <optgroup label="ตรวจสอบภายใน (Internal Audit)">
                                <option value="Internal Audit ฝ่ายมาตราฐาน">Internal Audit ฝ่ายมาตราฐาน</option>
                                <option value="Internal Audit ฝ่าย IDC (ITIและการตลาด)">Internal Audit ฝ่าย IDC
                                    (ITIและการตลาด)</option>
                                <option value="Internal Audit ฝ่าย AC (บัญชี)">Internal Audit ฝ่าย AC (บัญชี)</option>
                                <option value="Internal Audit ฝ่าย CD (ตรอ.)">Internal Audit ฝ่าย CD (ตรอ.)</option>
                                <option value="Internal Audit ฝ่าย IDD (โรงเรียน)">Internal Audit ฝ่าย IDD (โรงเรียน)
                                </option>
                            </optgroup>
                            <optgroup label="หนังสือแจ้ง / อื่นๆ">
                                <option value="ขอจดหมาย">ขอจดหมาย</option>
                                <option value="แจ้งเพื่อทราบ">แจ้งเพื่อทราบ</option>
                                <option value="แจ้งเพื่อทราบและดำเนินการด้วย">แจ้งเพื่อทราบและดำเนินการด้วย</option>
                                <option value="อื่นๆ">อื่นๆ (ระบุรายละเอียดเพิ่มเติม)</option>
                            </optgroup>
                        </select>
                        <input type="hidden" name="title" id="real_title">
                    </div>

                    <div id="custom_title_div" class="hidden flex items-end mt-2 pl-12">
                        <span class="text-sm font-semibold text-gray-500 shrink-0 mr-2">ระบุเรื่องอื่นๆ:</span>
                        <input type="text" id="custom_title_input"
                            class="w-full border-b border-dotted border-gray-400 focus:outline-none bg-transparent py-1 text-blue-600"
                            placeholder="พิมพ์ชื่อเรื่องที่นี่...">
                    </div>
                </div>

                {{-- เรียน --}}
                <div class="flex items-end">
                    <span class="font-bold shrink-0 mr-3 mb-1">เรียน</span>
                    <select name="to_position" id="to_position_select" class="w-full" required>
                        <option value="">-- เลือกผู้รับเรื่อง (ผู้จัดการ / ผอ.ฝ่าย / CEO) --</option>
                        @foreach (\App\Models\User::whereIn('role', ['Manager', 'HR Manager', 'Director', 'CEO'])->orderBy('role')->get() as $emp)
                            <option
                                value="{{ $emp->name }} ({{ $emp->role }} - {{ $emp->department ?? 'บริหาร' }})"
                                data-short-name="{{ $emp->name }}">
                                {{ $emp->name }} | ตำแหน่ง: {{ $emp->role }} | แผนก:
                                {{ $emp->department ?? 'บริหารส่วนกลาง' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- จำนวนเงิน/งบประมาณ (มี Checkbox) --}}
                <div class="flex items-center mt-3 mb-1">
                    <label class="flex items-center cursor-pointer font-bold text-gray-800 shrink-0 mr-4">
                        <input type="checkbox" id="has_amount_checkbox"
                            class="form-checkbox text-blue-600 h-5 w-5 mr-2 rounded border-gray-400 focus:ring-blue-500">
                        ระบุจำนวนเงิน/งบประมาณ (ถ้ามี)
                    </label>

                    <div id="amount_input_container" class="hidden flex items-end w-full md:w-1/2">
                        <input type="number" name="amount" id="amount_input" step="0.01" min="0"
                            class="w-full border-b border-dotted border-gray-600 focus:outline-none bg-transparent py-1 text-blue-700 font-semibold text-center"
                            placeholder="ระบุตัวเลข (เช่น 1500.50)">
                        <span class="font-bold shrink-0 ml-3">บาท</span>
                    </div>
                </div>

                {{-- สำเนาส่ง (CC) --}}
                <div class="flex items-start mt-4 text-base">
                    <span class="font-bold shrink-0 mr-3 mt-1">สำเนาส่ง (CC)</span>
                    <div class="w-full">
                        <select name="cc_users[]" id="cc_users" class="w-full" multiple="multiple">
                            @foreach (\App\Models\User::orderBy('name')->get() as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}
                                    ({{ $emp->department ?? 'ไม่ระบุ' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- สิ่งที่ส่งมาด้วย --}}
                <div class="flex items-center mt-2 text-base">
                    <span class="font-bold shrink-0 mr-3 text-gray-700">สิ่งที่ส่งมาด้วย</span>
                    <div class="w-full">
                        <input type="file" name="document_files[]" id="document_files" multiple
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png">
                        <small class="text-gray-400 block mt-1">* สามารถเลือกแนบได้หลายไฟล์พร้อมกัน</small>
                    </div>
                </div>

            </div>

            <hr class="border-gray-800 border-2 mt-4 mb-6">

            {{-- รูปแบบขั้นตอนการอนุมัติ --}}
            <div class="mb-4 bg-blue-50 p-3 rounded border border-blue-200 text-sm">
                <label class="block font-bold text-blue-900 mb-2"><i class="bi bi-diagram-3 me-1"></i>
                    รูปแบบขั้นตอนการอนุมัติ (Approval Workflow):</label>
                <div class="flex gap-6">
                    <label class="flex items-center cursor-pointer font-semibold text-gray-800">
                        <input type="radio" name="approval_steps_toggle" value="1"
                            class="form-radio text-blue-600 h-4 w-4 mr-2 focus:ring-blue-500">
                        <span class="text-gray-900">อนุมัติ 1 คน</span> <span
                            class="text-xs text-gray-500 font-normal ms-1">(สิ้นสุดงานที่หัวหน้างาน/HR
                            ตรงช่องผู้อนุมัติ 1)</span>
                    </label>
                    <label class="flex items-center cursor-pointer font-semibold text-gray-800">
                        <input type="radio" name="approval_steps_toggle" value="2" checked
                            class="form-radio text-blue-600 h-4 w-4 mr-2 focus:ring-blue-500">
                        <span class="text-gray-900">อนุมัติ 2 คน</span> <span
                            class="text-xs text-gray-500 font-normal ms-1">(หัวหน้างาน/HR ตรวจสอบ -> ส่งต่อ ผอ.ฝ่าย/CEO
                            ที่เลือกในช่องถัดไป)</span>
                    </label>
                </div>
                <input type="hidden" name="approval_steps" id="approval_steps" value="2">
            </div>

            {{-- โซนเลือกผู้อนุมัติ --}}
            <div
                class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded border border-gray-200 mb-6 text-sm">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">ผู้อนุมัติคนที่ 1 (Manager/HR) <span
                            class="text-red-500">*</span></label>
                    <select name="approver_id"
                        class="w-full p-2 border border-gray-300 rounded bg-white focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                        <option value="">-- เลือกผู้จัดการ/HR ตรวจสอบขั้นแรก --</option>
                        @foreach (\App\Models\User::whereIn('role', ['Super Admin', 'HR Manager', 'Manager'])->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                        @endforeach
                    </select>
                </div>

                <div id="approver_step_2_container">
                    <label class="block font-bold text-gray-700 mb-1" id="label_approver_2">
                        ผู้อนุมัติคนที่ 2 (Director/CEO) <span class="text-red-500" id="required_star_2">*</span>
                    </label>
                    <select name="approver_2_id" id="approver_2_id"
                        class="w-full p-2 border border-gray-300 rounded bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all"
                        required>
                        <option value="">-- เลือก ผอ.ฝ่าย / CEO อนุมัติขั้นที่สอง --</option>
                        @foreach (\App\Models\User::whereIn('role', ['Director', 'CEO', 'Super Admin'])->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- กล่องพิมพ์ข้อความอย่างละเอียด --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">เนื้อความบันทึกข้อความอย่างละเอียด:</label>
                <div id="editor" class="bg-white border border-gray-300 rounded-sm mt-4 mb-4"></div>

                <input type="hidden" name="content" id="content-input">

                {{-- โซนลายเซ็น --}}
                <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6 text-center text-sm text-gray-800">
                    <div>
                        <p class="mb-10">ลงชื่อ..............................................ผู้ขออนุมัติ</p>
                        <p class="font-bold">( {{ Auth::user()->name ?? 'ชื่อผู้ใช้งาน' }} )</p>
                        <p class="mt-1 font-medium text-gray-900">วันที่
                            {{ \Carbon\Carbon::now()->addYears(543)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="mb-10">ลงชื่อ..............................................ผู้อนุมัติ (1)</p>
                        <p>(..............................................)</p>
                        <p class="mt-1 font-medium text-gray-900">วันที่
                            {{ \Carbon\Carbon::now()->addYears(543)->format('d/m/Y') }}</p>
                    </div>
                    <div id="sig_block_approver_2" class="transition-all duration-300">
                        <p class="mb-10" id="sig_title_2">
                            ลงชื่อ..............................................ผู้อนุมัติ (2)</p>
                        <p id="sig_parenthesis_2">(..............................................)</p>
                        <p class="mt-1 font-medium text-gray-900" id="sig_date_2">วันที่
                            {{ \Carbon\Carbon::now()->addYears(543)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- ปุ่มกดส่งบันทึกข้อมูล --}}
            <div class="flex justify-end gap-3 border-t border-gray-200 pt-4">
                <button type="button" onclick="history.back()"
                    class="px-5 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 text-sm font-medium">
                    ยกเลิก
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium shadow-sm">
                    ส่งคำขออนุมัติเอกสาร
                </button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {

            // 🌟 1. ระบบจัดการช่อง "สาขา" และ "ส่วนงาน/แผนก" (พิมพ์อิสระได้) 🌟
            $('#branch_select').select2({
                placeholder: "-- เลือก หรือ พิมพ์ชื่อสาขา --",
                allowClear: true,
                width: '100%',
                tags: true
            });

            $('#department_select').select2({
                placeholder: "-- เลือก หรือ พิมพ์ชื่อแผนกเจ้าของเรื่อง --",
                allowClear: true,
                width: '100%',
                tags: true
            });

            // 2. ระบบจัดการช่อง "เรียน" 
            $('#to_position_select').select2({
                placeholder: "-- เลือกผู้รับเรื่อง (ผู้จัดการ / ผอ.ฝ่าย / CEO) --",
                allowClear: true,
                width: '100%',
                templateSelection: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    var shortName = $(data.element).data('short-name');
                    return shortName ? shortName : data.text;
                }
            });

            // 3. ระบบค้นหาและเลือกได้หลายคนในช่อง CC
            $('#cc_users').select2({
                placeholder: "-- เลือกผู้ที่ต้องการส่งสำเนา (CC) (เว้นว่างได้ถ้าไม่มี) --",
                allowClear: true,
                width: '100%'
            });

            // เปิดใช้งานระบบตัวพิมพ์ฟอร์ม Quill Editor
            var quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{
                            'header': [1, 2, 3, false]
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        [{
                            'align': []
                        }],
                        ['clean']
                    ]
                },
                placeholder: 'พิมพ์รายละเอียดเนื้อความของบันทึกข้อความภายในที่นี่...'
            });

            $('#has_amount_checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#amount_input_container').removeClass('hidden').addClass('flex');
                    $('#amount_input').attr('required', true);
                } else {
                    $('#amount_input_container').removeClass('flex').addClass('hidden');
                    $('#amount_input').removeAttr('required').val('');
                }
            });

            // ปรับปรุงลอจิกการสลับจำนวนผู้อนุมัติ
            $('input[name="approval_steps_toggle"]').on('change', function() {
                var steps = $(this).val();
                $('#approval_steps').val(steps);

                if (steps === '1') {
                    $('#label_approver_2').removeClass('text-gray-700').addClass(
                        'text-gray-400 line-through');
                    $('#approver_2_id').attr('disabled', true).removeAttr('required').val('').addClass(
                        'bg-gray-100 cursor-not-allowed opacity-50');
                    $('#required_star_2').hide();

                    $('#sig_block_approver_2').addClass('opacity-30 pointer-events-none select-none');
                    $('#sig_title_2').text('ไม่ต้องมีผู้อนุมัติคนที่ 2');
                    $('#sig_parenthesis_2').text('-');
                    $('#sig_date_2').hide();
                } else {
                    $('#label_approver_2').removeClass('text-gray-400 line-through').addClass(
                        'text-gray-700');
                    $('#approver_2_id').removeAttr('disabled').attr('required', true).removeClass(
                        'bg-gray-100 cursor-not-allowed opacity-50');
                    $('#required_star_2').show();

                    $('#sig_block_approver_2').removeClass('opacity-30 pointer-events-none select-none');
                    $('#sig_title_2').text(
                        'ลงชื่อ..............................................ผู้อนุมัติ (2)');
                    $('#sig_parenthesis_2').text('(..............................................)');
                    $('#sig_date_2').show();
                }
            });

            $('#title_select').on('change', function() {
                if ($(this).val() === 'อื่นๆ') {
                    $('#custom_title_div').removeClass('hidden');
                    $('#custom_title_input').attr('required', true);
                } else {
                    $('#custom_title_div').addClass('hidden');
                    $('#custom_title_input').removeAttr('required').val('');
                }
            });

            $('form').on('submit', function(e) {
                var selectedTitle = $('#title_select').val();
                if (selectedTitle === 'อื่นๆ') {
                    var customText = $('#custom_title_input').val().trim();
                    if (customText === '') {
                        alert('กรุณาระบุรายละเอียดหัวข้ออื่นๆ ด้วยครับ');
                        e.preventDefault();
                        return false;
                    }
                    $('#real_title').val('อื่นๆ (' + customText + ')');
                } else {
                    $('#real_title').val(selectedTitle);
                }

                var htmlContent = quill.root.innerHTML;
                if (htmlContent === '<p><br></p>' || htmlContent.trim() === '') {
                    alert('กรุณากรอกข้อความและรายละเอียดบันทึกภายในก่อนกดบันทึกครับ');
                    e.preventDefault();
                    return false;
                }
                $('#content-input').val(htmlContent);
            });
        });
    </script>
</body>

</html>
