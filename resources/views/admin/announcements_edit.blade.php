@extends('layouts.ess')
@section('title', 'แก้ไขประกาศ')

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

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square text-warning me-2"></i>แก้ไขประกาศ</h4>
                    </div>
                    <div class="card-body p-4">
                        {{-- Form ชี้ไปที่ Route อัปเดต และใช้ Method PUT --}}
                        <form action="{{ route('admin.announcements.update', $post->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label small fw-bold">หัวข้อประกาศ</label>
                                <input type="text" name="title" class="form-control" value="{{ $post->title }}"
                                    required>
                            </div>

                            {{-- ส่วนการคัดกรอง สาขา และ แผนก ของประกาศ --}}
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-primary"><i class="bi bi-geo-alt-fill"></i>
                                        กำหนดสาขาที่จะให้เห็นโพส</label>
                                    <select name="target_branch" class="form-select border-primary" required>
                                        <option value="ทั้งหมด" {{ $post->target_branch == 'ทั้งหมด' ? 'selected' : '' }}>
                                            ทุกสาขา (เห็นทั้งหมด)</option>
                                        @foreach (\App\Models\Branch::all() as $branch)
                                            <option value="{{ $branch->name }}"
                                                {{ $post->target_branch == $branch->name ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-primary"><i class="bi bi-building"></i>
                                        กำหนดแผนกที่จะให้เห็นโพส</label>
                                    <select name="target_department" class="form-select border-primary" required>
                                        <option value="ทั้งหมด"
                                            {{ $post->target_department == 'ทั้งหมด' ? 'selected' : '' }}>ทุกแผนก
                                            (เห็นทั้งหมด)</option>
                                        <option value="HR" {{ $post->target_department == 'HR' ? 'selected' : '' }}>
                                            ทรัพยากรบุคคล (HR)</option>
                                        <option value="IT" {{ $post->target_department == 'IT' ? 'selected' : '' }}>
                                            เทคโนโลยีสารสนเทศ (IT)</option>
                                        <option value="Accounting"
                                            {{ $post->target_department == 'Accounting' ? 'selected' : '' }}>บัญชีและการเงิน
                                            (Accounting)</option>
                                        <option value="Marketing"
                                            {{ $post->target_department == 'Marketing' ? 'selected' : '' }}>การตลาด
                                            (Marketing)</option>
                                        <option value="Sales" {{ $post->target_department == 'Sales' ? 'selected' : '' }}>
                                            ฝ่ายขาย (Sales)</option>
                                        <option value="Operation"
                                            {{ $post->target_department == 'Operation' ? 'selected' : '' }}>ฝ่ายปฏิบัติการ
                                            (Operation)</option>
                                        <option value="General Admin"
                                            {{ $post->target_department == 'General Admin' ? 'selected' : '' }}>
                                            ธุรการทั่วไป (General Admin)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">หมวดหมู่</label>
                                <select name="category" class="form-select" required>
                                    <option value="primary" {{ $post->category == 'primary' ? 'selected' : '' }}>
                                        ข่าวสารทั่วไป (สีฟ้า)</option>
                                    <option value="success" {{ $post->category == 'success' ? 'selected' : '' }}>
                                        ประกาศสำคัญ (สีเขียว)</option>
                                    <option value="danger" {{ $post->category == 'danger' ? 'selected' : '' }}>
                                        เรื่องด่วนมาก (สีแดง)</option>
                                    <option value="warning" {{ $post->category == 'warning' ? 'selected' : '' }}>คำเตือน
                                        (สีเหลือง)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">แนบไฟล์ (รูปภาพ, PDF, Word, Excel)</label>

                                {{-- แสดงไฟล์เดิมถ้ามี --}}
                                @if ($post->image)
                                    <div class="alert alert-secondary small py-2 mb-2">
                                        <i class="bi bi-paperclip me-1"></i> ไฟล์แนบปัจจุบัน:
                                        <a href="{{ asset('uploads/announcements/' . $post->image) }}" target="_blank"
                                            class="fw-bold">{{ $post->image }}</a>
                                    </div>
                                @endif

                                <input type="file" name="image" class="form-control"
                                    accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">
                                <small class="text-muted">อัปโหลดไฟล์ใหม่เฉพาะเมื่อต้องการเปลี่ยนไฟล์แนบเดิม</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">เนื้อหาประกาศ</label>
                                <textarea name="content" class="form-control" rows="6" placeholder="เขียนรายละเอียดที่นี่..." required>{{ $post->content }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('welcome') }}" class="btn btn-light px-4">ยกเลิก</a>
                                <button type="submit" class="btn btn-warning px-4 shadow-sm">บันทึกการแก้ไข</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
