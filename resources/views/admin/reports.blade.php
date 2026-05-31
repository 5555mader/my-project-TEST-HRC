@extends('layouts.ess')
@section('title', 'รายงานและสถิติ')

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

    <h1 class="h3 mb-4">HR Analytics (ข้อมูลเชิงลึก)</h1>

    <div class="row g-4 mb-4">
        {{-- กล่องที่ 1: จำนวนพนักงานทั้งหมด (ปรับจาก Turnover rate เพื่อให้ดึงข้อมูลง่ายขึ้น) --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 h-100 border-start border-primary border-4">
                <h6 class="text-muted fw-bold">จำนวนพนักงานทั้งหมด</h6>
                <h2 class="text-primary mb-0">{{ $totalEmployees ?? 0 }} คน</h2>
                <p class="small text-muted mt-2 mb-0"><i class="bi bi-people-fill text-primary"></i> พนักงานในระบบปัจจุบัน
                </p>
            </div>
        </div>

        {{-- กล่องที่ 2: ค่าใช้จ่ายบุคลากรรายเดือน --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 h-100 border-start border-success border-4">
                <h6 class="text-muted fw-bold">ค่าใช้จ่ายบุคลากรเดือนนี้</h6>
                <h2 class="text-dark mb-0">฿{{ number_format($totalExpenses ?? 0, 2) }}</h2>
                <p class="small text-muted mt-2 mb-0"><i class="bi bi-wallet2 text-success"></i> คำนวณจากสลิปที่ปล่อยแล้ว
                </p>
            </div>
        </div>

        {{-- กล่องที่ 3: อัตราการลาป่วยเฉลี่ย --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 h-100 border-start border-warning border-4">
                <h6 class="text-muted fw-bold">อัตราการลาป่วยเฉลี่ย (ปีนี้)</h6>
                <h2 class="text-warning mb-0">{{ $avgSickLeave ?? 0 }} วัน/คน</h2>
                <p class="small text-muted mt-2 mb-0"><i class="bi bi-thermometer-half text-warning"></i>
                    อ้างอิงจากคำขอลาป่วยที่อนุมัติ</p>
            </div>
        </div>
    </div>

    {{-- กล่องที่ 4: กราฟสถิติ Chart.js --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between mb-4">
                <h6 class="fw-bold"><i class="bi bi-bar-chart-line-fill me-2 text-primary"></i>กราฟสรุปการเข้างาน (7
                    วันล่าสุด)</h6>
            </div>

            {{-- พื้นที่แสดงกราฟ --}}
            <div style="height: 350px;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    {{-- นำเข้า Script ของ Chart.js จาก CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // รับค่าข้อมูลที่ถูกส่งมาจาก Controller (web.php)
            const labels = {!! json_encode($dates ?? []) !!};
            const presentData = {!! json_encode($presents ?? []) !!};
            const lateData = {!! json_encode($lates ?? []) !!};

            const ctx = document.getElementById('attendanceChart').getContext('2d');

            // วาดกราฟแท่ง
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'มาปกติ',
                            data: presentData,
                            backgroundColor: 'rgba(25, 135, 84, 0.7)', // สีเขียว
                            borderColor: 'rgba(25, 135, 84, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        {
                            label: 'มาสาย',
                            data: lateData,
                            backgroundColor: 'rgba(255, 193, 7, 0.7)', // สีเหลือง/ส้ม
                            borderColor: 'rgba(255, 193, 7, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1 // ให้แกน Y นับทีละ 1 (คน)
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        });
    </script>
@endsection
