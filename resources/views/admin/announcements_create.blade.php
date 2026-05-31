@extends('layouts.ess')

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
        <div class="card border-0 shadow-sm col-md-8 mx-auto">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4">สร้างประกาศใหม่</h4>

                <form action="{{ route('admin.announcements.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">หัวข้อประกาศ</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ชื่อผู้ประกาศ / ฝ่าย</label>
                        <input type="text" name="author" class="form-control" value="HR Announcement" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ประเภท (สีประกาศ)</label>
                        <select name="category" class="form-select">
                            <option value="success">สีเขียว (ข่าวทั่วไป)</option>
                            <option value="primary">สีน้ำเงิน (กิจกรรม)</option>
                            <option value="danger">สีแดง (ประกาศด่วน/สำคัญ)</option>
                            <option value="warning">สีเหลือง (แจ้งเตือน)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">เนื้อหาประกาศ</label>
                        <textarea name="content" class="form-control" rows="6" required></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2">ลงประกาศทันที</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
