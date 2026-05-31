@extends('layouts.ess')
@section('title', 'จัดการแบบฟอร์มเอกสาร')

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
        <h1 class="h3">แบบฟอร์มและเอกสาร (Document Center)</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-cloud-upload me-2"></i>อัปโหลดไฟล์ใหม่
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="list-group list-group-flush rounded">
                    <a href="{{ route('admin.documents') }}"
                        class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">ทั้งหมด</a>
                    <a href="{{ route('admin.documents', ['category' => 'แบบฟอร์มการลา']) }}"
                        class="list-group-item list-group-item-action {{ request('category') == 'แบบฟอร์มการลา' ? 'active' : '' }}">แบบฟอร์มการลา</a>
                    <a href="{{ route('admin.documents', ['category' => 'เอกสารภาษี/การเงิน']) }}"
                        class="list-group-item list-group-item-action {{ request('category') == 'เอกสารภาษี/การเงิน' ? 'active' : '' }}">เอกสารภาษี/การเงิน</a>
                    <a href="{{ route('admin.documents', ['category' => 'สวัสดิการ/ประกันภัย']) }}"
                        class="list-group-item list-group-item-action {{ request('category') == 'สวัสดิการ/ประกันภัย' ? 'active' : '' }}">สวัสดิการ/ประกันภัย</a>
                    <a href="{{ route('admin.documents', ['category' => 'คู่มือการทำงาน']) }}"
                        class="list-group-item list-group-item-action {{ request('category') == 'คู่มือการทำงาน' ? 'active' : '' }}">คู่มือการทำงาน</a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.documents') }}" method="GET" class="input-group mb-3">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0"
                            placeholder="ค้นหาชื่อเอกสาร..." value="{{ request('search') }}">
                        <button class="btn btn-dark" type="submit">ค้นหา</button>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ชื่อเอกสาร</th>
                                    <th>หมวดหมู่</th>
                                    <th>วันที่อัปเดต</th>
                                    <th>ขนาด</th>
                                    <th class="text-center">ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $doc)
                                    <tr>
                                        <td>
                                            @if ($doc->file_extension == 'pdf')
                                                <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>
                                            @elseif(in_array($doc->file_extension, ['doc', 'docx']))
                                                <i class="bi bi-file-earmark-word-fill text-primary me-2"></i>
                                            @elseif(in_array($doc->file_extension, ['xls', 'xlsx']))
                                                <i class="bi bi-file-earmark-excel-fill text-success me-2"></i>
                                            @else
                                                <i class="bi bi-file-earmark-fill text-secondary me-2"></i>
                                            @endif
                                            <span class="fw-bold">{{ $doc->title }}</span>
                                        </td>
                                        <td><span
                                                class="badge bg-secondary bg-opacity-10 text-secondary">{{ $doc->category }}</span>
                                        </td>
                                        <td class="small text-muted">{{ $doc->created_at->format('d/m/Y') }}</td>
                                        <td class="small">{{ $doc->file_size }}</td>
                                        <td class="text-center">
                                            {{-- 1. เพิ่มปุ่มลูกตาตรงนี้ --}}
                                            <button class="btn btn-sm btn-outline-info me-1" title="ดูตัวอย่าง"
                                                data-bs-toggle="modal" data-bs-target="#viewModal"
                                                data-url="{{ asset('uploads/documents/' . $doc->file_name) }}"
                                                data-title="{{ $doc->title }}">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <a href="{{ asset('uploads/documents/' . $doc->file_name) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary" title="ดาวน์โหลด">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <form action="{{ route('admin.documents.destroy', $doc->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('ยืนยันการลบเอกสารนี้?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="ลบ"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">ไม่พบเอกสารในระบบ</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title">อัปโหลดเอกสารใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">ชื่อเอกสาร</label>
                            <input type="text" name="title" class="form-control" placeholder="ระบุชื่อเรียกเอกสาร"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">หมวดหมู่</label>
                            <select name="category" class="form-select" required>
                                <option value="แบบฟอร์มการลา">แบบฟอร์มการลา</option>
                                <option value="เอกสารภาษี/การเงิน">เอกสารภาษี/การเงิน</option>
                                <option value="สวัสดิการ/ประกันภัย">สวัสดิการ/ประกันภัย</option>
                                <option value="คู่มือการทำงาน">คู่มือการทำงาน</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">เลือกไฟล์ (PDF, Word, Excel)</label>
                            <input type="file" name="file" class="form-control"
                                accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">ยืนยันการอัปโหลด</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Modal สำหรับแสดงตัวอย่างเอกสาร --}}
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold text-primary">ตัวอย่างเอกสาร</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    {{-- ใช้ iframe ในการโหลดไฟล์ PDF หรือเอกสารอื่นๆ --}}
                    <div id="fileViewerContainer" style="height: 80vh;">
                        <iframe id="fileViewer" src="" style="width: 100%; height: 100%; border: none;"></iframe>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
                    <a id="fullScreenBtn" href="" target="_blank" class="btn btn-primary">
                        <i class="bi bi-box-arrow-up-right me-1"></i> เปิดในหน้าต่างใหม่
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. เพิ่ม JavaScript เพื่อเปลี่ยนไฟล์ตามที่คลิก --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewModal = document.getElementById('viewModal');
            if (viewModal) {
                viewModal.addEventListener('show.bs.modal', function(event) {
                    // ปุ่มที่ถูกคลิก
                    const button = event.relatedTarget;

                    // ดึงค่าจาก Data attributes
                    const fileUrl = button.getAttribute('data-url');
                    const fileTitle = button.getAttribute('data-title');

                    // อัปเดตเนื้อหาใน Modal
                    const modalTitle = viewModal.querySelector('.modal-title');
                    const iframe = viewModal.querySelector('#fileViewer');
                    const fullScreenBtn = viewModal.querySelector('#fullScreenBtn');

                    modalTitle.textContent = 'ดูเอกสาร: ' + fileTitle;

                    // กรณีเป็นไฟล์ Word/Excel อาจต้องใช้ Google Docs Viewer ช่วยในการแสดงผลบนเว็บ
                    const fileExtension = fileUrl.split('.').pop().toLowerCase();
                    if (['doc', 'docx', 'xls', 'xlsx'].includes(fileExtension)) {
                        iframe.src =
                            `https://docs.google.com/viewer?url=${encodeURIComponent(fileUrl)}&embedded=true`;
                    } else {
                        iframe.src = fileUrl;
                    }

                    fullScreenBtn.href = fileUrl;
                });

                // ล้างค่า src เมื่อปิด Modal เพื่อหยุดการโหลดไฟล์ค้างไว้
                viewModal.addEventListener('hidden.bs.modal', function() {
                    viewModal.querySelector('#fileViewer').src = '';
                });
            }
        });
    </script>
@endsection
