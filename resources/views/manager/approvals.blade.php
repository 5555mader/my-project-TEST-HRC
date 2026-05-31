@extends('layouts.ess')
@section('title', 'ศูนย์อนุมัติคำขอ')

@section('content')
    {{-- นำเข้า Google Fonts: Sarabun --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">

    <style>
        /* บังคับใช้ฟอนต์ Sarabun ทั้งระบบตาราง, ตัว Modal และกระดาษพิมพ์พรีวิว A4 */
        body,
        table,
        .modal-content,
        .print-a4-paper,
        #modal-content {
            font-family: 'Sarabun', sans-serif !important;
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
            color: #000000;
        }

        /* สไตล์เดิมที่จำเป็นสำหรับการพรีวิวกระดาษ A4 */
        .preview-a4-container {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
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

            #modal-content {
                min-height: auto !important;
                max-height: 160mm !important;
                overflow: hidden !important;
                line-height: 1.6 !important;
            }
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Approval Center</h1>
        <span
            class="badge bg-danger rounded-pill">{{ count($pendingLeaves ?? []) + (isset($pendingDocuments) ? count($pendingDocuments) : 0) }}
            คำขอที่รอดำเนินการ</span>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="approvalTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="leave-tab" data-bs-toggle="tab"
                        data-bs-target="#leave">คำขอลา</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="ot-tab" data-bs-toggle="tab" data-bs-target="#ot">คำขอโอที (OT)</button>
                </li>

                {{-- ปรับเพิ่มสิทธิ์ Director และ CEO ให้เห็น Tab บันทึกข้อความภายใน --}}
                @if (in_array(Auth::user()->role, ['HR Manager', 'Manager', 'Super Admin', 'Director', 'CEO']))
                    <li class="nav-item">
                        <button class="nav-link text-primary fw-bold" id="internal-doc-tab" data-bs-toggle="tab"
                            data-bs-target="#internal-doc">
                            <i class="bi bi-file-earmark-text me-1"></i> บันทึกข้อความภายใน
                            @if (isset($pendingDocuments) && count($pendingDocuments) > 0)
                                <span class="badge bg-danger ms-1">{{ count($pendingDocuments) }}</span>
                            @endif
                        </button>
                    </li>
                @endif
            </ul>

            <div class="tab-content" id="approvalTabContent">
                {{-- 1. Tab คำขอลา --}}
                <div class="tab-pane fade show active" id="leave">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>พนักงาน</th>
                                    <th>ประเภท</th>
                                    <th>วันที่</th>
                                    <th>เหตุผล</th>
                                    <th class="text-center">ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingLeaves as $leave)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $leave->user->name }}</div>
                                            <small class="text-muted">{{ $leave->user->department }}</small>
                                        </td>
                                        <td>{{ $leave->leave_type }}</td>
                                        <td>{{ $leave->start_date }} ถึง {{ $leave->end_date }}</td>
                                        <td>{{ $leave->reason }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('manager.approvals.update', $leave->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                {{-- 🌟 เพิ่ม onclick ดักเหตุการณ์ที่ปุ่มนี้ 🌟 --}}
                                                <button type="submit" class="btn btn-sm btn-success me-1"
                                                    onclick="confirmApprove(event, this)">
                                                    <i class="bi bi-check-lg"></i> อนุมัติ
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal"
                                                data-action="{{ route('manager.approvals.update', $leave->id) }}">
                                                <i class="bi bi-x-lg"></i> ปฏิเสธ
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 2. Tab คำขอโอที (Placeholder) --}}
                <div class="tab-pane fade" id="ot">
                    <p class="text-center py-4 text-muted">ไม่มีคำขอโอทีในขณะนี้</p>
                </div>

                {{-- 3. Tab บันทึกข้อความภายใน --}}
                {{-- ปรับเพิ่มสิทธิ์ Director และ CEO ในส่วนของเนื้อหา Tab --}}
                @if (in_array(Auth::user()->role, ['HR Manager', 'Manager', 'Super Admin', 'Director', 'CEO']))
                    <div class="tab-pane fade" id="internal-doc" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light small text-muted">
                                    <tr>
                                        <th>เลขที่เอกสาร / เรื่อง</th>
                                        <th>ผู้ส่งคำขอ</th>
                                        <th>แผนก</th>
                                        <th>เรียน (ถึง)</th>
                                        <th class="text-center">ดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingDocuments as $doc)
                                        <tr>
                                            <td>
                                                <span class="d-block text-muted small">{{ $doc->doc_number }}</span>
                                                <strong>{{ $doc->title }}</strong>
                                            </td>
                                            <td>{{ $doc->user->name ?? 'ไม่ระบุ' }}</td>
                                            <td><span
                                                    class="badge bg-secondary bg-opacity-10 text-secondary">{{ $doc->department }}</span>
                                            </td>
                                            <td>{{ $doc->to_position }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    {{-- เพิ่ม Data Attributes ให้ปุ่ม ดูรายละเอียด --}}
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal" data-bs-target="#viewDocModal"
                                                        data-title="{{ $doc->title }}"
                                                        data-content="{{ $doc->content }}"
                                                        data-sender="{{ $doc->user->name ?? '' }}"
                                                        data-number="{{ $doc->doc_number ?? '' }}"
                                                        data-dept="{{ $doc->department ?? '' }}"
                                                        data-to="{{ $doc->to_position ?? '' }}"
                                                        data-date="{{ \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y') }}"
                                                        data-approver1-name="{{ $doc->approver->name ?? '..................................' }}"
                                                        data-cc="{{ is_array($doc->cc_users) ? implode(', ', \App\Models\User::whereIn('id', $doc->cc_users)->pluck('name')->toArray()) : 'ไม่มีสำเนาส่ง' }}"
                                                        data-files="{{ $doc->files ? $doc->files->toJson() : '[]' }}">
                                                        <i class="bi bi-eye me-1"></i>ดูรายละเอียด
                                                    </button>

                                                    <form action="{{ route('admin.documents.update_status', $doc->id) }}"
                                                        method="POST" class="m-0 d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="approved">
                                                        {{-- 🌟 เพิ่ม onclick ดักเหตุการณ์ที่ปุ่มนี้ 🌟 --}}
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            onclick="confirmApprove(event, this)">
                                                            <i class="bi bi-check-circle"></i> อนุมัติ
                                                        </button>
                                                    </form>

                                                    {{-- ปุ่มไม่อนุมัติ (เปิด Modal พร้อมส่ง URL ไปประมวลผล) --}}
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal"
                                                        data-action="{{ route('admin.documents.update_status', $doc->id) }}">
                                                        ไม่อนุมัติ
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="bi bi-folder-x fs-3 d-block mb-2 text-light"></i>
                                                ไม่มีคำขอบันทึกข้อความภายในที่รอดำเนินการ
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL: แสดงรายละเอียดบันทึก --}}
    <div class="modal fade" id="viewDocModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-primary"><i
                            class="bi bi-file-earmark-text me-2"></i>รายละเอียดบันทึกภายใน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body preview-a4-container">
                    <div class="print-a4-paper text-start shadow-sm bg-white p-5 rounded border">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-0">บันทึกข้อความ</h4>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pb-2 mb-3 text-sm border-b border-gray-300 row">
                            <div class="col-6 mb-1"><span class="font-bold fw-bold">จาก:</span> <span
                                    id="modal-a4-sender"></span></div>
                            <div class="col-6 mb-1"><span class="font-bold fw-bold">ที่ (เลขหนังสือ):</span> <span
                                    id="modal-a4-number"></span></div>
                            <div class="col-6 mb-1"><span class="font-bold fw-bold">แผนก:</span> <span
                                    id="modal-a4-dept"></span></div>
                            <div class="col-6 mb-1"><span class="font-bold fw-bold">เรียน:</span> <span
                                    id="modal-a4-to"></span></div>
                        </div>

                        <div class="d-flex align-items-center mb-1">
                            <span class="font-bold fw-bold shrink-0">โครงการ:</span>
                            <span id="modal-a4-title" class="ms-2 font-bold fw-bold text-base text-gray-900"></span>
                        </div>

                        <div class="d-flex align-items-center mb-4 pb-2 border-b border-gray-100 text-sm">
                            <span class="font-bold fw-bold text-muted shrink-0">สำเนาส่ง (CC):</span>
                            <span id="modal-a4-cc" class="ms-2 text-gray-600 italic"></span>
                        </div>

                        <div id="modal-content" class="my-4 text-sm leading-relaxed text-gray-800"
                            style="min-height: 150px; white-space: pre-wrap;"></div>

                        {{-- 🌟 เพิ่มกล่องรับไฟล์แนบตรงนี้ (ก่อนถึงโซนลายเซ็น) 🌟 --}}
                        <div class="mt-4 pt-3 border-top d-print-none">
                            <span class="font-bold fw-bold text-secondary d-block mb-2" style="font-size: 13px;">
                                <i class="bi bi-paperclip me-1"></i>ไฟล์แนบเอกสาร:
                            </span>

                            <div id="file-attachments-container" class="d-flex flex-column gap-2"></div>

                            <div id="no-file-message" class="text-muted italic text-xs" style="font-size: 12px;">
                                ไม่มีไฟล์แนบสำหรับเอกสารฉบับนี้
                            </div>
                        </div>
                        {{-- 🌟 สิ้นสุดโซนไฟล์แนบ 🌟 --}}

                        {{-- ปรับแก้โซนลายเซ็นให้ดึงผู้อนุมัติคนที่ 2 ลงมาด้านล่าง --}}
                        <div class="mt-16 border-t pt-5 text-gray-800 text-center"
                            style="font-family: 'Sarabun', sans-serif; font-size: 13px;">

                            {{-- แถวที่ 1: ผู้ขออนุมัติ และ ผู้อนุมัติคนที่ 1 --}}
                            <div class="row">
                                <div class="col-6 mb-4">
                                    <p class="mb-5 text-nowrap">
                                        ลงชื่อ..............................................ผู้ขออนุมัติ</p>
                                    <p class="font-bold fw-bold">( <span id="modal-sig-sender">NAMES</span> )</p>
                                    <p class="mt-1 text-muted">วันที่ <span id="modal-sig-date1"></span></p>
                                </div>
                                <div class="col-6 mb-4">
                                    <p class="mb-5 text-nowrap">
                                        ลงชื่อ..............................................ผู้อนุมัติ (1)</p>
                                    <p class="font-bold fw-bold">( <span
                                            id="modal-sig-approver1">..............................................</span>
                                        )</p>
                                    <p class="mt-1 text-muted">วันที่ <span id="modal-sig-date2"></span></p>
                                </div>
                            </div>

                            {{-- แถวที่ 2: ผู้อนุมัติคนที่ 2 (ดึงลงมาเพื่อไม่ให้เบียด) --}}
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <p class="mb-5 text-nowrap">
                                        ลงชื่อ..............................................ผู้อนุมัติ (2)</p>
                                    <p class="font-bold fw-bold">( <span
                                            id="modal-sig-approver2">..............................................</span>
                                        )</p>
                                    <p class="mt-1 text-muted">วันที่ <span id="modal-sig-date3"></span></p>
                                </div>
                            </div>
                        </div>
                        {{-- สิ้นสุดการปรับแก้โซนลายเซ็น --}}

                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: สำหรับกรอกเหตุผลการไม่อนุมัติ (ส่งค่า reject_reason) --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">

                    <div class="modal-header border-0 bg-danger text-white">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>ระบุเหตุผลการไม่อนุมัติ
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <label class="form-label fw-bold">กรุณาระบุเหตุผลที่ไม่อนุมัติคำขอนี้:</label>
                        <textarea name="reject_reason" class="form-control" rows="4"
                            placeholder="พิมพ์เหตุผลที่นี่เพื่อแจ้งให้พนักงานทราบ..." required></textarea>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-danger px-4 shadow-sm">ยืนยันการไม่อนุมัติ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const viewDocModal = document.getElementById('viewDocModal');
            if (viewDocModal) {
                viewDocModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    // ผูกข้อมูลโครงสร้างกระดาษ A4หลัก
                    viewDocModal.querySelector('#modal-a4-title').textContent = button.getAttribute(
                        'data-title');
                    viewDocModal.querySelector('#modal-content').innerHTML = button.getAttribute(
                        'data-content');
                    viewDocModal.querySelector('#modal-a4-sender').textContent = button.getAttribute(
                        'data-sender');
                    viewDocModal.querySelector('#modal-a4-number').textContent = button.getAttribute(
                        'data-number');
                    viewDocModal.querySelector('#modal-a4-dept').textContent = button.getAttribute(
                        'data-dept');
                    viewDocModal.querySelector('#modal-a4-to').textContent = button.getAttribute('data-to');

                    // ผูกการแสดงผลพนักงานที่ได้รับ สำเนาส่ง (CC)
                    const ccUsers = button.getAttribute('data-cc');
                    viewDocModal.querySelector('#modal-a4-cc').textContent = ccUsers ? ccUsers :
                        'ไม่มีสำเนาส่ง';

                    // จัดการและจัดสรรข้อมูลลงในแถบลายเซ็นท้ายเอกสาร
                    const docDate = button.getAttribute('data-date');
                    const formattedDate = docDate ? docDate : '28/05/2569'; // Fallback กรณีหาค่าไม่เจอ

                    viewDocModal.querySelector('#modal-sig-sender').textContent = button.getAttribute(
                        'data-sender');
                    viewDocModal.querySelector('#modal-sig-approver1').textContent = button.getAttribute(
                        'data-approver1-name') || 'ฟิล์ม';
                    viewDocModal.querySelector('#modal-sig-approver2').textContent = button.getAttribute(
                        'data-approver2-name') || '..............................................';

                    // ส่งข้อมูลวันที่กระจายให้ครบทั้ง 3 ช่องเพื่อความเป็นระเบียบและเข้าชุดกัน
                    viewDocModal.querySelector('#modal-sig-date1').textContent = formattedDate;
                    viewDocModal.querySelector('#modal-sig-date2').textContent = formattedDate;
                    viewDocModal.querySelector('#modal-sig-date3').textContent = formattedDate;

                    // 🌟 เพิ่มโค้ดควบคุมการแสดงผลปุ่มคู่ (ดูไฟล์ / โหลดไฟล์) ตรงนี้ 🌟
                    const filesData = button.getAttribute('data-files');
                    const fileContainer = document.getElementById('file-attachments-container');
                    const noFileMsg = document.getElementById('no-file-message');

                    if (fileContainer) fileContainer.innerHTML = ''; // ล้างค่าเก่าทุกครั้งที่เปิดใหม่

                    if (filesData && filesData !== '[]') {
                        try {
                            const files = JSON.parse(filesData);
                            if (files.length > 0) {
                                if (noFileMsg) noFileMsg.classList.add('d-none');

                                files.forEach(file => {
                                    const fileUrl = window.location.origin + '/uploads/documents/' +
                                        file.file_name;
                                    const fileHtml = `
                                        <div class="d-flex align-items-center justify-content-between w-100 p-2.5 bg-light border rounded-3 mb-1" style="max-width: 380px; border-color: #e3e6f0 !important;">
                                            <div class="d-flex align-items-center overflow-hidden me-2">
                                                <div class="p-2 bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; min-width: 34px;">
                                                    <i class="bi bi-paperclip fs-5"></i>
                                                </div>
                                                <p class="text-dark font-bold fw-bold mb-0 text-truncate ms-2 small" style="max-width: 160px; font-size: 12.5px;">${file.file_name}</p>
                                            </div>
                                            <div class="d-flex gap-1 shadow-xs shrink-0">
                                                <a href="${fileUrl}" target="_blank" class="btn btn-xs btn-primary text-white py-1 px-2 small d-flex align-items-center gap-1" style="font-size: 11px;" title="เปิดดูบนหน้าเว็บ">
                                                    <i class="bi bi-eye-fill"></i> ดู
                                                </a>
                                                <a href="${fileUrl}" download="${file.file_name}" class="btn btn-xs btn-success text-white py-1 px-2 small d-flex align-items-center gap-1" style="font-size: 11px;" title="บันทึกเข้าเครื่อง">
                                                    <i class="bi bi-download"></i> โหลด
                                                </a>
                                            </div>
                                        </div>
                                    `;
                                    fileContainer.insertAdjacentHTML('beforeend', fileHtml);
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

            // รับ URL จากปุ่มที่ถูกคลิกแล้วส่งเข้าแบบฟอร์มการปฏิเสธ
            const rejectModal = document.getElementById('rejectModal');
            if (rejectModal) {
                rejectModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    document.getElementById('rejectForm').action = button.getAttribute('data-action');
                });
            }

            let activeTab = localStorage.getItem('lastActiveTab');
            if (activeTab) {
                let tabToActivate = document.querySelector('#' + activeTab);
                if (tabToActivate) {
                    let bsTab = new bootstrap.Tab(tabToActivate);
                    bsTab.show();
                }
            }

            let tabButtons = document.querySelectorAll('button[data-bs-toggle="tab"]');
            tabButtons.forEach(button => {
                button.addEventListener('shown.bs.tab', function(event) {
                    localStorage.setItem('lastActiveTab', event.target.id);
                });
            });
        });
    </script>

    {{-- 🌟 1. นำเข้าไลบรารี SweetAlert2 สำหรับทำ Popup 🌟 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 🌟 2. ฟังก์ชันควบคุม Popup อนุมัติ 🌟
        function confirmApprove(event, button) {
            event.preventDefault(); // หยุดการส่งข้อมูลชั่วคราวเพื่อโชว์ Popup ก่อน
            const form = button.closest('form'); // หาฟอร์มที่ปุ่มนี้อาศัยอยู่

            // โชว์ Popup ถามเพื่อความแน่ใจ
            Swal.fire({
                title: 'ยืนยันการอนุมัติ?',
                text: "คุณตรวจสอบเอกสารและต้องการอนุมัติใช่หรือไม่?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754', // สีเขียว Success
                cancelButtonColor: '#6c757d', // สีเทา Secondary
                confirmButtonText: '<i class="bi bi-check-circle"></i> ใช่, อนุมัติเลย',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // 🌟 หากกดยืนยัน จะเด้ง Popup โหลด ป้องกันการกดซ้ำ และแจ้งว่ากดไปแล้ว 🌟
                    Swal.fire({
                        title: 'กำลังดำเนินการ...',
                        text: 'ระบบรับทราบว่าผู้อนุมัติได้กดแล้ว กรุณารอสักครู่',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading(); // โชว์ไอคอนหมุนๆ
                        }
                    });

                    // สั่งให้ฟอร์มส่งข้อมูลไปยังระบบหลังบ้านจริงๆ
                    form.submit();
                }
            });
        }
    </script>
@endsection
