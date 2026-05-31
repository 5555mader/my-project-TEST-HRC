@extends('layouts.ess')
@section('title', 'Payslip')

@section('content')
    {{-- นำเข้า Google Fonts: Sarabun --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">

    {{-- 1. CSS สำหรับควบคุมการพิมพ์และจำลองหน้าจอให้สวยงาม --}}
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

        .payslip-box {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 2rem;
        }

        /* --- โหมดสั่งพิมพ์ (Print to PDF) ดึงเฉพาะเนื้อหาสลิปออกมา --- */
        @media print {
            body * {
                visibility: hidden;
            }

            #printArea,
            #printArea * {
                visibility: visible;
            }

            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3"><strong><i
                    class="bi bi-receipt me-2 text-primary"></i>ประวัติสลิปเงินเดือน</strong></div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">เดือน/ปี</th>
                        <th>รายรับสุทธิ</th>
                        <th class="text-end pe-4">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payslips as $slip)
                        <tr>
                            <td class="fw-bold ps-4">{{ $slip->month }}</td>
                            <td class="text-success fw-bold">฿{{ number_format($slip->net_total, 2) }}</td>
                            <td class="text-end pe-4">
                                {{-- แก้ไขปุ่ม "ดู" ให้ส่งค่าผ่าน Data Attributes เหมือนหน้า archives.blade.php --}}
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                    data-bs-toggle="modal" data-bs-target="#payslipModal" data-month="{{ $slip->month }}"
                                    data-name="{{ Auth::user()->name }}"
                                    data-emp-id="EMP{{ str_pad(Auth::user()->id, 3, '0', STR_PAD_LEFT) }}"
                                    data-department="{{ Auth::user()->department ?? 'พนักงานบริษัท' }}"
                                    data-base="{{ number_format($slip->base_salary ?? 30000, 2) }}"
                                    data-bonus="{{ number_format($slip->bonus ?? 0, 2) }}"
                                    data-deduction="{{ number_format($slip->deduction ?? 0, 2) }}"
                                    data-tax="{{ number_format($slip->tax ?? 1500, 2) }}"
                                    data-net="{{ number_format($slip->net_total, 2) }}">
                                    <i class="bi bi-eye-fill me-1"></i> ดูสลิป
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-light"></i>
                                ยังไม่มีสลิปเงินเดือนที่ถูกปล่อยในขณะนี้
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 2. Modal แสดงสลิปเงินเดือนรูปแบบ HTML ภาษาไทย (แทนที่ Iframe เดิม) --}}
    <div class="modal fade" id="payslipModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-light no-print">
                    <h5 class="modal-title fw-bold text-dark"><i
                            class="bi bi-file-earmark-text me-2 text-primary"></i>ตรวจสอบสลิปเงินเดือน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-gray-50">

                    {{-- พื้นที่โครงสร้างสลิปเงินเดือน HTML ภาษาไทยสำหรับเปิดดูและสั่งพิมพ์ --}}
                    <div id="printArea" class="payslip-box bg-white shadow-sm">
                        <div class="text-center mb-4 border-bottom pb-3">
                            <h3 class="fw-bold text-dark mb-1" style="letter-spacing: 1px;">ใบแจ้งยอดเงินเดือน (Payslip)
                            </h3>
                            <p class="text-muted mb-0">ประจำเดือน: <span id="modal-month"
                                    class="fw-bold text-primary"></span></p>
                        </div>

                        {{-- ข้อมูลพนักงาน --}}
                        <div class="row g-3 mb-4 bg-light p-3 rounded text-sm">
                            <div class="col-6">
                                <span class="text-muted d-block">ชื่อ-นามสกุลพนักงาน:</span>
                                <strong id="modal-name" class="text-dark"></strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block">รหัสพนักงาน / แผนก:</span>
                                <strong class="text-dark"><span id="modal-emp-id"></span> — [<span
                                        id="modal-dept"></span>]</strong>
                            </div>
                        </div>

                        {{-- ตารางรายรับ - รายการหัก --}}
                        <div class="row g-4 mb-4">
                            {{-- ฝั่งรายได้ --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success border-b border-2 pb-2 mb-2"><i
                                        class="bi bi-plus-circle-fill me-1"></i> รายรับ (Earnings)</h6>
                                <div class="d-flex justify-content-between py-1 border-bottom border-dashed text-sm">
                                    <span class="text-secondary">เงินเดือนพื้นฐาน</span>
                                    <span class="fw-bold">฿<span id="modal-base"></span></span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom border-dashed text-sm">
                                    <span class="text-secondary">เบี้ยเลี้ยง / โบนัส / อื่นๆ</span>
                                    <span class="fw-bold text-success">+ ฿<span id="modal-bonus"></span></span>
                                </div>
                            </div>

                            {{-- ฝั่งรายการหัก --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold text-danger border-b border-2 pb-2 mb-2"><i
                                        class="bi bi-dash-circle-fill me-1"></i> รายการหัก (Deductions)</h6>
                                <div class="d-flex justify-content-between py-1 border-bottom border-dashed text-sm">
                                    <span class="text-secondary">หักมาสาย / ขาดงาน</span>
                                    <span class="fw-bold text-danger">- ฿<span id="modal-deduction"></span></span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom border-dashed text-sm">
                                    <span class="text-secondary">ภาษีหัก ณ ที่จ่าย & ประกันสังคม</span>
                                    <span class="fw-bold text-danger">- ฿<span id="modal-tax"></span></span>
                                </div>
                            </div>
                        </div>

                        {{-- สรุปยอดเงินสุทธิ --}}
                        <div class="border-top pt-3 bg-light p-3 rounded d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark text-uppercase">รายรับสุทธิ (Net Pay)</span>
                            <h4 class="mb-0 fw-bold text-primary">฿<span id="modal-net"></span> บาท</h4>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 bg-light no-print">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill"
                        data-bs-dismiss="modal">ปิดหน้าต่าง</button>
                    <button type="button" class="btn btn-primary px-4 rounded-pill shadow-sm" onclick="window.print()">
                        <i class="bi bi-printer-fill me-1"></i> สั่งพิมพ์ / เซฟ PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. JavaScript สำหรับดึงข้อมูลจากปุ่มมากระจายลงใน Modal เหมือนหน้า Archives --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payslipModal = document.getElementById('payslipModal');
            if (payslipModal) {
                payslipModal.addEventListener('show.bs.modal', function(event) {
                    // ปุ่มที่ถูกคลิกดู
                    const button = event.relatedTarget;

                    // ดึงค่า attributes ออกจากปุ่มและส่งไปแปะใน Element ต่างๆ ของหน้า Modal
                    payslipModal.querySelector('#modal-month').textContent = button.getAttribute(
                        'data-month');
                    payslipModal.querySelector('#modal-name').textContent = button.getAttribute(
                        'data-name');
                    payslipModal.querySelector('#modal-emp-id').textContent = button.getAttribute(
                        'data-emp-id');
                    payslipModal.querySelector('#modal-dept').textContent = button.getAttribute(
                        'data-department');
                    payslipModal.querySelector('#modal-base').textContent = button.getAttribute(
                        'data-base');
                    payslipModal.querySelector('#modal-bonus').textContent = button.getAttribute(
                        'data-bonus');
                    payslipModal.querySelector('#modal-deduction').textContent = button.getAttribute(
                        'data-deduction');
                    payslipModal.querySelector('#modal-tax').textContent = button.getAttribute('data-tax');
                    payslipModal.querySelector('#modal-net').textContent = button.getAttribute('data-net');
                });
            }
        });
    </script>
@endsection
