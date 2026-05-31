@extends('layouts.ess')
@section('title', 'คลังบันทึกภายใน')

@section('content')
    {{-- นำเข้า Google Fonts: Sarabun --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">

    {{-- นำเข้า Quill CSS เวอร์ชัน 2.0.2 --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

    <style>
        /* เพิ่มให้มีผลกับทั้งหน้าจอรวมถึงตารางและ Modal */
        body,
        table,
        .modal-content,
        .print-a4-paper {
            font-family: 'Sarabun', sans-serif !important;
        }

        .preview-a4-container {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
        }

        .print-a4-paper {
            background: white;
            width: 100%;
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 25mm 20mm 20mm 20mm;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            box-sizing: border-box;
            position: relative;
            color: #000000;
        }

        /* เพิ่มคลาส hidden เพื่อรองรับกรณีที่ไม่มี Tailwind โหลดในหน้านี้ */
        .hidden {
            display: none !important;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            .sidebar,
            .navbar,
            .d-print-none,
            .card,
            .alert,
            .modal-header,
            .modal-footer,
            .modal-backdrop {
                display: none !important;
                visibility: hidden !important;
            }

            html,
            body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
                overflow: hidden !important;
            }

            main,
            .content-wrapper,
            .container-fluid,
            .modal,
            .modal-dialog,
            .modal-content,
            .modal-body {
                display: block !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
                background: #ffffff !important;
                transform: none !important;
            }

            .print-a4-paper {
                display: block !important;
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
                padding: 20mm 20mm 15mm 20mm !important;
                margin: 0 !important;
                box-shadow: none !important;
                background: #ffffff !important;
                box-sizing: border-box !important;
                page-break-inside: avoid !important;
                page-break-after: avoid !important;
            }

            #modal-a4-content {
                min-height: auto !important;
                max-height: 160mm !important;
                overflow: hidden !important;
                line-height: 1.6 !important;
            }
        }

        /* เอฟเฟกต์เบา ๆ เวลาเอาเมาส์ไปวางที่ตัวเลือกไฟล์ */
        .hover-scale-sm:hover {
            transform: translateY(-1px);
            background-color: #f0f3f9 !important;
            border-color: #b4c2e0 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">คลังบันทึกภายใน (Internal Memo Archives)</h1>
            <p class="text-muted small mb-0 d-print-none">จัดการและเรียกดูประวัติบันทึกข้อความภายในทั้งหมดของคุณ</p>
        </div>
        <div class="d-flex gap-2 d-print-none">
            <a href="{{ url('/document-form') }}" class="btn btn-primary shadow-sm px-4">
                <i class="bi bi-pencil-square me-2"></i>เขียนบันทึกข้อความใหม่
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ========================================== --}}
    {{-- 🔍 ส่วนตัวกรองข้อมูล (Filter) --}}
    {{-- ========================================== --}}
    <div class="card border-0 shadow-sm mb-4 d-print-none">
        <div class="card-body">
            <form action="{{ route('admin.archives') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0"
                            placeholder="ค้นหาเลขที่เอกสาร หรือ หัวข้อเรื่อง..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select text-secondary">
                        <option value="">-- ทุกสถานะเอกสาร --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รออนุมัติ</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>อนุมัติแล้ว
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>ไม่อนุมัติ</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">ค้นหา</button>
                </div>
                @if (request('search') || request('status'))
                    <div class="col-md-2">
                        <a href="{{ route('admin.archives') }}" class="btn btn-link text-danger text-decoration-none px-0">
                            <i class="bi bi-x-circle me-1"></i>ล้างการค้นหา
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-archive me-2 text-primary"></i>คลังบันทึกข้อความภายใน</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-muted text-uppercase">
                    <tr>
                        <th class="ps-4">เลขที่ / เรื่อง</th>
                        {{-- 🌟 จุดที่ 1: เพิ่มคอลัมน์วันที่สร้าง 🌟 --}}
                        <th>วันที่สร้าง</th>
                        <th>ผู้ส่งเอกสาร</th>
                        <th>เรียน (ส่งถึง)</th>
                        <th>ผู้อนุมัติ / สถานะ</th>
                        <th class="text-center pe-4" style="width: 150px;">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                        <tr>
                            <td class="ps-4">
                                <span class="d-block text-muted small fw-mono">{{ $doc->doc_number }}</span>
                                <span class="fw-bold text-dark">{{ $doc->title }}</span>
                            </td>
                            {{-- 🌟 จุดที่ 1: แสดงข้อมูลวันที่พร้อมเวลา 🌟 --}}
                            <td class="text-muted small">
                                {{ \Carbon\Carbon::parse($doc->created_at)->locale('th')->addYears(543)->translatedFormat('j F Y เวลา H:i น.') }}
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $doc->user->name ?? 'ไม่ระบุผู้ส่ง' }}</div>
                                <small class="text-muted">{{ $doc->department ?? 'ไม่ระบุแผนก' }}</small>
                            </td>
                            <td>
                                <span class="text-secondary font-medium">{{ $doc->to_position }}</span>
                            </td>
                            <td>
                                <div class="mb-2">
                                    <small class="text-muted d-block"><i class="bi bi-person me-1"></i>อ.1:
                                        {{ $doc->approver->name ?? 'ไม่ระบุ' }}</small>
                                    <small class="text-muted d-block"><i class="bi bi-person me-1"></i>อ.2:
                                        {{ $doc->approver2->name ?? 'ไม่ระบุ' }}</small>
                                </div>

                                @if ($doc->status == 'pending')
                                    <span class="badge bg-warning text-dark px-2 py-1"><i
                                            class="bi bi-hourglass-split me-1"></i> รอดำเนินการ</span>
                                @elseif($doc->status == 'approved')
                                    <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle me-1"></i>
                                        อนุมัติแล้ว</span>
                                @elseif($doc->status == 'rejected')
                                    <span class="badge bg-danger px-2 py-1"><i class="bi bi-x-circle me-1"></i>
                                        ไม่อนุมัติ</span>
                                @else
                                    <span class="badge bg-secondary px-2 py-1">{{ $doc->status }}</span>
                                @endif
                            </td>

                            <td class="text-center pe-4 align-middle">
                                @php
                                    $statusTh = 'รอดำเนินการ';
                                    $statusColor = 'text-warning';
                                    if ($doc->status == 'approved') {
                                        $statusTh = 'อนุมัติแล้ว';
                                        $statusColor = 'text-success';
                                    } elseif ($doc->status == 'rejected') {
                                        $statusTh = 'ไม่อนุมัติ';
                                        $statusColor = 'text-danger';
                                    }

                                    $ccNames = '- ไม่มีสำเนา -';
                                    $ccIdsArray = [];
                                    if (!empty($doc->cc_users) && is_array($doc->cc_users)) {
                                        $ccUsers = \App\Models\User::whereIn('id', $doc->cc_users)->get();
                                        if ($ccUsers->count() > 0) {
                                            $ccFormatted = [];
                                            foreach ($ccUsers as $ccUser) {
                                                $ccFormatted[] =
                                                    $ccUser->name . ' (' . ($ccUser->department ?? '') . ')';
                                                $ccIdsArray[] = $ccUser->id;
                                            }
                                            $ccNames = implode(', ', $ccFormatted);
                                        }
                                    }
                                    $senderWithDept =
                                        ($doc->user->name ?? 'ไม่ระบุผู้ส่ง') .
                                        ' (' .
                                        ($doc->department ?? 'ไม่ระบุแผนก') .
                                        ')';
                                @endphp

                                <div class="d-flex flex-column justify-content-center gap-2 my-2">
                                    {{-- 🌟 จุดที่ 2: อัปเดต data-date ปุ่มพิมพ์ / A4 🌟 --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded w-100"
                                        data-bs-toggle="modal" data-bs-target="#viewDocModal"
                                        data-title="{{ $doc->title }}" data-number="{{ $doc->doc_number }}"
                                        data-branch="{{ $doc->branch ?? '-' }}" data-dept="{{ $doc->department }}"
                                        data-to="{{ $doc->to_position }}" data-amount="{{ $doc->amount ?? '' }}"
                                        data-content="{{ $doc->content }}"
                                        data-sender="{{ $doc->user->name ?? 'ไม่ระบุ' }}"
                                        data-sender-sig="{{ $doc->user->signature ?? '' }}"
                                        data-date="{{ \Carbon\Carbon::parse($doc->created_at)->locale('th')->addYears(543)->translatedFormat('j F Y เวลา H:i น.') }}"
                                        data-files="{{ $doc->files->toJson() }}" data-cc="{{ $ccNames }}"
                                        data-approver1-name="{{ $doc->approver->name ?? '-' }}"
                                        data-approver1-sig="{{ $doc->approver->signature ?? '' }}"
                                        data-approver2-name="{{ $doc->approver2->name ?? '-' }}"
                                        data-approver2-sig="{{ $doc->approver2->signature ?? '' }}"
                                        data-status="{{ $doc->status }}">
                                        <i class="bi bi-file-earmark-pdf"></i> พิมพ์ / A4
                                    </button>

                                    {{-- ปุ่มแก้ไข --}}
                                    @if (Auth::id() == $doc->user_id || Auth::user()->role == 'Super Admin')
                                        @if ($doc->status == 'pending')
                                            <button type="button" class="btn btn-sm btn-warning shadow-sm px-2 w-100"
                                                data-bs-toggle="modal" data-bs-target="#editMemoModal"
                                                data-id="{{ $doc->id }}" data-title="{{ $doc->title }}"
                                                data-branch="{{ $doc->branch ?? '' }}"
                                                data-dept="{{ $doc->department ?? '' }}"
                                                data-to="{{ $doc->to_position ?? '' }}"
                                                data-amount="{{ $doc->amount ?? '' }}"
                                                data-content="{{ $doc->content }}"
                                                data-cc-ids="{{ json_encode($doc->cc_users ?? []) }}">
                                                <i class="bi bi-pencil-square"></i> แก้ไข
                                            </button>
                                        @endif
                                    @endif

                                    {{-- 🌟 จุดที่ 2: อัปเดต data-date ปุ่มรายละเอียด 🌟 --}}
                                    <button type="button" class="btn btn-sm btn-outline-info rounded w-100"
                                        data-bs-toggle="modal" data-bs-target="#detailDocModal"
                                        data-title="{{ $doc->title }}" data-number="{{ $doc->doc_number }}"
                                        data-dept="{{ $doc->department }}" data-to="{{ $doc->to_position }}"
                                        data-content="{{ $doc->content }}" data-sender="{{ $senderWithDept }}"
                                        data-approver="{{ $doc->approver->name ?? 'ไม่ระบุ' }}"
                                        data-approver2="{{ $doc->approver2->name ?? 'ไม่ระบุ' }}"
                                        data-status="{{ $statusTh }}" data-statuscolor="{{ $statusColor }}"
                                        data-cc="{{ $ccNames }}"
                                        data-rejectreason="{{ $doc->reject_reason ?? '-' }}"
                                        data-files="{{ $doc->files->toJson() }}"
                                        data-date="{{ \Carbon\Carbon::parse($doc->created_at)->locale('th')->addYears(543)->translatedFormat('j F Y เวลา H:i น.') }}">
                                        <i class="bi bi-card-text"></i> รายละเอียด
                                    </button>

                                    {{-- ปุ่มดูไฟล์แนบเฉพาะ (ถ้ามีไฟล์) --}}
                                    @if ($doc->files && $doc->files->count() > 0)
                                        <button type="button" class="btn btn-sm btn-info text-white rounded w-100"
                                            data-bs-toggle="modal" data-bs-target="#attachmentPopupModal"
                                            data-title="{{ $doc->title }}" data-files="{{ $doc->files->toJson() }}">
                                            <i class="bi bi-paperclip"></i> ไฟล์แนบ ({{ $doc->files->count() }})
                                        </button>
                                    @endif

                                    {{-- ปุ่มลบ --}}
                                    @if (Auth::id() == $doc->user_id || Auth::user()->role == 'Super Admin')
                                        @if ($doc->status == 'pending')
                                            <form action="{{ route('admin.archives.destroy', $doc->id) }}" method="POST"
                                                class="m-0 w-100"
                                                onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบเอกสารบันทึกข้อความเลขที่ {{ $doc->doc_number }}? \n(หมายเหตุ: เอกสารนี้จะถูกลบออกจากระบบอย่างถาวร)')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded w-100">
                                                    <i class="bi bi-trash"></i> ลบ
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">ไม่พบบันทึกข้อความในคลัง</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL 1: รายละเอียดบันทึก (Detail Doc Modal) --}}
    {{-- ========================================== --}}
    <div class="modal fade" id="detailDocModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-info bg-opacity-10">
                    <h5 class="modal-title fw-bold text-info"><i
                            class="bi bi-info-circle-fill me-2"></i>รายละเอียดบันทึกข้อความ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">เลขที่หนังสือ:</small>
                            <span class="fw-bold" id="detail-number"></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">ส่วนงาน/แผนก:</small>
                            <span class="fw-bold" id="detail-dept"></span>
                        </div>
                        <div class="col-md-12">
                            <small class="text-muted d-block">เรื่อง:</small>
                            <span class="fw-bold text-primary" id="detail-title"></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">เรียน (ผู้รับ):</small>
                            <span class="fw-bold" id="detail-to"></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">ผู้ขออนุมัติ:</small>
                            <span class="fw-bold" id="detail-sender"></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">สถานะ:</small>
                            <span class="fw-bold" id="detail-status"></span>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block text-primary">ผู้อนุมัติคนที่ 1:</small>
                            <span class="fw-bold" id="detail-approver"></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block text-primary">ผู้อนุมัติคนที่ 2:</small>
                            <span class="fw-bold" id="detail-approver-2"></span>
                        </div>

                        <div class="col-md-12">
                            <small class="text-muted d-block">ผู้รับสำเนา (CC):</small>
                            <span class="text-secondary" id="detail-cc"></span>
                        </div>

                        {{-- กล่องแสดงเหตุผลเมื่อถูกปฏิเสธ --}}
                        <div class="col-md-12 d-none mt-2" id="detail-reject-container">
                            <div class="alert alert-danger mb-0 py-2 border-danger border-opacity-50">
                                <small class="text-danger fw-bold d-block"><i
                                        class="bi bi-exclamation-triangle-fill me-1"></i>เหตุผลที่ไม่อนุมัติ:</small>
                                <span class="text-dark" id="detail-reject-reason" style="white-space: pre-wrap;"></span>
                            </div>
                        </div>
                    </div>

                    {{-- ปรับโครงสร้าง HTML ให้รองรับรูปแบบ Quill --}}
                    <div class="border-top pt-3 mt-2 mb-3">
                        <label class="form-label fw-bold text-muted small d-block mb-2">รายละเอียดบันทึกภายใน</label>
                        <div class="ql-container ql-snow border rounded bg-light p-2">
                            <div id="modal-detail-content" class="ql-editor text-dark"
                                style="font-size: 16px; line-height: 1.8; min-height: 150px; max-height: 400px; overflow-y: auto;">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 border-top pt-3">
                        <small class="text-muted d-block fw-bold mb-2"><i class="bi bi-paperclip"></i>
                            เอกสาร/ไฟล์แนบ:</small>
                        <div id="modal-attachments" class="d-flex flex-wrap gap-2">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL 2: พรีวิวรูปแบบเต็มหน้ากระดาษ A4 HTML + พิมพ์ PDF --}}
    {{-- ========================================== --}}
    <div class="modal fade" id="viewDocModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white border-0 d-print-none">
                    <h5 class="modal-title fw-bold" id="modal-title"><i
                            class="bi bi-file-earmark-text me-2"></i>ตรวจสอบเอกสาร</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body preview-a4-container">
                    <div class="print-a4-paper position-relative" style="min-height: 297mm;">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold m-0 text-dark" style="font-size: 26px; letter-spacing: 2px;">บันทึกข้อความ
                            </h2>
                        </div>

                        {{-- ส่วนหัวบันทึกข้อความ --}}
                        <div class="border-bottom border-dark pb-3 mb-4"
                            style="border-width: 2px !important; font-size: 16px;">
                            <div class="row g-3 mb-2">
                                <div class="col-6 d-flex align-items-baseline">
                                    <span class="fw-bold me-2 text-nowrap">สาขา:</span>
                                    <div id="modal-a4-branch"></div>
                                </div>
                                <div class="col-6 d-flex align-items-baseline">
                                    <span class="fw-bold me-2 text-nowrap">ส่วนงาน/แผนก:</span>
                                    <div id="modal-a4-dept"></div>
                                </div>
                            </div>

                            <div class="row g-3 mb-2">
                                <div class="col-6 d-flex align-items-baseline">
                                    <span class="fw-bold me-2 text-nowrap">ที่:</span>
                                    <div id="modal-a4-number"></div>
                                </div>
                                <div class="col-6 d-flex align-items-baseline">
                                    <span class="fw-bold me-2 text-nowrap">วันที่:</span>
                                    <div id="modal-a4-date"></div>
                                </div>
                            </div>

                            <div class="d-flex align-items-baseline mb-2">
                                <span class="fw-bold me-2 text-nowrap">เรื่อง:</span>
                                <div id="modal-a4-title"></div>
                            </div>

                            <div class="d-flex align-items-baseline mb-2">
                                <span class="fw-bold me-2 text-nowrap">เรียน:</span>
                                <div id="modal-a4-to"></div>
                            </div>

                            <div class="d-flex align-items-baseline mb-2" id="modal-a4-amount-container"
                                style="display: none !important;">
                                <span class="fw-bold me-2 text-nowrap">จำนวนเงิน/งบประมาณ:</span>
                                <div class="fw-bold text-primary" id="modal-a4-amount"></div>
                                <span class="fw-bold ms-2 text-nowrap">บาท</span>
                            </div>

                            <div class="d-flex align-items-start mt-2">
                                <span class="fw-bold me-2 text-nowrap">สำเนาส่ง (CC):</span>
                                <div class="w-100 small mt-1" id="modal-a4-cc"></div>
                            </div>

                            <div class="d-flex align-items-start mt-3 d-print-none">
                                <span class="fw-bold me-2 text-nowrap text-secondary"><i class="bi bi-paperclip"></i>
                                    สิ่งที่ส่งมาด้วย:</span>
                                <div class="w-100 small" id="file-attachments-container"></div>
                                <div id="no-file-message" class="text-muted fst-italic small mt-1">
                                    ไม่มีไฟล์แนบสำหรับเอกสารฉบับนี้</div>
                            </div>
                        </div>

                        {{-- เนื้อหาเอกสาร --}}
                        <div id="modal-a4-content" class="my-4 text-dark"
                            style="font-size: 16px; line-height: 1.8; white-space: pre-wrap; min-height: 120mm;"></div>

                        {{-- โซนลายเซ็นล็อกตำแหน่งด้านล่างสุดของ A4 --}}
                        <div class="position-absolute w-100 row text-center small text-dark"
                            style="bottom: 20mm; left: 0; padding: 0 15mm; margin: 0;">
                            <div class="col-4 px-2">
                                <div id="sig-sender-container" style="height: 60px;"
                                    class="d-flex align-items-end justify-content-center mb-1"></div>
                                <p class="mb-0 fw-bold">( <span id="modal-sig-sender"></span> )</p>
                                <p class="text-muted small mt-1">วันที่ <span class="modal-sig-date"></span></p>
                            </div>

                            <div class="col-4 px-2">
                                <div id="sig-approver1-container" style="height: 60px;"
                                    class="d-flex align-items-end justify-content-center mb-1"></div>
                                <p class="mb-0 fw-bold">( <span id="modal-sig-approver1"></span> )</p>
                                <p class="text-muted small mt-1">วันที่ <span class="modal-sig-date"></span></p>
                            </div>

                            <div class="col-4 px-2">
                                <div id="sig-approver2-container" style="height: 60px;"
                                    class="d-flex align-items-end justify-content-center mb-1"></div>
                                <p class="mb-0 fw-bold">( <span id="modal-sig-approver2"></span> )</p>
                                <p class="text-muted small mt-1">วันที่ <span class="modal-sig-date"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
                    <button type="button" onclick="window.print()"
                        class="bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2 px-4 rounded shadow d-print-none">พิมพ์เอกสาร
                        (Save as PDF)</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL 3: แก้ไขบันทึกข้อความ (Edit Memo Modal) --}}
    {{-- ========================================== --}}
    <div class="modal fade" id="editMemoModal" tabindex="-1" aria-labelledby="editMemoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold" id="editMemoModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>แก้ไขบันทึกข้อความภายใน
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="editMemoForm" method="POST" action="">
                    @csrf
                    @method('PATCH')

                    <div class="modal-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">สาขา</label>
                                <input type="text" name="branch" id="edit_branch" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ส่วนงาน/แผนก</label>
                                <input type="text" name="department" id="edit_dept" class="form-control" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">เรื่อง</label>
                                <select id="edit_title_select" class="form-select" required>
                                    <option value="ขออนุมัติจัดซื้อ/จัดจ้าง">ขออนุมัติจัดซื้อ/จัดจ้าง</option>
                                    <option value="ขออนุมัติเบิกค่าใช้จ่าย">ขออนุมัติเบิกค่าใช้จ่าย</option>
                                    <option value="ขออนุมัติหลักการ">ขออนุมัติหลักการ</option>
                                    <option value="แจ้งเพื่อทราบ">แจ้งเพื่อทราบ</option>
                                    <option value="อื่นๆ">อื่นๆ (กรุณาระบุ)</option>
                                </select>
                                <input type="hidden" name="title" id="edit_real_title">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">เรียน (ตำแหน่ง)</label>
                                <input type="text" name="to_position" id="edit_to" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3 d-none" id="edit_custom_title_div">
                            <label class="form-label fw-bold text-danger">ระบุหัวข้อเรื่องเพิ่มเติม</label>
                            <input type="text" id="edit_custom_title_input" class="form-control"
                                placeholder="กรุณาพิมพ์หัวข้อเรื่องที่ต้องการระบุ">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">จำนวนเงิน/งบประมาณ (บาท) <span
                                    class="text-muted small fw-normal">(ระบุเฉพาะกรณีที่มีงบประมาณเกี่ยวข้อง)</span></label>
                            <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control"
                                placeholder="0.00">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">สำเนาส่ง (CC ถึงพนักงานท่านอื่น)</label>
                            <select name="cc_users[]" id="edit_cc_users" class="form-select w-100 select2-edit-cc"
                                multiple="multiple">
                                @foreach (\App\Models\User::where('id', '!=', Auth::id())->get() as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}
                                        ({{ $employee->department }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">รายละเอียดบันทึกภายใน</label>
                            <div id="edit_quill_editor" style="height: 250px; background: #fff;" class="border rounded">
                            </div>
                            <input type="hidden" name="content" id="edit_content_input">
                        </div>
                    </div>

                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-light px-3 border" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-warning fw-bold px-4 shadow-sm text-dark">
                            <i class="bi bi-check-circle me-1"></i> บันทึกการแก้ไข
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL 4: Popup แสดงไฟล์แนบโดยเฉพาะ --}}
    {{-- ========================================== --}}
    <div class="modal fade" id="attachmentPopupModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-light border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold text-dark"><i
                            class="bi bi-folder-symlink-fill text-info me-2 fs-4"></i> ไฟล์แนบของเอกสาร</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3" id="popup-project-title" style="font-size: 13px; font-weight: 500;">
                    </p>

                    {{-- กล่องรับรายการไฟล์ --}}
                    <div id="popup-files-container" class="d-flex flex-column gap-2"></div>

                    <div id="popup-no-file" class="text-center text-muted d-none">
                        <small>ไม่มีไฟล์แนบ</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4 rounded-bottom-4">
                    <button type="button" class="btn btn-secondary btn-sm px-4 rounded-pill"
                        data-bs-dismiss="modal">ปิดหน้าต่าง</button>
                </div>
            </div>
        </div>
    </div>

    {{-- นำเข้าสคริปต์ Quill.js 2.0.2 ก่อนเปิดแท็ก script หลัก --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ---------------------------------------------------------
            // ฟังก์ชันสำหรับหน้าต่าง Modal ดูรายละเอียด (View Details)
            // ---------------------------------------------------------
            const detailDocModal = document.getElementById('detailDocModal');
            if (detailDocModal) {
                detailDocModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    detailDocModal.querySelector('#detail-title').textContent = button.getAttribute(
                        'data-title');
                    detailDocModal.querySelector('#detail-number').textContent = button.getAttribute(
                        'data-number');
                    detailDocModal.querySelector('#detail-dept').textContent = button.getAttribute(
                        'data-dept');
                    detailDocModal.querySelector('#detail-to').textContent = button.getAttribute('data-to');
                    detailDocModal.querySelector('#detail-sender').textContent = button.getAttribute(
                        'data-sender');
                    detailDocModal.querySelector('#detail-approver').textContent = button.getAttribute(
                        'data-approver');
                    detailDocModal.querySelector('#detail-approver-2').textContent = button.getAttribute(
                        'data-approver2');

                    // ดึงข้อความรายละเอียดที่เป็น HTML แล้วแปะลงในกล่อง Quill แบบ Read-only
                    const contentData = button.getAttribute('data-content');
                    const detailContainer = detailDocModal.querySelector('#modal-detail-content');
                    if (detailContainer) {
                        detailContainer.innerHTML = contentData ? contentData :
                            '<p class="text-muted fst-italic">ไม่มีรายละเอียดข้อความ</p>';
                    }

                    detailDocModal.querySelector('#detail-cc').textContent = button.getAttribute('data-cc');

                    // อัปเดตสถานะ
                    const statusText = button.getAttribute('data-status');
                    const statusEl = detailDocModal.querySelector('#detail-status');
                    statusEl.textContent = statusText;
                    statusEl.className = 'fw-bold ' + button.getAttribute('data-statuscolor');

                    // ตรวจสอบและแสดงเหตุผลการปฏิเสธ
                    const rejectContainer = detailDocModal.querySelector('#detail-reject-container');
                    const rejectReasonText = button.getAttribute('data-rejectreason');

                    if (statusText === 'ไม่อนุมัติ') {
                        rejectContainer.classList.remove('d-none'); // แสดงกล่องสีแดง
                        detailDocModal.querySelector('#detail-reject-reason').textContent =
                            rejectReasonText !== '-' && rejectReasonText !== '' ? rejectReasonText :
                            'ไม่มีการระบุเหตุผล';
                    } else {
                        rejectContainer.classList.add('d-none'); // ซ่อนกล่องสีแดง
                    }

                    const attachmentsContainer = detailDocModal.querySelector('#modal-attachments');
                    if (attachmentsContainer) {
                        attachmentsContainer.innerHTML = '';
                        const filesData = button.getAttribute('data-files');
                        let hasFiles = false;

                        if (filesData && filesData !== '[]') {
                            try {
                                const files = JSON.parse(filesData);
                                if (files.length > 0) {
                                    hasFiles = true;
                                    files.forEach((file) => {
                                        const fileUrl = window.location.origin +
                                            '/uploads/documents/' + file.file_name;
                                        attachmentsContainer.innerHTML += `
                                            <div class="d-flex align-items-center justify-content-between p-2 bg-white border rounded shadow-sm mb-1" style="width: 100%; max-width: 360px;">
                                                <span class="text-dark text-truncate small fw-bold me-2" style="max-width: 180px;">
                                                    <i class="bi bi-file-earmark-text text-secondary me-1"></i>${file.file_name}
                                                </span>
                                                <div class="d-flex gap-1">
                                                    <a href="${fileUrl}" target="_blank" class="btn btn-xs btn-primary text-white py-1 px-2" style="font-size: 11px;" title="เปิดดูไฟล์">
                                                        <i class="bi bi-eye-fill"></i> ดู
                                                    </a>
                                                    <a href="${fileUrl}" download="${file.file_name}" class="btn btn-xs btn-success text-white py-1 px-2" style="font-size: 11px;" title="บันทึกเข้าเครื่อง">
                                                        <i class="bi bi-download"></i> โหลด
                                                    </a>
                                                </div>
                                            </div>`;
                                    });
                                }
                            } catch (e) {
                                console.error("Error parsing files JSON", e);
                            }
                        }

                        if (!hasFiles) {
                            attachmentsContainer.innerHTML =
                                '<span class="text-muted small">ไม่มีไฟล์แนบสำหรับเอกสารฉบับนี้</span>';
                        }
                    }
                });
            }

            // ---------------------------------------------------------
            // ฟังก์ชันสำหรับหน้ากระดาษ A4 (View Modal) และระบบไฟล์แนบ
            // ---------------------------------------------------------
            const viewDocModal = document.getElementById('viewDocModal');
            if (viewDocModal) {
                viewDocModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    viewDocModal.querySelector('#modal-a4-title').textContent = button.getAttribute(
                        'data-title');
                    viewDocModal.querySelector('#modal-a4-content').innerHTML = button.getAttribute(
                        'data-content');
                    viewDocModal.querySelector('#modal-a4-number').textContent = button.getAttribute(
                        'data-number');
                    viewDocModal.querySelector('#modal-a4-dept').textContent = button.getAttribute(
                        'data-dept');
                    viewDocModal.querySelector('#modal-a4-branch').textContent = button.getAttribute(
                        'data-branch');
                    viewDocModal.querySelector('#modal-a4-to').textContent = button.getAttribute('data-to');

                    const ccData = button.getAttribute('data-cc');
                    viewDocModal.querySelector('#modal-a4-cc').textContent = ccData && ccData !==
                        '- ไม่มีสำเนา -' ? ccData : 'ไม่มีสำเนาส่ง';

                    const docDate = button.getAttribute('data-date');
                    viewDocModal.querySelector('#modal-a4-date').textContent = docDate;
                    document.querySelectorAll('.modal-sig-date').forEach(el => el.textContent = docDate);

                    const amount = button.getAttribute('data-amount');
                    const amountContainer = viewDocModal.querySelector('#modal-a4-amount-container');
                    if (amount && parseFloat(amount) > 0) {
                        amountContainer.style.setProperty('display', 'flex', 'important');
                        viewDocModal.querySelector('#modal-a4-amount').textContent = parseFloat(amount)
                            .toLocaleString('en-US', {
                                minimumFractionDigits: 2
                            });
                    } else {
                        amountContainer.style.setProperty('display', 'none', 'important');
                    }

                    const status = button.getAttribute('data-status');
                    const basePath = window.location.origin + '/uploads/signatures/';
                    const sender = button.getAttribute('data-sender');
                    const senderSig = button.getAttribute('data-sender-sig');
                    const app1Name = button.getAttribute('data-approver1-name');
                    const app1Sig = button.getAttribute('data-approver1-sig');
                    const app2Name = button.getAttribute('data-approver2-name');
                    const app2Sig = button.getAttribute('data-approver2-sig');

                    viewDocModal.querySelector('#modal-sig-sender').textContent = sender;
                    viewDocModal.querySelector('#modal-sig-approver1').textContent = app1Name !== '-' ?
                        app1Name : '..............................................';
                    viewDocModal.querySelector('#modal-sig-approver2').textContent = (app2Name &&
                        app2Name !== '-') ? app2Name : '..............................................';

                    const renderSignature = (containerId, sigFile, defaultText, isApproved) => {
                        const container = viewDocModal.querySelector('#' + containerId);
                        if (sigFile && isApproved) {
                            container.innerHTML =
                                `<div class="d-flex flex-column align-items-center"><span class="small text-start w-100 ms-5 mb-1">ลงชื่อ</span><img src="${basePath + sigFile}" style="max-height: 40px; object-fit: contain; margin-bottom: -5px;"></div>`;
                        } else if (isApproved && !sigFile) {
                            container.innerHTML =
                                `<span class="text-success fw-bold">✓ อนุมัติแล้ว (ไม่มีลายเซ็น)</span>`;
                        } else if (status === 'rejected') {
                            container.innerHTML =
                                `<span class="text-danger fw-bold">✕ ปฏิเสธเอกสาร</span>`;
                        } else {
                            container.innerHTML = `<span class="text-muted">${defaultText}</span>`;
                        }
                    };

                    renderSignature('sig-sender-container', senderSig,
                        'ลงชื่อ..............................................ผู้ขออนุมัติ', true);

                    const app1Approved = (status === 'approved' || status === 'pending_step_2');
                    renderSignature('sig-approver1-container', app1Sig,
                        'ลงชื่อ..............................................ผู้อนุมัติ (1)',
                        app1Approved);

                    if (app2Name && app2Name !== '-') {
                        renderSignature('sig-approver2-container', app2Sig,
                            'ลงชื่อ..............................................ผู้อนุมัติ (2)', (
                                status === 'approved'));
                    } else {
                        viewDocModal.querySelector('#sig-approver2-container').innerHTML =
                            `<span class="text-muted">ไม่ต้องมีผู้อนุมัติคนที่ 2</span>`;
                        viewDocModal.querySelector('#modal-sig-approver2').textContent = '-';
                    }

                    const filesData = button.getAttribute('data-files');
                    const fileContainer = document.getElementById('file-attachments-container');
                    const noFileMsg = document.getElementById('no-file-message');

                    if (fileContainer) fileContainer.innerHTML = '';

                    if (filesData && filesData !== '[]') {
                        try {
                            const files = JSON.parse(filesData);
                            if (files.length > 0) {
                                if (noFileMsg) noFileMsg.classList.add('d-none');
                                files.forEach(file => {
                                    const fileUrl = window.location.origin + '/uploads/documents/' +
                                        file.file_name;
                                    const fileHtml = `
                                        <div class="d-flex align-items-center justify-content-between w-100 p-2 bg-light border rounded mb-1" style="max-width: 400px;">
                                            <span class="text-dark fw-bold text-truncate small" style="max-width: 250px;"><i class="bi bi-paperclip"></i> ${file.file_name}</span>
                                            <div class="d-flex gap-1">
                                                <a href="${fileUrl}" target="_blank" class="btn btn-xs btn-primary py-1 px-2 text-white" style="font-size: 11px;">ดู</a>
                                                <a href="${fileUrl}" download="${file.file_name}" class="btn btn-xs btn-success py-1 px-2 text-white" style="font-size: 11px;">โหลด</a>
                                            </div>
                                        </div>`;
                                    if (fileContainer) fileContainer.insertAdjacentHTML('beforeend',
                                        fileHtml);
                                });
                            } else {
                                if (noFileMsg) noFileMsg.classList.remove('d-none');
                            }
                        } catch (e) {
                            if (noFileMsg) noFileMsg.classList.remove('d-none');
                        }
                    } else {
                        if (noFileMsg) noFileMsg.classList.remove('d-none');
                    }
                });
            }

            // เพิ่ม Event Listener สำหรับ Modal ไฟล์แนบแบบ Popup
            const attachmentPopupModal = document.getElementById('attachmentPopupModal');
            if (attachmentPopupModal) {
                attachmentPopupModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const projectTitle = button.getAttribute('data-title');
                    const filesData = button.getAttribute('data-files');

                    document.getElementById('popup-project-title').innerHTML =
                        `<span class="fw-bold text-dark">โครงการ:</span> ${projectTitle}`;
                    const container = document.getElementById('popup-files-container');
                    const noFile = document.getElementById('popup-no-file');

                    container.innerHTML = '';
                    if (filesData && filesData !== '[]') {
                        try {
                            const files = JSON.parse(filesData);
                            if (files.length > 0) {
                                noFile.classList.add('d-none');
                                files.forEach(file => {
                                    const fileUrl = window.location.origin + '/uploads/documents/' +
                                        file.file_name;
                                    container.innerHTML += `
                                        <div class="d-flex align-items-center justify-content-between w-100 p-3 bg-light border rounded-3 mb-2" style="border-color: #e3e6f0 !important;">
                                            <div class="d-flex align-items-center overflow-hidden me-2">
                                                <div class="p-2.5 bg-info text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; min-width: 42px;">
                                                    <i class="bi bi-file-earmark-text fs-4"></i>
                                                </div>
                                                <div class="text-start overflow-hidden ms-3" style="line-height: 1.3;">
                                                    <p class="text-dark font-bold fw-bold mb-1 text-truncate" style="max-width: 180px; font-size: 13px;">${file.file_name}</p>
                                                    <small class="text-muted" style="font-size: 11px;">เลือกดำเนินการไฟล์</small>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-1.5 shrink-0">
                                                <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-primary text-white d-flex align-items-center gap-1 shadow-sm px-2.5" title="กดเพื่อเปิดดูพรีวิวรูปภาพหรือไฟล์">
                                                    <i class="bi bi-eye-fill"></i> ดูไฟล์
                                                </a>
                                                <a href="${fileUrl}" download="${file.file_name}" class="btn btn-sm btn-success text-white d-flex align-items-center gap-1 shadow-sm px-2.5" title="กดเพื่อบันทึกไฟล์ดาวน์โหลดลงเครื่อง">
                                                    <i class="bi bi-download"></i> โหลด
                                                </a>
                                            </div>
                                        </div>`;
                                });
                            } else {
                                noFile.classList.remove('d-none');
                            }
                        } catch (e) {
                            noFile.classList.remove('d-none');
                        }
                    } else {
                        noFile.classList.remove('d-none');
                    }
                });
            }

            // ---------------------------------------------------------
            // ฟังก์ชันสำหรับปรับปรุงข้อมูลในหน้าต่างแก้ไข (Edit Memo Modal)
            // ---------------------------------------------------------

            // 1. เปิดการทำงานของ Quill Editor สำหรับกล่องฟอร์มแก้ไขข้อมูล
            var editQuill = new Quill('#edit_quill_editor', {
                theme: 'snow',
                placeholder: 'กรอกรายละเอียดหรือข้อความเนื้อหาในบันทึกภายในที่นี่...',
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
                        ['clean']
                    ]
                }
            });

            // 2. ตั้งค่าเริ่มต้นให้ Select2 ภายใน Modal
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2-edit-cc').select2({
                    dropdownParent: $('#editMemoModal'),
                    placeholder: "เลือกรายชื่อพนักงานที่ต้องการสำเนาส่ง",
                    allowClear: true
                });
            }

            // 3. Logic สลับการแสดงผลกล่องข้อความเมื่อเลือกประเภทเรื่อง "อื่นๆ"
            $('#edit_title_select').on('change', function() {
                if ($(this).val() === 'อื่นๆ') {
                    $('#edit_custom_title_div').removeClass('d-none');
                    $('#edit_custom_title_input').attr('required', true);
                } else {
                    $('#edit_custom_title_div').addClass('d-none');
                    $('#edit_custom_title_input').removeAttr('required').val('');
                }
            });

            // 4. ดักจับเหตุการณ์และเติมข้อมูลเดิมลงในฟิลด์เมื่อกดปุ่มเปิด Modal แก้ไข
            const editMemoModal = document.getElementById('editMemoModal');
            if (editMemoModal) {
                editMemoModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    // ดึงค่าพื้นฐานจากคุณลักษณะของปุ่ม
                    const id = button.getAttribute('data-id');
                    const titleData = button.getAttribute('data-title');
                    const branch = button.getAttribute('data-branch');
                    const dept = button.getAttribute('data-dept');
                    const toPosition = button.getAttribute('data-to');
                    const amount = button.getAttribute('data-amount');
                    const contentHtml = button.getAttribute('data-content');
                    const ccIds = button.getAttribute('data-cc-ids');

                    // ผูกค่า Action URL ไปยังระบบตรวจสอบเส้นทาง (ปรับเปลี่ยนให้ตรงตาม web.php ของคุณ)
                    document.getElementById('editMemoForm').setAttribute('action', `/admin/archives/${id}`);

                    // เติมข้อมูลพื้นฐานลงในฟอร์ม
                    document.getElementById('edit_branch').value = branch || '';
                    document.getElementById('edit_dept').value = dept || '';
                    document.getElementById('edit_to').value = toPosition || '';
                    document.getElementById('edit_amount').value = amount || '';

                    // เติมข้อความ HTML ลงใน Quill Editor
                    editQuill.root.innerHTML = contentHtml ? contentHtml : '';

                    // จัดการคัดแยกประเภทหัวข้อเรื่องมาตรฐาน และหัวข้อเขียนเอง (อื่นๆ)
                    const standardTitles = ["ขออนุมัติจัดซื้อ/จัดจ้าง", "ขออนุมัติเบิกค่าใช้จ่าย",
                        "ขออนุมัติหลักการ", "แจ้งเพื่อทราบ"
                    ];
                    if (standardTitles.includes(titleData)) {
                        $('#edit_title_select').val(titleData).trigger('change');
                    } else {
                        $('#edit_title_select').val('อื่นๆ').trigger('change');
                        // หากข้อความถูกประกอบคำว่า "อื่นๆ (หัวข้อจริง)" มาจากหลังบ้าน ให้ถอดข้อความในวงเล็บออกมาแสดงผล
                        if (titleData && titleData.startsWith('อื่นๆ (')) {
                            let extractedTitle = titleData.substring(7, titleData.length - 1);
                            document.getElementById('edit_custom_title_input').value = extractedTitle;
                        } else {
                            document.getElementById('edit_custom_title_input').value = titleData || '';
                        }
                    }

                    // จัดการคลี่ข้อมูลผูกเลือกรายชื่อผู้รับสำเนาส่ง CC บนตัว Select2
                    if (ccIds) {
                        try {
                            $('.select2-edit-cc').val(JSON.parse(ccIds)).trigger('change');
                        } catch (e) {
                            $('.select2-edit-cc').val([]).trigger('change');
                        }
                    } else {
                        $('.select2-edit-cc').val([]).trigger('change');
                    }
                });
            }

            // 5. ตรวจสอบเงื่อนไขข้อมูลและมัดรวมค่าชุดข้อมูลก่อนทำการ Submit ส่งฟอร์มแก้ไข
            $('#editMemoForm').on('submit', function(e) {
                // จัดการรวบรวมคำกรอกหัวข้อกรณีเลือกแบบระบุเอง "อื่นๆ" เพื่อแปลงรูปเป็นข้อความเรื่องหลัก
                var selectedTitle = $('#edit_title_select').val();
                if (selectedTitle === 'อื่นๆ') {
                    var customText = $('#edit_custom_title_input').val().trim();
                    if (customText === '') {
                        alert('กรุณาระบุรายละเอียดหัวข้อเรื่องเพิ่มเติมด้วยครับ');
                        e.preventDefault();
                        return false;
                    }
                    $('#edit_real_title').val('อื่นๆ (' + customText + ')');
                } else {
                    $('#edit_real_title').val(selectedTitle);
                }

                // ดึงข้อความและรหัสโครงสร้าง HTML ออกมาจากระบบกล่องข้อความ Quill Editor
                var htmlContent = editQuill.root.innerHTML;
                // ตรวจสอบความถูกต้องเพื่อไม่ปล่อยให้พนักงานส่งกล่องบันทึกว่างเปล่า
                if (htmlContent === '<p><br></p>' || htmlContent.trim() === '') {
                    alert('กรุณากรอกข้อความและรายละเอียดคำอธิบายบันทึกภายในก่อนกดบันทึกครับ');
                    e.preventDefault();
                    return false;
                }
                // แนบข้อมูล HTML เข้าสู่ Hidden Input เพื่อดำเนินการบันทึกต่อในฐานข้อมูลได้อย่างปลอดภัย
                $('#edit_content_input').val(htmlContent);
            });
        });
    </script>
@endsection
