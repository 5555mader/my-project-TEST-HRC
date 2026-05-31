@extends('layouts.ess')
@section('title', 'จัดการเงินเดือน')

@section('content')
    {{-- นำเข้าและตั้งค่าฟอนต์ Sarabun --}}
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

        /* ปรับขนาดฟอนต์มาตรฐานให้พอดีกับการอ่านภาษาไทยบนหน้าเว็บ */
        body {
            font-size: 15px;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">รอบการจ่ายเงินเดือน (Payroll Processing)</h1>
        <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-calendar3 me-2"></i> พฤษภาคม 2026
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">เมษายน 2026</a></li>
                <li><a class="dropdown-item" href="#">มีนาคม 2026</a></li>
            </ul>
        </div>
    </div>

    {{-- สรุปภาพรวมค่าใช้จ่ายของเดือนนี้ --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 bg-white h-100 border-start border-primary border-4">
                <h6 class="text-muted small fw-bold">ยอดจ่ายสุทธิรวม (Net Pay)</h6>
                <h3 class="mb-0 text-dark">฿145,250.00</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 bg-white h-100 border-start border-success border-4">
                <h6 class="text-muted small fw-bold">รายได้รวม (Total Earnings)</h6>
                <h4 class="mb-0 text-success">฿160,000.00</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 bg-white h-100 border-start border-danger border-4">
                <h6 class="text-muted small fw-bold">รายการหักรวม (Total Deductions)</h6>
                <h4 class="mb-0 text-danger">฿14,750.00</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 bg-white h-100 border-start border-warning border-4">
                <h6 class="text-muted small fw-bold">สถานะการทำรายการ</h6>
                <h4 class="mb-0 text-warning">รอตรวจสอบ</h4>
            </div>
        </div>
    </div>

    <div class="col-md-9 mb-3">
        <form action="{{ route('admin.payroll') }}" method="GET" class="d-flex align-items-center">
            <div class="input-group input-group-sm w-50">
                <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อพนักงาน หรือ แผนก..."
                    value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> ค้นหา</button>
                @if (request('search'))
                    <a href="{{ route('admin.payroll') }}" class="btn btn-outline-danger">ล้าง</a>
                @endif
            </div>
        </form>
    </div>

    <form action="{{ route('admin.payroll.release') }}" method="POST" id="payrollForm" class="row g-4">
        @csrf

        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">รายการเงินเดือนพนักงาน</h6>
                </div>

                @if (session('success'))
                    <div class="alert alert-success m-3 py-2 small"><i class="bi bi-check-circle me-1"></i>
                        {{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger m-3 py-2 small"><i class="bi bi-exclamation-triangle me-1"></i>
                        {{ $errors->first() }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-muted">
                            <tr>
                                <th class="ps-3 text-center" style="width: 50px;">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </th>
                                <th>รหัส/พนักงาน</th>
                                <th class="text-end">เงินเดือนพื้นฐาน</th>
                                <th class="text-end text-success">OT / โบนัส (+)</th>
                                <th class="text-end text-danger">หักมาสาย/ขาด (-)</th>
                                <th class="text-end text-danger">ภาษี/ปสล. (-)</th>
                                <th class="text-end pe-3">รับสุทธิ (Net)</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            {{-- วนลูปแสดงข้อมูลพนักงานพร้อมยอดหักที่คำนวณจริง --}}
                            @forelse($employees as $employee)
                                <tr>
                                    <td class="ps-3 text-center">
                                        <input class="form-check-input employee-checkbox" type="checkbox"
                                            name="employee_ids[]" value="{{ $employee->id }}">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $employee->name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            EMP{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }} |
                                            {{ $employee->department ?? 'ไม่ระบุแผนก' }}
                                        </div>
                                    </td>
                                    <td class="text-end">30,000.00</td>
                                    <td class="text-end text-success">0.00</td>

                                    {{-- แสดงยอดหักที่คำนวณได้จริงจากสถานะการมาสาย/ขาด --}}
                                    <td class="text-end text-danger">
                                        {{ number_format($employee->total_deduction, 2) }}
                                        @if ($employee->late_count > 0)
                                            <div class="text-muted" style="font-size: 0.65rem;">(สาย
                                                {{ $employee->late_count }} ครั้ง)</div>
                                        @endif
                                    </td>

                                    <td class="text-end text-danger">1,500.00</td>

                                    {{-- คำนวณยอดรับสุทธิเบื้องต้น --}}
                                    <td class="text-end pe-3 fw-bold">
                                        {{ number_format(30000 - $employee->total_deduction - 1500, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-person-x fs-1 text-muted d-block mb-2"></i>
                                        ไม่พบข้อมูลพนักงานในระบบ
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-light sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-gear-fill me-2 text-primary"></i>ดำเนินการ (Actions)</h6>
                    <div class="d-grid gap-3">
                        <button type="button" class="btn btn-primary shadow-sm text-start"
                            onclick="submitFormAction('calculate')">
                            <i class="bi bi-calculator me-2"></i>1. ประมวลผลเงินเดือน
                            <div class="small fw-normal opacity-75 mt-1" style="font-size: 0.7rem;">
                                คำนวณเงินคนที่เลือกใหม่</div>
                        </button>
                        <button type="button" class="btn btn-outline-dark text-start bg-white shadow-sm"
                            onclick="submitFormAction('export')">
                            <i class="bi bi-file-earmark-excel me-2 text-success"></i>2. Export Bank File
                            <div class="small fw-normal text-muted mt-1" style="font-size: 0.7rem;">โหลดไฟล์ CSV
                                คนที่เลือก</div>
                        </button>
                        <hr class="my-1">
                        <button type="button" class="btn btn-success shadow-sm text-start"
                            onclick="submitFormAction('release')">
                            <i class="bi bi-send-check me-2"></i>3. อนุมัติและปล่อยสลิป
                            <div class="small fw-normal opacity-75 mt-1" style="font-size: 0.7rem;">
                                ส่งสลิปให้คนที่ถูกเลือกเท่านั้น</div>
                        </button>
                        <input type="hidden" name="action_type" id="actionType" value="">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');

            selectAllCheckbox.addEventListener('change', function() {
                employeeCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });

            employeeCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(employeeCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                });
            });
        });

        function submitFormAction(action) {
            const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('กรุณาเลือกพนักงานอย่างน้อย 1 คนก่อนดำเนินการครับ');
                return;
            }
            let confirmMessage = '';
            if (action === 'calculate') confirmMessage = 'ยืนยันการประมวลผลเงินเดือนใหม่ให้พนักงานที่เลือก?';
            if (action === 'export') confirmMessage = 'ยืนยันการดาวน์โหลด Bank File สำหรับพนักงานที่เลือก?';
            if (action === 'release') confirmMessage =
                'ยืนยันการปล่อยสลิปเงินเดือนให้พนักงานที่เลือก? (พนักงานจะมองเห็นสลิปทันที)';
            if (confirm(confirmMessage)) {
                document.getElementById('actionType').value = action;
                document.getElementById('payrollForm').submit();
            }
        }
    </script>
@endsection
