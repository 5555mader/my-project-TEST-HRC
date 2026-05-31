@extends('layouts.ess')
@section('title', 'Leave Request')

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

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">ส่งคำขอแจ้งลา</h5>

            <form action="{{ route('ess.leave.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ประเภทการลา</label>
                        <select name="leave_type" class="form-select" required>
                            <option value="ลาป่วย">ลาป่วย</option>
                            <option value="ลากิจ">ลากิจ</option>
                            <option value="ลาพักร้อน">ลาพักร้อน</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">แนบหลักฐาน (ถ้ามี)</label>
                        <input type="file" name="attachment" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">วันที่เริ่ม</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ถึงวันที่</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">เหตุผลการลา</label>
                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">ส่งคำขอ</button>
            </form>

        </div>
    </div>

    <div class="mt-4">
        <h6>ประวัติการลา</h6>
        <div class="table-responsive">
            <table class="table bg-white shadow-sm rounded align-middle">
                <thead class="table-light">
                    <tr>
                        <th>วันที่ยื่นคำขอ</th>
                        <th>ประเภท</th>
                        <th>ช่วงวันที่ลา</th>
                        <th>เหตุผล</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- วนลูปแสดงข้อมูลประวัติการลาจากตัวแปร $history --}}
                    @forelse($history as $leave)
                        <tr>
                            {{-- วันที่กดส่งคำขอ (อิงจาก created_at ของตาราง) --}}
                            <td class="small text-muted">{{ $leave->created_at->format('d/m/Y H:i') }}</td>

                            {{-- ประเภทการลา --}}
                            <td><span class="fw-bold">{{ $leave->leave_type }}</span></td>

                            {{-- วันที่เริ่มต้น ถึง วันที่สิ้นสุด --}}
                            <td class="small">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }} <br>
                                <span class="text-muted">ถึง</span>
                                {{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}
                            </td>

                            {{-- เหตุผล (ใช้ text-truncate ตัดข้อความถ้ายาวเกินไป) --}}
                            <td class="small text-truncate" style="max-width: 150px;" title="{{ $leave->reason }}">
                                {{ $leave->reason }}
                            </td>

                            {{-- สถานะ (ตรวจสอบคำและใส่สี Badge ให้เหมาะสม) --}}
                            <td>
                                @if ($leave->status == 'pending')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>
                                        รออนุมัติ</span>
                                @elseif($leave->status == 'approved')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>
                                        อนุมัติแล้ว</span>
                                @elseif($leave->status == 'rejected')
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> ปฏิเสธ</span>
                                @else
                                    <span class="badge bg-secondary">{{ $leave->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        {{-- กรณีที่พนักงานคนนี้ยังไม่เคยลาเลย --}}
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-folder2-open fs-2 d-block mb-2 text-light"></i>
                                ยังไม่มีประวัติการส่งคำขอลา
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
