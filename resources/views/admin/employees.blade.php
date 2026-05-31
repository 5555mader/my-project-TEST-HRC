@extends('layouts.ess')
@section('title', 'จัดการพนักงาน')

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
        <h1 class="h3">การจัดการพนักงาน (Employee Management)</h1>
        <div class="d-flex gap-2">
            {{-- ปุ่มกดเปิดหน้าต่างจัดการ --}}
            <button class="btn btn-outline-info shadow-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                <i class="bi bi-diagram-3 me-2"></i>จัดการแผนก
            </button>
            <button class="btn btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addBranchModal">
                <i class="bi bi-building-add me-2"></i>จัดการสาขา
            </button>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                <i class="bi bi-person-plus me-2"></i>เพิ่มพนักงานใหม่
            </button>
        </div>
    </div>

    {{-- แสดงข้อความแจ้งเตือน --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ส่วนฟอร์มค้นหาพนักงาน --}}
    <div class="card border-0 shadow-sm mb-4 bg-white">
        <div class="card-body p-3">
            <form action="{{ route('admin.employees') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0"
                            placeholder="ค้นหาชื่อ, รหัสพนักงาน หรืออีเมล..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-dark w-100">ค้นหา</button>
                </div>
                @if (request('search'))
                    <div class="col-md-2">
                        <a href="{{ route('admin.employees') }}" class="btn btn-link text-danger text-decoration-none">
                            <i class="bi bi-x-circle me-1"></i>ล้างการค้นหา
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- ตารางแสดงรายชื่อพนักงาน --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-4">รหัส / ชื่อพนักงาน</th>
                            <th>ข้อมูลติดต่อ</th>
                            <th>แผนก / ตำแหน่ง</th>
                            <th>สาขา</th>
                            <th>บทบาท (Role)</th>
                            <th class="text-center pe-4">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if ($emp->image && file_exists(public_path('uploads/profiles/' . $emp->image)))
                                            <img src="{{ asset('uploads/profiles/' . $emp->image) }}"
                                                class="rounded-circle me-3 object-fit-cover shadow-sm" width="40"
                                                height="40">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($emp->name) }}&background=random"
                                                class="rounded-circle me-3 shadow-sm" width="40">
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark">{{ $emp->name }}</div>
                                            <div class="text-muted small">EMP{{ str_pad($emp->id, 3, '0', STR_PAD_LEFT) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small"><i class="bi bi-envelope me-1 text-muted"></i> {{ $emp->email }}
                                    </div>
                                    <div class="small"><i class="bi bi-telephone me-1 text-muted"></i>
                                        {{ $emp->phone ?? '-' }}</div>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 mb-1">{{ $emp->department ?? 'ไม่ระบุแผนก' }}</span>
                                </td>
                                <td>
                                    <span class="small"><i class="bi bi-geo-alt text-danger me-1"></i>
                                        {{ $emp->branch ?? '-' }}</span>
                                </td>
                                <td>
                                    @if ($emp->role == 'Super Admin')
                                        <span class="badge bg-danger">Super Admin</span>
                                    @elseif($emp->role == 'CEO')
                                        <span class="badge bg-dark">CEO</span>
                                    @elseif($emp->role == 'Director')
                                        <span class="badge bg-primary">Director</span>
                                    @elseif(str_contains($emp->role, 'HR Manager'))
                                        <span class="badge bg-warning text-dark">HR Manager</span>
                                    @elseif(str_contains($emp->role, 'Manager'))
                                        <span class="badge bg-info text-dark">Manager</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $emp->role ?? 'Employee' }}</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        {{-- ปุ่มเปิด Single Modal สำหรับแก้ไขข้อมูลพนักงาน --}}
                                        <button class="btn btn-sm btn-light border" data-bs-toggle="modal"
                                            data-bs-target="#editEmployeeModal" data-id="{{ $emp->id }}"
                                            data-name="{{ $emp->name }}" data-email="{{ $emp->email }}"
                                            data-department="{{ $emp->department }}" data-branch="{{ $emp->branch }}"
                                            data-role="{{ $emp->role }}" title="แก้ไขข้อมูล">
                                            <i class="bi bi-pencil-square text-warning"></i>
                                        </button>

                                        @if ($emp->id !== Auth::id())
                                            <form action="{{ route('admin.employees.destroy', $emp->id) }}" method="POST"
                                                class="d-inline ms-1"
                                                onsubmit="return confirm('ยืนยันการลบพนักงาน {{ $emp->name }} ออกจากระบบ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border"
                                                    title="ลบพนักงาน">
                                                    <i class="bi bi-trash text-danger"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">ไม่พบข้อมูลพนักงานที่ตรงกับการค้นหา
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- SINGLE MODAL: แก้ไขข้อมูลพนักงาน (แบบรับค่าผ่าน JS) --}}
    {{-- ========================================== --}}
    <div class="modal fade" id="editEmployeeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <form id="editEmployeeForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header border-0 bg-light">
                        <h5 class="modal-title fw-bold" id="editEmployeeModalLabel">แก้ไขข้อมูลพนักงาน</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">ชื่อ-นามสกุล <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">อีเมล <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">แผนก (Department) <span
                                        class="text-danger">*</span></label>
                                <select name="department" id="edit_department" class="form-select" required>
                                    <option value="">-- เลือกแผนก --</option>
                                    @foreach ($departments ?? [] as $dept)
                                        <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">สาขา (Branch) <span
                                        class="text-danger">*</span></label>
                                <select name="branch" id="edit_branch" class="form-select" required>
                                    <option value="">-- เลือกสาขา --</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ช่อง Select เลือกระดับสิทธิ์แบบ Single Modal (ปรับปรุงตามชุดข้อมูลใหม่) --}}
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">ระดับสิทธิ์ (Role) <span
                                        class="text-danger">*</span></label>
                                <select id="edit_role" name="role" class="form-select" required>
                                    <option value="Employee">พนักงานทั่วไป (Employee)</option>
                                    <option value="Manager">ผู้จัดการ / หัวหน้างาน (Manager)</option>
                                    <option value="HR Manager">ฝ่ายบุคคล (HR Manager)</option>
                                    <option value="Director">ผู้อำนวยการฝ่าย (Director)</option>
                                    <option value="CEO">ผู้บริหารสูงสุด (CEO)</option>
                                    <option value="Super Admin">ผู้ดูแลระบบ (Super Admin)</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-danger">
                                    <i class="bi bi-key-fill"></i> เปลี่ยนรหัสผ่านใหม่ (ปล่อยว่างไว้หากไม่ต้องการเปลี่ยน)
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password" id="edit_password" class="form-control"
                                        placeholder="ระบุรหัสผ่านใหม่สำหรับพนักงานคนนี้">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleEditPasswordBtn">
                                        <i class="bi bi-eye-slash" id="edit_eyeIcon"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Super Admin
                                    สามารถคลิกรูปตาเพื่อตรวจสอบตัวอักษรของรหัสผ่านที่ตั้งใหม่ได้</small>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-warning px-4 shadow-sm">บันทึกการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL: จัดการแผนก (มีรายการ แก้ไข ลบ แบบ Inline) --}}
    {{-- ========================================== --}}
    <div class="modal fade" id="addDepartmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-info bg-opacity-10 text-dark">
                    <h5 class="modal-title fw-bold"><i
                            class="bi bi-diagram-3 me-2 text-info"></i>ศูนย์จัดการแผนกภายในองค์กร</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('admin.departments.store') }}" method="POST"
                        class="mb-4 bg-light p-3 rounded border">
                        @csrf
                        <label class="form-label small fw-bold text-dark"><i
                                class="bi bi-plus-circle me-1"></i>เพิ่มแผนกใหม่</label>
                        <div class="input-group">
                            <input type="text" name="name" class="form-control"
                                placeholder="เช่น ฝ่ายพัฒนาธุรกิจ, ทีมกฎหมาย" required>
                            <button type="submit" class="btn btn-info text-white shadow-sm px-4">บันทึกแผนก</button>
                        </div>
                    </form>

                    <label class="form-label small fw-bold text-muted mb-2"><i
                            class="bi bi-list-nested me-1"></i>รายการแผนกทั้งหมดที่มีอยู่ในปัจจุบัน</label>
                    <div class="table-responsive border rounded" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0 table-sm">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="ps-3 py-2" width="70%">ชื่อแผนก</th>
                                    <th class="text-center py-2" width="30%">ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments ?? [] as $dept)
                                    <tr>
                                        <td class="ps-3 py-2">
                                            <div id="dept-text-{{ $dept->id }}" class="fw-bold text-dark">
                                                {{ $dept->name }}</div>
                                            <form id="dept-form-{{ $dept->id }}"
                                                action="{{ route('admin.departments.update', $dept->id) }}"
                                                method="POST" class="d-none m-0">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ $dept->name }}" required>
                                                    <button type="submit" class="btn btn-success btn-sm">บันทึก</button>
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        onclick="toggleEditDept({{ $dept->id }}, false)">ยกเลิก</button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-center py-2">
                                            <div id="dept-actions-{{ $dept->id }}"
                                                class="btn-group btn-group-sm border shadow-sm rounded">
                                                <button type="button" class="btn btn-white"
                                                    onclick="toggleEditDept({{ $dept->id }}, true)"
                                                    title="แก้ไขชื่อแผนก">
                                                    <i class="bi bi-pencil text-warning"></i>
                                                </button>
                                                <form action="{{ route('admin.departments.destroy', $dept->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('ยืนยันการลบแผนก {{ $dept->name }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-white" title="ลบแผนก">
                                                        <i class="bi bi-trash text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-3 text-muted">ยังไม่มีแผนกผูกในระบบ</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL: จัดการสาขา (มีรายการ แก้ไข ลบ แบบ Inline) --}}
    {{-- ========================================== --}}
    <div class="modal fade" id="addBranchModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-primary bg-opacity-10 text-dark">
                    <h5 class="modal-title fw-bold"><i class="bi bi-building me-2 text-primary"></i>ศูนย์จัดการสาขา /
                        สำนักงานย่อย</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('admin.branches.store') }}" method="POST"
                        class="mb-4 bg-light p-3 rounded border">
                        @csrf
                        <label class="form-label small fw-bold text-dark"><i
                                class="bi bi-plus-circle me-1"></i>เพิ่มสำนักงานหรือสาขาใหม่</label>
                        <div class="row g-2">
                            <div class="col-md-5">
                                <input type="text" name="name" class="form-control form-control-sm"
                                    placeholder="ชื่อสาขา เช่น สาขาขอนแก่น" required>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="location" class="form-control form-control-sm"
                                    placeholder="ที่อยู่ย่อ หรือตำแหน่งที่ตั้ง">
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary btn-sm shadow-sm">บันทึกสาขา</button>
                            </div>
                        </div>
                    </form>

                    <label class="form-label small fw-bold text-muted mb-2"><i
                            class="bi bi-list-ul me-1"></i>สาขาที่เปิดให้บริการในระบบ</label>
                    <div class="table-responsive border rounded" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0 table-sm">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="ps-3 py-2" width="35%">ชื่อสาขา</th>
                                    <th class="py-2" width="45%">สถานที่ตั้ง / ที่อยู่</th>
                                    <th class="text-center py-2" width="20%">ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branches as $branch)
                                    <tr>
                                        <td class="ps-3 py-2">
                                            <div id="branch-name-{{ $branch->id }}" class="fw-bold text-dark">
                                                {{ $branch->name }}</div>
                                        </td>
                                        <td class="py-2">
                                            <div id="branch-loc-{{ $branch->id }}" class="small text-muted">
                                                {{ $branch->location ?? '-' }}</div>
                                        </td>
                                        <td class="text-center py-2">
                                            <div id="branch-actions-{{ $branch->id }}"
                                                class="btn-group btn-group-sm border shadow-sm rounded">
                                                <button type="button" class="btn btn-white"
                                                    onclick="toggleEditBranch({{ $branch->id }}, true)"
                                                    title="แก้ไขสาขา">
                                                    <i class="bi bi-pencil text-warning"></i>
                                                </button>
                                                <form action="{{ route('admin.branches.destroy', $branch->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('ยืนยันลบสาขา {{ $branch->name }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-white" title="ลบสาขา">
                                                        <i class="bi bi-trash text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="branch-edit-row-{{ $branch->id }}" class="d-none table-warning">
                                        <td colspan="3" class="p-2">
                                            <form action="{{ route('admin.branches.update', $branch->id) }}"
                                                method="POST" class="m-0">
                                                @csrf
                                                @method('PATCH')
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-md-4">
                                                        <input type="text" name="name"
                                                            class="form-control form-control-sm bg-white"
                                                            value="{{ $branch->name }}" required>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" name="location"
                                                            class="form-control form-control-sm bg-white"
                                                            value="{{ $branch->location }}">
                                                    </div>
                                                    <div class="col-md-3 text-end">
                                                        <button type="submit"
                                                            class="btn btn-success btn-sm py-1 px-2">บันทึก</button>
                                                        <button type="button" class="btn btn-secondary btn-sm py-1 px-2"
                                                            onclick="toggleEditBranch({{ $branch->id }}, false)">ยกเลิก</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-muted">ยังไม่มีข้อมูลสาขา</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal สำหรับเพิ่มพนักงานใหม่ --}}
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('admin.employees.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">ลงทะเบียนพนักงานใหม่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">ชื่อ-นามสกุล</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">อีเมล</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">รหัสผ่าน</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">แผนก</label>
                            <select name="department" class="form-select" required>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">สาขา</label>
                            <select name="branch" class="form-select" required>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            {{-- ส่วนนี้คือเป้าหมายที่ถูกเปลี่ยนใหม่ทั้งหมด --}}
                            <label class="form-label small fw-bold">ระดับสิทธิ์ (Role)</label>
                            <select name="role" class="form-select" required>
                                <option value="Employee">พนักงานทั่วไป (Employee)</option>
                                <option value="Manager">ผู้จัดการ / หัวหน้างาน (Manager)</option>
                                <option value="HR Manager">ฝ่ายบุคคล (HR Manager)</option>
                                <option value="Director">ผู้อำนวยการฝ่าย (Director)</option>
                                <option value="CEO">ผู้บริหารสูงสุด (CEO)</option>
                                <option value="Super Admin">ผู้ดูแลระบบ (Super Admin)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- สคริปต์ควบคุมการทำงานต่างๆ ภายในหน้า --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. ตรรกะของหน้าต่าง Single Modal สำหรับแก้ไขข้อมูลพนักงาน (Edit Employee)
            const editModal = document.getElementById('editEmployeeModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');
                    const email = button.getAttribute('data-email');
                    const department = button.getAttribute('data-department');
                    const branch = button.getAttribute('data-branch');
                    const role = button.getAttribute('data-role');

                    // เปลี่ยนเป้าหมายการ Submit Form
                    document.getElementById('editEmployeeForm').action = `/admin/employees/${id}`;
                    document.getElementById('editEmployeeModalLabel').textContent = `แก้ไขข้อมูล: ${name}`;

                    // ดึงข้อมูลมาแสดงใน Input ต่างๆ
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_email').value = email;

                    const deptSelect = document.getElementById('edit_department');
                    if (deptSelect) deptSelect.value = department;

                    const branchSelect = document.getElementById('edit_branch');
                    if (branchSelect) branchSelect.value = branch;

                    // สั่งให้ Select เลื่อนไปหา Option ที่มี value ตรงกับ currentRole
                    const roleSelect = document.getElementById('edit_role');
                    if (roleSelect && role) {
                        // ปรับแก้กรณีสิทธิ์เก่าถูกเก็บเป็น String (เช่น กรณีมีหลายสิทธิ์) เพื่อป้องกัน Error เราจะเลือกอันแรกแทน
                        const primaryRole = role.split(',')[0].trim();
                        roleSelect.value = primaryRole;
                    }

                    // เคลียร์ค่ารหัสผ่านใหม่ทุกครั้งที่เปิด Modal
                    const passwordField = document.getElementById('edit_password');
                    const eyeIcon = document.getElementById('edit_eyeIcon');
                    if (passwordField && eyeIcon) {
                        passwordField.value = '';
                        passwordField.type = 'password';
                        eyeIcon.classList.remove('bi-eye');
                        eyeIcon.classList.add('bi-eye-slash');
                    }
                });
            }

            // 2. ระบบสลับตา (ดูรหัสผ่าน) สำหรับ Single Edit Modal
            const toggleEditPasswordBtn = document.getElementById('toggleEditPasswordBtn');
            if (toggleEditPasswordBtn) {
                toggleEditPasswordBtn.addEventListener('click', function() {
                    const passwordField = document.getElementById('edit_password');
                    const eyeIcon = document.getElementById('edit_eyeIcon');

                    if (passwordField && eyeIcon) {
                        if (passwordField.type === 'password') {
                            passwordField.type = 'text';
                            eyeIcon.classList.remove('bi-eye-slash');
                            eyeIcon.classList.add('bi-eye');
                        } else {
                            passwordField.type = 'password';
                            eyeIcon.classList.remove('bi-eye');
                            eyeIcon.classList.add('bi-eye-slash');
                        }
                    }
                });
            }
        });

        // ฟังก์ชัน Toggle แผนก/สาขา แบบ Inline
        function toggleEditDept(id, isEditing) {
            const textEl = document.getElementById(`dept-text-${id}`);
            const formEl = document.getElementById(`dept-form-${id}`);
            const actionsEl = document.getElementById(`dept-actions-${id}`);

            if (isEditing) {
                textEl.classList.add('d-none');
                formEl.classList.remove('d-none');
                actionsEl.classList.add('d-none');
            } else {
                textEl.classList.remove('d-none');
                formEl.classList.add('d-none');
                actionsEl.classList.remove('d-none');
            }
        }

        function toggleEditBranch(id, isEditing) {
            const nameEl = document.getElementById(`branch-name-${id}`);
            const locEl = document.getElementById(`branch-loc-${id}`);
            const actionsEl = document.getElementById(`branch-actions-${id}`);
            const editRowEl = document.getElementById(`branch-edit-row-${id}`);

            if (isEditing) {
                nameEl.classList.add('d-none');
                if (locEl) locEl.classList.add('d-none');
                actionsEl.classList.add('d-none');
                editRowEl.classList.remove('d-none');
            } else {
                nameEl.classList.remove('d-none');
                if (locEl) locEl.classList.remove('d-none');
                actionsEl.classList.remove('d-none');
                editRowEl.classList.add('d-none');
            }
        }
    </script>
@endsection
