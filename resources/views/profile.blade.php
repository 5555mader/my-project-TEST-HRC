@extends('layouts.ess')

@section('title', 'ข้อมูลส่วนตัว')

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

    <div class="container py-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-4 mb-4">
                    {{-- ส่วนแสดงรูปโปรไฟล์ทางด้านซ้าย (อัปเดตใหม่ใช้ asset) --}}
                    <div class="mb-3">
                        @if (Auth::user()->image && file_exists(public_path('uploads/profiles/' . Auth::user()->image)))
                            {{-- ใช้ฟังก์ชัน asset วิ่งไปหาที่โฟลเดอร์ public/uploads/profiles โดยตรง --}}
                            <img src="{{ asset('uploads/profiles/' . Auth::user()->image) }}"
                                class="rounded-circle img-thumbnail shadow-sm" width="120" height="120"
                                style="object-fit: cover;" alt="Profile">
                        @else
                            {{-- รูปเริ่มต้นกรณีไม่มีข้อมูลภาพ หรือไม่พบไฟล์จริงบน Server --}}
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=128&background=random"
                                class="rounded-circle img-thumbnail shadow-sm" width="120" alt="Profile">
                        @endif
                    </div>
                    <h5 class="fw-bold mb-0">{{ Auth::user()->name }}</h5>
                    <p class="text-muted small">รหัสพนักงาน: EMP{{ str_pad(Auth::user()->id, 3, '0', STR_PAD_LEFT) }}</p>

                    {{-- แก้ไขส่วนแสดงแผนกและเพิ่ม Role --}}
                    <div class="d-flex flex-column align-items-center gap-1 mt-2">
                        {{-- ป้ายชื่อแผนก (สีเดิม) --}}
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 rounded-pill w-auto">
                            {{ Auth::user()->department ?? 'ไม่ได้ระบุแผนก' }}
                        </span>

                        {{-- ป้ายชื่อบทบาท Role (เพิ่มใหม่) --}}
                        <span class="badge bg-secondary px-3 rounded-pill w-auto">
                            บทบาท: {{ Auth::user()->role ?? 'พนักงานทั่วไป' }}
                        </span>
                    </div>

                </div>

                <div class="card border-0 shadow-sm p-3">
                    <h6 class="fw-bold mb-3">การตั้งค่าบัญชี</h6>
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action border-0 px-0 small">
                            <i class="bi bi-shield-lock me-2"></i> เปลี่ยนรหัสผ่าน
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit"
                                class="list-group-item list-group-item-action border-0 px-0 small text-danger bg-transparent">
                                <i class="bi bi-box-arrow-right me-2"></i> ออกจากระบบ
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">ข้อมูลพนักงานทั่วไป</h6>
                    </div>
                    <div class="card-body">
                        {{-- แสดงข้อความแจ้งเตือนเมื่อบันทึกสำเร็จ --}}
                        @if (session('success'))
                            <div class="alert alert-success border-0 shadow-sm mb-3">
                                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        {{-- ฟอร์มแก้ไขข้อมูล --}}
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH') {{-- ต้องใช้ PATCH ให้ตรงกับ Route --}}

                            <div class="row g-3">
                                {{-- ช่องสำหรับเลือกไฟล์รูปโปรไฟล์ใหม่ --}}
                                <div class="col-md-12 mb-2">
                                    <label class="form-label small fw-bold">เปลี่ยนรูปโปรไฟล์</label>
                                    <input type="file" name="image" class="form-control form-control-sm"
                                        accept="image/*">
                                    <small class="text-muted">รองรับไฟล์ JPG, PNG, GIF (ไม่เกิน 2MB)</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">ชื่อ-นามสกุล</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ Auth::user()->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">อีเมลพนักงาน</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ Auth::user()->email }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">เบอร์โทรศัพท์</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ Auth::user()->phone }}" placeholder="08X-XXX-XXXX">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">ตำแหน่งงาน/แผนก</label>
                                    <select name="department" class="form-select">
                                        <option value="">-- เลือกแผนก --</option>
                                        {{-- ลูปแสดงแผนกแบบไดนามิกจากฐานข้อมูล --}}
                                        @foreach ($departments ?? [] as $dept)
                                            <option value="{{ $dept->name }}"
                                                {{ Auth::user()->department == $dept->name ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- ส่วนที่เพิ่มใหม่: ที่อยู่ปัจจุบัน --}}
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold">ที่อยู่ปัจจุบัน</label>
                                    <textarea name="address" class="form-control" rows="2" placeholder="ระบุที่อยู่สำหรับการติดต่อ...">{{ Auth::user()->address }}</textarea>
                                </div>

                                {{-- ส่วนการเลือกสาขาแบบ Dynamic --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">สาขาที่ปฏิบัติงาน</label>
                                        <select name="branch" class="form-select">
                                            <option value="">-- กรุณาเลือกสาขา --</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->name }}"
                                                    {{ Auth::user()->branch == $branch->name ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- 🌟 🌟 เพิ่มโซนอัปโหลดรูปลายเซ็นตรงนี้ 🌟 🌟 --}}
                                <div class="col-md-12 mt-3">
                                    <label class="form-label fw-bold small text-primary">
                                        <i class="bi bi-pen me-1"></i> ลายเซ็นอิเล็กทรอนิกส์ (สำหรับประทับลงในเอกสาร)
                                    </label>

                                    {{-- แสดงรูปลายเซ็นเดิม (ถ้ามี) --}}
                                    @if (Auth::user()->signature && file_exists(public_path('uploads/signatures/' . Auth::user()->signature)))
                                        <div class="mb-3 p-3 bg-light border border-dashed rounded text-center">
                                            <img src="{{ asset('uploads/signatures/' . Auth::user()->signature) }}"
                                                alt="Signature" class="img-fluid" style="max-height: 80px;">
                                        </div>
                                    @endif

                                    <input type="file" name="signature" class="form-control"
                                        accept="image/png, image/jpeg, image/jpg">
                                    <small class="text-muted">* แนะนำให้ใช้ภาพพื้นหลังโปร่งใส (.png)
                                        เพื่อความสวยงามเมื่อวางทาบลงบนหน้าเอกสาร</small>
                                </div>
                                {{-- 🌟 🌟 สิ้นสุดโซนลายเซ็น 🌟 🌟 --}}

                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" class="btn btn-primary px-4">บันทึกข้อมูล</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
