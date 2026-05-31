@extends('layouts.ess')

@section('title', 'Benefit & Welfare')

@section('content')
    {{-- นำเข้า Google Fonts: Sarabun --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <style>
        /* บังคับให้หน้าจอ, ตาราง, ฟอร์มกรอกข้อมูล และการ์ดต่างๆ ใช้ฟอนต์ Sarabun ทั้งหมด */
        body, html, table, thead, tbody, tr, th, td, input, select, textarea, button, p, span, div, h1, h2, h3, h4, h5, h6, .card, .modal-content {
            font-family: 'Sarabun', sans-serif !important;
        }
    </style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">สวัสดิการและสิทธิประโยชน์</h1>
    <button class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> ยื่นเรื่องเบิกสวัสดิการ
    </button>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">สวัสดิการปัจจุบันของคุณ</h5>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item py-3">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light p-3 me-3">
                                <i class="bi bi-shield-check text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 font-semibold">ประกันสุขภาพกลุ่ม (IPD/OPD)</h6>
                                <small class="text-muted">ครอบคลุมการรักษาพยาบาลทั้งผู้ป่วยในและผู้ป่วยนอก</small>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary">ดูรายละเอียด</button>
                    </div>
                </div>
                <div class="list-group-item py-3">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light p-3 me-3">
                                <i class="bi bi-laptop text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 font-semibold">สวัสดิการซื้ออุปกรณ์ทำงาน (BYOD)</h6>
                                <small class="text-muted">งบประมาณสนับสนุนการซื้อโน้ตบุ๊กส่วนตัวเพื่อใช้ทำงาน</small>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary">ดูรายละเอียด</button>
                    </div>
                </div>
                <div class="list-group-item py-3">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-light p-3 me-3">
                                <i class="bi bi-gift text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 font-semibold">ของขวัญวันเกิดพนักงาน</h6>
                                <small class="text-muted">รับ Voucher พิเศษในเดือนเกิดของคุณ</small>
                            </div>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success">ได้รับแล้ว</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">ประวัติการขอเบิกสวัสดิการ</h5>
            </div>
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle">
                    <thead class="table-light small">
                        <tr>
                            <th>วันที่ยื่น</th>
                            <th>รายการ</th>
                            <th>จำนวนเงิน</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <tr>
                            <td>20/04/2026</td>
                            <td>ค่ารักษาพยาบาล (คลินิกความงาม)</td>
                            <td>฿1,200</td>
                            <td><span class="badge bg-success bg-opacity-10 text-success px-3">อนุมัติแล้ว</span></td>
                        </tr>
                        <tr>
                            <td>15/03/2026</td>
                            <td>ค่าตัดแว่นสายตา</td>
                            <td>฿3,000</td>
                            <td><span class="badge bg-success bg-opacity-10 text-success px-3">อนุมัติแล้ว</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4">
                <h5><i class="bi bi-telephone-outbound me-2"></i> สายด่วนประกันภัย</h5>
                <p class="small opacity-75">หากเกิดอุบัติเหตุหรือเหตุฉุกเฉิน สามารถติดต่อบริษัทประกันได้ 24 ชม.</p>
                <hr class="bg-white">
                <div class="fw-bold">โทร: 1234-567-890</div>
                <div class="small">เลขที่กรมธรรม์: GHP-2026-9999</div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6>ดาวน์โหลดเอกสาร</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="#" class="text-decoration-none small"><i class="bi bi-file-pdf text-danger me-2"></i>ฟอร์มการขอเบิกเงิน.pdf</a>
                    </li>
                    <li>
                        <a href="#" class="text-decoration-none small"><i class="bi bi-file-pdf text-danger me-2"></i>รายชื่อโรงพยาบาลคู่สัญญา.pdf</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection