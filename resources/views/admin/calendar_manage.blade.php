@extends('layouts.ess')
@section('title', 'จัดการปฏิทินและวันหยุด')

@section('content')
    @php
        $departments = \App\Models\Department::all();
    @endphp

    {{-- นำเข้าฟอนต์ Sarabun และกำหนด Style --}}
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

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/th.js"></script>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">จัดการปฏิทินวันหยุดและกิจกรรม</h1>

        {{-- 🔒 ซ่อนปุ่มเพิ่มกิจกรรมหากไม่ใช่ผู้จัดการ หรือผู้ดูแลระบบ --}}
        @if (in_array(Auth::user()->role, ['Manager', 'HR Manager', 'Super Admin']))
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#eventModal"
                onclick="openAddModal()">
                <i class="bi bi-plus-lg me-2"></i>เพิ่มกิจกรรมใหม่
            </button>
        @endif
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div id="admin-calendar"></div>
        </div>
    </div>

    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalTitle">เพิ่มกิจกรรมใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <input type="hidden" id="eventId">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">หัวข้อกิจกรรม/วันหยุด <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="eventTitle" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">วันที่เริ่มต้น <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="eventStart" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">ถึงวันที่ (ถ้ามี)</label>
                                <input type="date" class="form-control" id="eventEnd">
                            </div>
                        </div>

                        {{-- จุดที่ 2: เพิ่ม Dropdown เลือกแผนก --}}
                        <div class="mb-3">
                            <label for="eventDepartment" class="form-label small fw-bold">แผนกที่สามารถมองเห็นได้</label>
                            <select class="form-select" id="eventDepartment" name="target_department">
                                <option value="ทั้งหมด">ทั้งหมด (ทุกแผนกเห็นได้)</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">รายละเอียด (ถ้ามี)</label>
                            <textarea class="form-control" id="eventDescription" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">ประเภท (สี)</label>
                            <select class="form-select" id="eventColor">
                                <option value="#dc3545">วันหยุด (สีแดง)</option>
                                <option value="#0d6efd">กิจกรรมบริษัท (สีน้ำเงิน)</option>
                                <option value="#198754">วันสำคัญ (สีเขียว)</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="modal-footer border-0">
                    @if (in_array(Auth::user()->role, ['Manager', 'HR Manager', 'Super Admin']))
                        <button type="button" class="btn btn-danger me-auto" id="btnDelete" style="display: none;"
                            onclick="deleteEvent()">ลบกิจกรรม</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="button" class="btn btn-primary" onclick="saveEvent()">บันทึกข้อมูล</button>
                    @else
                        <span class="text-muted small me-auto"><i class="bi bi-shield-lock"></i> โหมดอ่านอย่างเดียว</span>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        let calendar;
        let eventModal;

        document.addEventListener('DOMContentLoaded', function() {
            eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
            var calendarEl = document.getElementById('admin-calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'th',
                height: 650,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                events: "{{ route('admin.calendar.events') }}",
                selectable: true,
                dateClick: function(info) {
                    openAddModal(info.dateStr);
                },
                eventClick: function(info) {
                    openEditModal(info.event);
                }
            });
            calendar.render();

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('date')) {
                openAddModal(urlParams.get('date'));
            }
        });

        // จุดที่ 3.1: แก้ไขฟังก์ชัน openAddModal
        function openAddModal(dateStr = '') {
            document.getElementById('modalTitle').innerText = 'เพิ่มกิจกรรมใหม่';
            document.getElementById('eventForm').reset();
            document.getElementById('eventId').value = '';
            document.getElementById('eventDepartment').value = 'ทั้งหมด'; // รีเซ็ตเป็น 'ทั้งหมด'

            if (dateStr) document.getElementById('eventStart').value = dateStr;

            let btnDelete = document.getElementById('btnDelete');
            if (btnDelete) btnDelete.style.display = 'none';

            eventModal.show();
        }

        // จุดที่ 3.2: แก้ไขฟังก์ชัน openEditModal
        function openEditModal(event) {
            document.getElementById('modalTitle').innerText = 'แก้ไขกิจกรรม';
            document.getElementById('eventId').value = event.id;
            document.getElementById('eventTitle').value = event.title;
            document.getElementById('eventStart').value = event.startStr.split('T')[0];
            document.getElementById('eventEnd').value = event.endStr ? event.endStr.split('T')[0] : '';
            document.getElementById('eventColor').value = event.backgroundColor;
            document.getElementById('eventDescription').value = event.extendedProps.description || '';

            // เซ็ตค่าแผนกที่ดึงมาจากฐานข้อมูล (ถ้าไม่มีให้เลือก 'ทั้งหมด')
            document.getElementById('eventDepartment').value = event.extendedProps.target_department || 'ทั้งหมด';

            let btnDelete = document.getElementById('btnDelete');
            if (btnDelete) btnDelete.style.display = 'block';

            eventModal.show();
        }

        // จุดที่ 3.3: แก้ไขฟังก์ชันบันทึก (saveEvent)
        function saveEvent() {
            const id = document.getElementById('eventId').value;
            const data = {
                title: document.getElementById('eventTitle').value,
                start: document.getElementById('eventStart').value,
                end: document.getElementById('eventEnd').value || null,
                color: document.getElementById('eventColor').value,
                description: document.getElementById('eventDescription').value,
                target_department: document.getElementById('eventDepartment').value // เพิ่มค่าแผนก
            };

            if (!data.title || !data.start) {
                alert('กรุณากรอกหัวข้อและวันที่เริ่มต้น');
                return;
            }

            const url = id ? `/admin/calendar/events/${id}` : "/admin/calendar/events";
            const method = id ? "PUT" : "POST";

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(data)
                })
                .then(res => {
                    if (!res.ok) throw new Error('เกิดข้อผิดพลาดจากเซิร์ฟเวอร์');
                    return res.json();
                })
                .then(() => {
                    calendar.refetchEvents();
                    eventModal.hide();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                });
        }

        function deleteEvent() {
            const id = document.getElementById('eventId').value;
            if (confirm('ยืนยันการลบกิจกรรมนี้ออกจากระบบ?')) {
                fetch(`/admin/calendar/events/${id}`, {
                        method: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('เกิดข้อผิดพลาดจากเซิร์ฟเวอร์');
                        calendar.refetchEvents();
                        eventModal.hide();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('เกิดข้อผิดพลาดในการลบข้อมูล');
                    });
            }
        }
    </script>
@endsection
