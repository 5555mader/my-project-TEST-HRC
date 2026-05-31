@extends('layouts.ess')

@section('title', 'หน้าแรก - ประกาศจากบริษัท')

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

    {{-- นำเข้าไลบรารี FullCalendar (CSS/JS) --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/th.js"></script>

    <div class="container py-4">
        <div class="row justify-content-center">
            {{-- เปลี่ยนจาก col-md-9 เป็น col-md-8 เพื่อให้สัดส่วนพอดีกับ Sidebar ปฏิทิน --}}
            <div class="col-md-8">

                {{-- แสดงข้อความเมื่อบันทึกสำเร็จ --}}
                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- ส่วนปุ่มสร้างประกาศใหม่ และการค้นหา --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold text-dark mb-0">หน้าหลัก</h4>
                    @auth
                        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal"
                            data-bs-target="#createPostModal">
                            <i class="bi bi-plus-lg me-1"></i> สร้างประกาศใหม่
                        </button>
                    @endauth
                </div>

                {{-- ส่วนการค้นหาและคัดกรอง --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <form action="{{ route('welcome') }}" method="GET" class="row g-2">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0"
                                        placeholder="ค้นหาหัวข้อประกาศ หรือเนื้อหา..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select name="category" class="form-select">
                                    <option value="">ทุกหมวดหมู่</option>
                                    <option value="primary" {{ request('category') == 'primary' ? 'selected' : '' }}>
                                        ข่าวสารทั่วไป (สีฟ้า)</option>
                                    <option value="success" {{ request('category') == 'success' ? 'selected' : '' }}>
                                        ประกาศสำคัญ (สีเขียว)</option>
                                    <option value="danger" {{ request('category') == 'danger' ? 'selected' : '' }}>
                                        เรื่องด่วนมาก (สีแดง)</option>
                                    <option value="warning" {{ request('category') == 'warning' ? 'selected' : '' }}>คำเตือน
                                        (สีเหลือง)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-dark w-100">ค้นหา</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ส่วนแสดงปุ่มล้างการกรองข้อมูล --}}
                @if (request('search') || request('category'))
                    <div class="mb-3 text-end">
                        <a href="{{ route('welcome') }}" class="text-decoration-none small text-muted">
                            <i class="bi bi-x-circle"></i> ล้างการกรองข้อมูล
                        </a>
                    </div>
                @endif

                {{-- วนลูปแสดงรายการประกาศจากฐานข้อมูล --}}
                @forelse ($posts as $post)
                    @php
                        // ค้นหาข้อมูลผู้ใช้งานจากชื่อที่ระบุในคอลัมน์ author
                        $authorUser = \App\Models\User::where('name', $post->author)->first();
                    @endphp

                    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                        <div class="card-header bg-{{ $post->category }} text-white py-3 border-0">
                            <div class="d-flex align-items-center">
                                {{-- ส่วนแสดงรูปโปรไฟล์ของผู้โพสต์ --}}
                                <div class="me-3">
                                    @if ($authorUser && $authorUser->image)
                                        {{-- แสดงรูปจริงหากผู้ใช้มีการอัปโหลดรูปโปรไฟล์ไว้ --}}
                                        <img src="{{ asset('uploads/profiles/' . $authorUser->image) }}"
                                            class="rounded-circle border border-2 border-white shadow-sm" width="45"
                                            height="45" style="object-fit: cover;">
                                    @else
                                        {{-- หากไม่มีรูป ให้ใช้ UI Avatars โดยอิงจากชื่อผู้เขียนในโพสต์ --}}
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author) }}&background=fff"
                                            class="rounded-circle border border-2 border-white me-3 shadow-sm"
                                            width="45" height="45">
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $post->author }}</div>
                                    <small class="opacity-75">โพสต์เมื่อ: {{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4 bg-white">
                            <h5 class="card-title fw-bold mb-3">{{ $post->title }}</h5>

                            {{-- ตรวจสอบและแสดงรูปภาพหรือปุ่มดาวน์โหลดไฟล์เอกสาร --}}
                            @if ($post->image)
                                @php
                                    // หาประเภทของนามสกุลไฟล์
                                    $extension = strtolower(pathinfo($post->image, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp

                                @if ($isImage)
                                    {{-- เพิ่ม cursor: pointer และ onclick เพื่อส่ง URL รูปไปที่ Modal --}}
                                    <img src="{{ asset('uploads/announcements/' . $post->image) }}"
                                        class="img-fluid rounded-3 mb-3 w-100"
                                        style="max-height: 400px; object-fit: cover; cursor: pointer;"
                                        alt="รูปภาพประกอบประกาศ"
                                        onclick="openImageModal('{{ asset('uploads/announcements/' . $post->image) }}')">
                                @else
                                    {{-- ถ้าเป็นไฟล์เอกสาร แสดงเป็นปุ่มดาวน์โหลด --}}
                                    <div class="mb-3 p-3 bg-light rounded border">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-text fs-2 text-primary me-3"></i>
                                            <div>
                                                <div class="fw-bold mb-1">ไฟล์แนบเอกสาร ({{ strtoupper($extension) }})
                                                </div>
                                                <a href="{{ asset('uploads/announcements/' . $post->image) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download me-1"></i> ดาวน์โหลด / เปิดดูไฟล์
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <p class="card-text text-secondary leading-relaxed">
                                {!! nl2br(e($post->content)) !!}
                            </p>

                            {{-- ส่วนปุ่มกดต่างๆ --}}
                            <div class="mt-4 d-flex gap-3 border-top pt-3 align-items-center">

                                {{-- 1. ฟอร์มสำหรับกดถูกใจ (Like) --}}
                                <form action="{{ route('posts.like', $post->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-light btn-sm rounded-pill text-primary">
                                        <i class="bi bi-heart-fill me-1"></i> ถูกใจ
                                        {{-- แสดงจำนวนไลก์ --}}
                                        <span class="badge bg-primary rounded-pill ms-1">{{ $post->likes->count() }}</span>
                                    </button>
                                </form>

                                {{-- 2. ปุ่มสำหรับเปิด/ปิดกล่องแสดงความคิดเห็น (ใช้ Bootstrap Collapse) --}}
                                <button class="btn btn-light btn-sm rounded-pill" data-bs-toggle="collapse"
                                    data-bs-target="#comments-{{ $post->id }}">
                                    <i class="bi bi-chat me-1"></i> ความคิดเห็น
                                    {{-- แสดงจำนวนคอมเมนต์ --}}
                                    <span
                                        class="badge bg-secondary rounded-pill ms-1">{{ $post->comments->count() }}</span>
                                </button>

                                {{-- ปุ่มแก้ไข/ลบ (เฉพาะเจ้าของโพสต์ หรือ Super Admin) --}}
                                @if (Auth::check() && (Auth::user()->name == $post->author || Auth::user()->role == 'Super Admin'))
                                    <div class="ms-auto d-flex gap-2">
                                        <a href="{{ route('admin.announcements.edit', $post->id) }}"
                                            class="btn btn-outline-warning btn-sm rounded-pill">
                                            <i class="bi bi-pencil-square"></i> แก้ไข
                                        </a>

                                        <form action="{{ route('admin.announcements.destroy', $post->id) }}"
                                            method="POST" onsubmit="return confirm('ยืนยันการลบประกาศนี้?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                                <i class="bi bi-trash"></i> ลบ
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            {{-- 3. กล่องแสดงความคิดเห็นและช่องพิมพ์ --}}
                            <div class="collapse mt-3" id="comments-{{ $post->id }}">
                                <div class="card card-body bg-light border-0">

                                    {{-- วนลูปแสดงคอมเมนต์ที่มีอยู่แล้ว --}}
                                    @if ($post->comments->count() > 0)
                                        <div class="mb-3">
                                            @foreach ($post->comments as $comment)
                                                <div class="mb-2 border-bottom pb-2">
                                                    <strong class="text-dark">{{ $comment->author_name }}</strong>
                                                    <small
                                                        class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                                                    <p class="mb-0 small text-secondary mt-1">{{ $comment->comment_text }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted small text-center mb-3">ยังไม่มีความคิดเห็น
                                            เป็นคนแรกที่แสดงความคิดเห็นสิ!</p>
                                    @endif

                                    {{-- ฟอร์มสำหรับพิมพ์คอมเมนต์ใหม่ --}}
                                    @auth
                                        <form action="{{ route('posts.comment', $post->id) }}" method="POST"
                                            class="d-flex gap-2">
                                            @csrf
                                            <input type="text" name="comment_text"
                                                class="form-control form-control-sm border-0 shadow-sm"
                                                placeholder="เขียนความคิดเห็นของคุณ..." required>
                                            <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm">ส่ง</button>
                                        </form>
                                    @else
                                        <div class="alert alert-warning small py-2 mb-0 text-center">
                                            กรุณา <a href="{{ route('login') }}" class="alert-link">เข้าสู่ระบบ</a>
                                            เพื่อแสดงความคิดเห็น
                                        </div>
                                    @endauth
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 card border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                            <p class="text-muted mt-2">ไม่พบประกาศที่ท่านกำลังค้นหา</p>
                            <a href="{{ route('welcome') }}" class="btn btn-link">ล้างการค้นหา</a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Sidebar ใหม่ (ปฏิทิน) --}}
            <div class="col-md-4 d-none d-lg-block">
                <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px; z-index: 1;">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-3"><i class="bi bi-calendar-event text-primary me-2"></i>ปฏิทินวันหยุด &
                            กิจกรรม</h6>
                        <div id="calendar" style="font-size: 0.8rem;"></div>
                        <div class="mt-3 small text-muted d-flex justify-content-center gap-3">
                            <span><i class="bi bi-circle-fill text-danger me-1"></i>วันหยุด</span>
                            <span><i class="bi bi-circle-fill text-primary me-1"></i>กิจกรรมบริษัท</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal สร้างประกาศ --}}
    @auth
        <div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header border-0">
                            <h5 class="fw-bold mb-0">สร้างประกาศใหม่</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            {{-- เริ่มต้น: ส่วนที่นำเข้าใหม่ --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold">หัวข้อประกาศ</label>
                                <input type="text" name="title" class="form-control" required
                                    placeholder="ระบุหัวข้อเรื่อง...">
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-primary">
                                        <i class="bi bi-geo-alt-fill"></i> สาขาเป้าหมายที่จะให้เห็นโพสต์
                                    </label>
                                    <select name="target_branch" class="form-select border-primary" required>
                                        <option value="ทั้งหมด">ทั้งหมด (ทุกสาขาเห็นได้)</option>
                                        @foreach (\App\Models\Branch::all() as $branch)
                                            <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-primary">
                                        <i class="bi bi-diagram-3-fill"></i> แผนกเป้าหมายที่จะให้เห็นโพสต์
                                    </label>
                                    <select name="target_department" class="form-select border-primary" required>
                                        <option value="ทั้งหมด">ทั้งหมด (ทุกแผนกในสาขานั้นเห็นได้)</option>
                                        @foreach (\App\Models\Department::all() as $dept)
                                            <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">ประเภท (สีประกาศ)</label>
                                <select name="category" class="form-select">
                                    <option value="success">สีเขียว (ข่าวทั่วไป)</option>
                                    <option value="primary">สีน้ำเงิน (กิจกรรม)</option>
                                    <option value="danger">สีแดง (ประกาศด่วน/สำคัญ)</option>
                                    <option value="warning">สีเหลือง (แจ้งเตือน)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">แนบไฟล์ภาพหรือเอกสาร</label>
                                <input type="file" name="image" class="form-control" accept="image/*,.pdf,.doc,.docx">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">เนื้อหาประกาศ</label>
                                <textarea name="content" class="form-control" rows="5" required placeholder="พิมพ์รายละเอียดที่นี่..."></textarea>
                            </div>
                            {{-- สิ้นสุด: ส่วนที่นำเข้าใหม่ --}}

                            <input type="hidden" name="author" value="{{ Auth::user()->name }}">
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary">ลงประกาศ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth

    {{-- Modal สำหรับดูรูปภาพขนาดเต็ม --}}
    <div class="modal fade" id="imageViewerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: invert(1); opacity: 1; text-shadow: 0 0 10px rgba(0,0,0,0.5);"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="fullSizeImage" src="" class="img-fluid rounded shadow" alt="Full size image"
                        style="max-height: 90vh;">
                </div>
            </div>
        </div>
    </div>

    {{-- สคริปต์สำหรับจัดการปฏิทินและ Image Viewer --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'th',
                height: 'auto',
                contentHeight: 350,
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: ''
                },
                // ดึงข้อมูลจาก API
                events: "{{ route('admin.calendar.events') }}",

                eventClick: function(info) {
                    alert('กิจกรรม: ' + info.event.title + '\nวันที่: ' + info.event.start
                        .toLocaleDateString('th-TH'));
                },

                // 🔒 เช็คสิทธิ์ก่อนเปลี่ยนหน้าไปเพิ่มกิจกรรม
                dateClick: function(info) {
                    @if (Auth::check() && in_array(Auth::user()->role, ['Manager', 'HR Manager', 'Super Admin']))
                        // เฉพาะผู้จัดการและผู้ดูแลระบบเท่านั้นที่คลิกเพื่อไปหน้าจัดการได้
                        window.location.href = "{{ route('admin.calendar.manage') }}?date=" + info
                            .dateStr;
                    @else
                        // พนักงานทั่วไปหรือผู้ที่ยังไม่ได้เข้าสู่ระบบคลิกแล้วจะแจ้งเตือนแทน
                        alert(
                            'คุณสามารถรับชมปฏิทินกิจกรรมได้เท่านั้น ไม่มีสิทธิ์เพิ่มหรือแก้ไขกิจกรรม'
                            );
                    @endif
                }
            });

            calendar.render();
        });

        {{-- ฟังก์ชันสำหรับเปิดดูรูปเต็ม --}}

        function openImageModal(imageUrl) {
            // เอารูปที่กดไปใส่ใน Modal
            document.getElementById('fullSizeImage').src = imageUrl;
            // สั่งเปิด Modal
            var imageModal = new bootstrap.Modal(document.getElementById('imageViewerModal'));
            imageModal.show();
        }
    </script>

    <style>
        .leading-relaxed {
            line-height: 1.7;
        }

        .card {
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection
