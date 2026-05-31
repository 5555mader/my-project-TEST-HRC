@extends('layouts.ess')
@section('title', 'ภาพรวมทีม')

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

    <h1 class="h3 mb-4">Team Overview (วันนี้: {{ \Carbon\Carbon::now()->format('d/m/Y') }})</h1>

    {{-- ส่วนสรุปจำนวนพนักงาน --}}
    <div class="row g-3 mb-4 text-center">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 bg-primary text-white">
                <h6 class="opacity-75">สมาชิกทั้งหมด</h6>
                <h3>{{ $stats['total'] }} คน</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 bg-success text-white">
                <h6 class="opacity-75">มาทำงาน</h6>
                <h3>{{ $stats['present'] }} คน</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 bg-warning text-dark">
                <h6 class="opacity-75">มาสาย</h6>
                <h3>{{ $stats['late'] }} คน</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 bg-danger text-white">
                <h6 class="opacity-75">ลา/หยุด</h6>
                <h3>{{ $stats['leave'] }} คน</h3>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">สถานะพนักงานรายบุคคล</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ชื่อ-นามสกุล</th>
                            <th>เวลาเข้างาน</th>
                            <th>สถานะ</th>
                            <th>ข้อมูลเพิ่มเติม</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $emp)
                            @php
                                $attendance = $todayAttendances->get($emp->id);
                                $leave = $todayLeaves->get($emp->id);
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $emp->name }}</div>
                                    <small class="text-muted">{{ $emp->department }}</small>
                                </td>
                                <td>
                                    {{ $attendance ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') . ' น.' : '-' }}
                                </td>
                                <td>
                                    @if ($leave)
                                        <span class="badge bg-danger">ลา ({{ $leave->leave_type }})</span>
                                    @elseif($attendance)
                                        @if ($attendance->status == 'มาสาย')
                                            <span class="badge bg-warning text-dark">สาย</span>
                                        @else
                                            <span class="badge bg-success">ปกติ</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">ยังไม่เข้างาน</span>
                                    @endif
                                </td>
                                <td>
                                    <small>
                                        @if ($attendance)
                                            <i class="bi bi-geo-alt text-primary"></i> บันทึกแล้ว
                                        @elseif($leave)
                                            <i class="bi bi-calendar-event text-danger"></i> อนุมัติลาแล้ว
                                        @else
                                            <i class="bi bi-clock-history"></i> รอลงเวลา
                                        @endif
                                    </small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- เพิ่มแท็ก script ไว้ล่างสุดตรงนี้เลยครับ --}}
    <script>
        // แอบดึงข้อมูลใหม่มาแปะทับตารางเดิมทุกๆ 10 วินาที
        setInterval(function() {
            fetch('{{ route('manager.team') }}') // ใช้ route ของ Laravel แทนการพิมพ์ URL ตรงๆ จะชัวร์กว่าครับ
                .then(response => response.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, "text/html");

                    // ค้นหา tbody ตัวใหม่ที่ดึงมา แล้วเอามาแทนที่ tbody ตัวเก่าในหน้าเว็บ
                    let newTbody = doc.querySelector('tbody');
                    if (newTbody) {
                        document.querySelector('tbody').innerHTML = newTbody.innerHTML;
                    }
                })
                .catch(error => console.error('Error fetching team data:', error)); // ดักจับเผื่อมี error
        }, 10000); // 10000 มิลลิวินาที = 10 วินาที
    </script>

@endsection
