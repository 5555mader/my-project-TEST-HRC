@extends('layouts.ess')
@section('title', 'ศูนย์จัดการและคลังเอกสาร')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght=0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap"
        rel="stylesheet">

    <style>
        body,
        table,
        .modal-content,
        .accordion-button,
        .nav-link {
            font-family: 'Sarabun', sans-serif !important;
        }

        .print-a4-paper {
            background: white;
            width: 100%;
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 25mm 20mm 20mm 20mm;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            color: #000;
        }

        .preview-a4-container {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">ศูนย์จัดการและคลังเอกสาร</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('document.form') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> สร้างบันทึกข้อความใหม่
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.documents') }}" method="GET" class="row g-2">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="ค้นหาชื่อเอกสาร เรื่อง หรือเลขที่เอกสาร..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">ค้นหา</button>
                </div>
            </form>
        </div>
    </div>

    <ul class="nav nav-pills mb-4 bg-white p-2 rounded shadow-sm" id="documentMasterTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill" id="public-docs-tab" data-bs-toggle="tab"
                data-bs-target="#public-docs" type="button" role="tab">
                <i class="bi bi-cloud-download me-1"></i> แบบฟอร์มเอกสารส่วนกลาง
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill position-relative" id="internal-memos-tab" data-bs-toggle="tab"
                data-bs-target="#internal-memos" type="button" role="tab">
                <i class="bi bi-archive me-1"></i> คลังบันทึกข้อความภายในองค์กร
            </button>
        </li>
    </ul>

    <div class="tab-content" id="documentMasterTabContent">

        <div class="tab-pane fade show active" id="public-docs" role="tabpanel">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="list-group shadow-sm">
                        <a href="{{ route('admin.documents') }}"
                            class="list-group-item list-group-item-action {{ !request('category') ? 'active bg-primary' : '' }}">
                            แสดงทั้งหมด
                        </a>
                        @foreach ($categories as $cat)
                            <a href="{{ route('admin.documents', ['category' => $cat]) }}"
                                class="list-group-item list-group-item-action {{ request('category') == $cat ? 'active bg-primary' : '' }}">
                                {{ $cat }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card border-0 shadow-sm">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ชื่อแบบฟอร์ม / เอกสารส่วนกลาง</th>
                                        <th>หมวดหมู่</th>
                                        <th class="text-end">ดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($documents as $doc)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark"><i
                                                        class="bi bi-file-earmark-text text-primary me-2"></i>{{ $doc->title }}
                                                </div>
                                                <small class="text-muted">{{ strtoupper($doc->file_extension) }} •
                                                    {{ number_format(floatval($doc->file_size) / 1024, 2) }} KB</small>
                                            </td>
                                            <td><span
                                                    class="badge bg-secondary bg-opacity-10 text-secondary">{{ $doc->category }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ asset('uploads/documents/' . $doc->file_name) }}"
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                                    target="_blank">
                                                    <i class="bi bi-download me-1"></i> ดาวน์โหลด
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">ไม่พบข้อมูลเอกสารส่วนกลาง
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="internal-memos" role="tabpanel">
            <div class="accordion shadow-sm" id="memoGroupAccordion">
                @php $index = 0; @endphp
                @foreach ($groupedMemos as $groupName => $memos)
                    @php $index++; @endphp
                    <div class="accordion-item border-0 mb-2 rounded overflow-hidden shadow-sm">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button bg-white text-dark fw-bold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                aria-expanded="true">
                                <i class="bi bi-folder-fill text-warning me-2"></i> {{ $groupName }}
                                <span class="badge bg-primary rounded-pill ms-2">{{ count($memos) }} รายการ</span>
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse show"
                            data-bs-parent="#memoGroupAccordion">
                            <div class="accordion-body bg-white p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light small">
                                            <tr>
                                                <th class="ps-4">เลขที่เอกสาร / วัตถุประสงค์</th>
                                                <th>ผู้เขียน/เจ้าของเรื่อง</th>
                                                <th>แผนก</th>
                                                <th>สถานะคำขอ</th>
                                                <th class="text-end pe-4">การจัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($memos as $memo)
                                                <tr>
                                                    <td class="ps-4">
                                                        <span
                                                            class="d-block text-muted small">{{ $memo->doc_number }}</span>
                                                        <strong>{{ $memo->title }}</strong>
                                                    </td>
                                                    <td>{{ $memo->user->name ?? 'ไม่ระบุ' }}</td>
                                                    <td>{{ $memo->department }}</td>
                                                    <td>
                                                        @if ($memo->status == 'approved')
                                                            <span
                                                                class="badge bg-success bg-opacity-10 text-success px-3 rounded-pill">✓
                                                                อนุมัติสำเร็จ</span>
                                                        @elseif($memo->status == 'pending' || $memo->status == 'pending_step_2')
                                                            <span
                                                                class="badge bg-warning bg-opacity-10 text-warning px-3 rounded-pill">⏳
                                                                รอตรวจสอบ</span>
                                                        @else
                                                            <span
                                                                class="badge bg-danger bg-opacity-10 text-danger px-3 rounded-pill"
                                                                title="{{ $memo->reject_reason }}">✕
                                                                ปฏิเสธการอนุมัติ</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-sm btn-outline-dark"
                                                                data-bs-toggle="modal" data-bs-target="#viewDocModal"
                                                                data-title="{{ $memo->title }}"
                                                                data-content="{{ $memo->content }}"
                                                                data-sender="{{ $memo->user->name ?? '' }}"
                                                                data-number="{{ $memo->doc_number }}"
                                                                data-dept="{{ $memo->department }}"
                                                                data-to="{{ $memo->to_position }}"
                                                                data-date="{{ \Carbon\Carbon::parse($memo->created_at)->format('d/m/Y') }}"
                                                                data-approver1-name="{{ $memo->approver->name ?? '...........................' }}"
                                                                data-approver2-name="{{ $memo->approver2->name ?? '...........................' }}"
                                                                data-cc="{{ is_array($memo->cc_users) ? implode(', ', \App\Models\User::whereIn('id', $memo->cc_users)->pluck('name')->toArray()) : 'ไม่มีสำเนาส่ง' }}"
                                                                data-files="{{ $memo->files ? $memo->files->toJson() : '[]' }}">
                                                                <i class="bi bi-eye"></i> ดูใบเอกสาร
                                                            </button>
                                                            <a href="{{ route('admin.archives.show-form', $memo->id) }}"
                                                                target="_blank" class="btn btn-sm btn-light border">
                                                                <i class="bi bi-printer"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted italic">
                                                        ไม่มีรายการเอกสารในหมวดหมู่นี้</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewDocModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold text-dark"><i
                            class="bi bi-file-earmark-pdf text-danger me-2"></i>ตรวจสอบโครงสร้างเอกสารภายใน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body preview-a4-container">
                    <div class="print-a4-paper text-start">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold tracking-widest">บันทึกข้อความ</h3>
                        </div>
                        <div class="row g-2 pb-2 mb-3 border-bottom text-sm">
                            <div class="col-6"><strong>จาก:</strong> <span id="modal-a4-sender"></span></div>
                            <div class="col-6"><strong>ที่ (เลขหนังสือ):</strong> <span id="modal-a4-number"></span>
                            </div>
                            <div class="col-6"><strong>แผนก:</strong> <span id="modal-a4-dept"></span></div>
                            <div class="col-6"><strong>เรียน:</strong> <span id="modal-a4-to"></span></div>
                        </div>
                        <div class="mb-1"><strong>เรื่อง/วัตถุประสงค์:</strong> <span id="modal-a4-title"
                                class="fw-bold text-gray-900 ms-1"></span></div>
                        <div class="mb-4 pb-2 border-bottom text-sm text-muted"><strong>สำเนาส่ง (CC):</strong> <span
                                id="modal-a4-cc" class="italic"></span></div>

                        <div id="modal-content" class="my-4 text-sm leading-relaxed"
                            style="min-height: 120mm; white-space: pre-wrap;"></div>

                        <div class="mt-4 pt-3 border-top bg-light p-2 rounded">
                            <span class="font-bold fw-bold text-secondary d-block mb-1 small"><i
                                    class="bi bi-paperclip"></i> รายการไฟล์แนบเพิ่มเติม:</span>
                            <div id="file-attachments-container" class="d-flex flex-column gap-1"></div>
                        </div>

                        <div class="mt-12 text-center text-sm row">
                            <div class="col-4">
                                <p class="mb-5">ลงชื่อ.........................................ผู้ขอ</p>
                                <p class="fw-bold">( <span id="modal-sig-sender"></span> )</p>
                            </div>
                            <div class="col-4">
                                <p class="mb-5">ลงชื่อ.........................................ผู้อนุมัติ (1)</p>
                                <p class="fw-bold">( <span id="modal-sig-approver1"></span> )</p>
                            </div>
                            <div class="col-4">
                                <p class="mb-5">ลงชื่อ.........................................ผู้อนุมัติ (2)</p>
                                <p class="fw-bold">( <span id="modal-sig-approver2"></span> )</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewDocModal = document.getElementById('viewDocModal');
            if (viewDocModal) {
                viewDocModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

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
                    viewDocModal.querySelector('#modal-a4-cc').textContent = button.getAttribute('data-cc');

                    viewDocModal.querySelector('#modal-sig-sender').textContent = button.getAttribute(
                        'data-sender');
                    viewDocModal.querySelector('#modal-sig-approver1').textContent = button.getAttribute(
                        'data-approver1-name');
                    viewDocModal.querySelector('#modal-sig-approver2').textContent = button.getAttribute(
                        'data-approver2-name');

                    // ระบบลูปเรนเดอร์การดาวน์โหลดไฟล์แนบร่วม
                    const filesData = button.getAttribute('data-files');
                    const fileContainer = document.getElementById('file-attachments-container');
                    fileContainer.innerHTML = '';

                    if (filesData && filesData !== '[]') {
                        try {
                            const files = JSON.parse(filesData);
                            files.forEach(file => {
                                const fileUrl = window.location.origin + '/uploads/documents/' +
                                    file.file_name;
                                fileContainer.insertAdjacentHTML('beforeend', `
                                    <div class="d-flex justify-content-between bg-white p-2 border rounded mb-1 text-xs">
                                        <span><i class="bi bi-file-earmark-arrow-down me-1"></i>${file.file_name}</span>
                                        <a href="${fileUrl}" download class="btn btn-xs btn-success py-0 px-2 text-white" style="font-size:11px;">ดาวน์โหลด</a>
                                    </div>
                                `);
                            });
                        } catch (e) {
                            fileContainer.innerHTML =
                                '<span class="text-muted text-xs">ไม่มีไฟล์แนบ</span>';
                        }
                    } else {
                        fileContainer.innerHTML = '<span class="text-muted text-xs">ไม่มีไฟล์แนบ</span>';
                    }
                });
            }
        });
    </script>
@endsection
