@extends('layouts.ess')
@section('title', 'ประเมินผลงาน')

@section('content')
    {{-- นำเข้า Google Fonts: Sarabun --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">

    <style>
        /* บังคับให้หน้าจอ, ตาราง, ฟอร์มกรอกข้อมูล และการ์ดต่างๆ ใช้ฟอนต์ Sarabun ทั้งหมด */
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
        .modal-content {
            font-family: 'Sarabun', sans-serif !important;
        }
    </style>

    <h1 class="h3 mb-4">Performance Review (Q2/2026)</h1>

    {{-- ส่วนเลือกพนักงาน --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <label class="form-label fw-bold">เลือกพนักงานที่ต้องการประเมิน</label>
            <form action="{{ route('manager.review') }}" method="GET" id="employeeSelectForm">
                <select name="user_id" class="form-select"
                    onchange="document.getElementById('employeeSelectForm').submit()">
                    <option value="">-- กรุณาเลือกพนักงาน --</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('user_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }} ({{ $emp->department }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @if ($selectedEmployee)
        {{-- แสดงฟอร์มประเมินเมื่อเลือกพนักงานแล้ว --}}
        <div class="card border-0 shadow-sm p-4">
            <div class="row align-items-center mb-4">
                <div class="col-auto">
                    {{-- ใช้ UI Avatars แสดงรูปตามชื่อ --}}
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($selectedEmployee->name) }}&background=random"
                        class="rounded-circle" width="60">
                </div>
                <div class="col">
                    <h5 class="mb-0">{{ $selectedEmployee->name }}</h5>
                    <small class="text-muted">ตำแหน่ง: {{ $selectedEmployee->department }} | อีเมล:
                        {{ $selectedEmployee->email }}</small>
                </div>
            </div>

            <form action="#" method="POST">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $selectedEmployee->id }}">

                <div class="mb-4">
                    <h6>1. คุณภาพงาน (Quality of Work)</h6>
                    <input type="range" class="form-range" min="1" max="5" step="1"
                        name="quality_score">
                    <div class="d-flex justify-content-between small text-muted">
                        <span>ควรปรับปรุง (1)</span>
                        <span>ดีมาก (5)</span>
                    </div>
                </div>

                <div class="mb-4">
                    <h6>2. การตรงต่อเวลา (Punctuality)</h6>
                    <select class="form-select" name="punctuality_score">
                        <option value="5">ดีมาก (ไม่เคยสาย)</option>
                        <option value="4">ดี (สาย 1-2 ครั้ง)</option>
                        <option value="3">พอใช้</option>
                        <option value="2">ต้องปรับปรุง</option>
                    </select>
                </div>

                <div class="mb-4">
                    <h6>ความคิดเห็นเพิ่มเติม</h6>
                    <textarea class="form-control" name="comments" rows="3" placeholder="ระบุข้อดีหรือสิ่งที่ควรพัฒนา..."></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-5">บันทึกผลการประเมินของ
                        {{ $selectedEmployee->name }}</button>
                </div>
            </form>
        </div>
    @else
        {{-- แสดงข้อความเมื่อยังไม่ได้เลือกพนักงาน --}}
        <div class="card border-0 shadow-sm p-5 text-center">
            <i class="bi bi-person-check fs-1 text-light"></i>
            <p class="text-muted mt-3">กรุณาเลือกรายชื่อพนักงานจากรายการด้านบน เพื่อเริ่มทำการประเมิน</p>
        </div>
    @endif

@endsection
