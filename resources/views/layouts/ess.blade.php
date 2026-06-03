<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESS Portal - @yield('title')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --sidebar-bg: #f26822;
            --sidebar-hover: #d95a1a;
            --accent-color: #ffffff;
            --text-muted: #ffdcc8;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
            color: #212529;
        }

        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-bg);
            color: white;
            transition: all 0.3s;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            flex-direction: column;
        }

        .sidebar h5 {
            letter-spacing: 1px;
            font-weight: 700;
            color: var(--accent-color);
        }

        .nav-link {
            color: var(--text-muted);
            padding: 0.8rem 1.25rem;
            border-radius: 8px;
            margin: 0.1rem 1rem;
            transition: all 0.2s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        .nav-link:hover {
            color: var(--sidebar-bg);
            background: var(--accent-color);
        }

        .nav-link.active {
            color: var(--sidebar-bg);
            background: var(--accent-color);
            font-weight: 700;
        }

        .menu-header {
            padding: 0.8rem 1.25rem;
            margin: 0.2rem 1rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 1px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px;
        }

        .menu-header:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .menu-header[aria-expanded="true"] i.arrow {
            transform: rotate(180deg);
        }

        .arrow {
            transition: transform 0.2s;
            font-size: 0.8rem;
        }

        hr.divider {
            margin: 1rem 1.5rem;
            border-color: rgba(255, 255, 255, 0.3);
        }

        main {
            background-color: #f8f9fa;
        }

        .bg-soft-orange {
            background-color: #ffe8d6;
        }

        .text-orange {
            color: #f26822;
        }

        .notif-item.unread {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar py-4 shadow-sm">
                <div class="text-center mb-4">
                    <h5 class="mb-3 px-3">
                        <i class="bi bi-cpu-fill me-2"></i>HRC I'D DRIVE SYSTEM
                    </h5>

                    {{-- กระดิ่งแจ้งเตือน --}}
                    <div class="dropdown" id="notificationBell">
                        <a class="nav-link position-relative d-inline-block p-0" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-4 text-white"></i>
                            <span id="notif-badge"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"
                                style="display: none; font-size: 0.6rem;">
                                0
                            </span>
                        </a>
                        <div class="dropdown-menu shadow border-0 py-0 mt-3"
                            style="width: 320px; overflow: hidden; border-radius: 12px; position: absolute; left: 50%; transform: translateX(-50%);">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                                <h6 class="mb-0 fw-bold text-dark">การแจ้งเตือน</h6>
                                <span class="badge bg-soft-orange text-orange small" id="notif-count-text">0
                                    รายการใหม่</span>
                            </div>

                            <div id="notif-list" style="max-height: 350px; overflow-y: auto;">
                                <div class="p-4 text-center text-muted">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                </div>
                            </div>

                            <div class="p-2 border-top bg-light d-flex justify-content-around">
                                <button onclick="markAllRead()"
                                    class="btn btn-link btn-sm text-decoration-none text-dark fw-bold small">อ่านทั้งหมด</button>
                                <button onclick="clearAllNotif()"
                                    class="btn btn-link btn-sm text-decoration-none text-danger fw-bold small">ล้างทั้งหมด</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-grow-1 overflow-y-auto">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('welcome') }}">
                                <i class="bi bi-house-door me-3"></i> HR Center (HRC)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('ess.profile') ? 'active' : '' }}"
                                href="{{ route('ess.profile') }}">
                                <i class="bi bi-person-circle me-3"></i> My Profile
                            </a>
                        </li>

                        <hr class="divider">

                        @auth
                            {{-- 1. กลุ่ม: เอกสาร --}}
                            <div class="menu-header" data-bs-toggle="collapse" data-bs-target="#adminMenu"
                                aria-expanded="{{ request()->routeIs('admin.documents') || request()->routeIs('admin.archives') ? 'true' : 'false' }}">
                                <span>เอกสาร</span>
                                <i class="bi bi-chevron-down arrow"></i>
                            </div>
                            <div class="collapse {{ request()->routeIs('admin.documents') || request()->routeIs('admin.archives') ? 'show' : '' }}"
                                id="adminMenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.documents') ? 'active' : '' }}"
                                        href="{{ route('admin.documents') }}">
                                        <i class="bi bi-folder2-open me-3"></i> แบบฟอร์มและเอกสาร
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.archives') ? 'active' : '' }}"
                                        href="{{ route('admin.archives') }}">
                                        <i class="bi bi-archive me-3"></i> บันทึกภายใน
                                    </a>
                                </li>
                            </div>

                            {{-- 2. กลุ่ม: เมนูพนักงาน --}}
                            <div class="menu-header" data-bs-toggle="collapse" data-bs-target="#employeeMenu"
                                aria-expanded="{{ request()->is('ess*') ? 'true' : 'false' }}">
                                <span>เมนูพนักงาน</span>
                                <i class="bi bi-chevron-down arrow"></i>
                            </div>
                            <div class="collapse {{ request()->is('ess*') ? 'show' : '' }}" id="employeeMenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('ess.dashboard') ? 'active' : '' }}"
                                        href="{{ route('ess.dashboard') }}">
                                        <i class="bi bi-grid-1x2 me-3"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('ess.attendance') ? 'active' : '' }}"
                                        href="{{ route('ess.attendance') }}">
                                        <i class="bi bi-clock me-3"></i> ลงเวลาเข้างาน
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('ess.leave') ? 'active' : '' }}"
                                        href="{{ route('ess.leave') }}">
                                        <i class="bi bi-calendar-check me-3"></i> แจ้งขอลา
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('ess.payslip') ? 'active' : '' }}"
                                        href="{{ route('ess.payslip') }}">
                                        <i class="bi bi-receipt me-3"></i> สลิปเงินเดือน
                                    </a>
                                </li>
                            </div>
                        @endauth

                        {{-- 3. เมนูผู้จัดการ (Manager, HR Payroll, HR Manager, Super Admin, Director, CEO) --}}
                        @if (Auth::check() &&
                                in_array(Auth::user()->role, ['Manager', 'HR Payroll', 'HR Manager', 'Super Admin', 'Director', 'CEO']))
                            <div class="menu-header" data-bs-toggle="collapse" data-bs-target="#managerMenu"
                                aria-expanded="{{ request()->is('manager*') || request()->routeIs('admin.payroll') || request()->routeIs('admin.reports') ? 'true' : 'false' }}">
                                <span>เมนูผู้จัดการ</span>
                                <i class="bi bi-chevron-down arrow"></i>
                            </div>
                            <div class="collapse {{ request()->is('manager*') || request()->routeIs('admin.payroll') || request()->routeIs('admin.reports') ? 'show' : '' }}"
                                id="managerMenu">

                                {{-- 1. คำขอนุมัติ: ทุกสิทธิ์รวมถึง CEO และ ผอ.ฝ่าย (Director) จะมองเห็นเมนูนี้ --}}
                                @if (in_array(Auth::user()->role, ['Manager', 'HR Manager', 'Super Admin', 'Director', 'CEO']))
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('manager.approvals') ? 'active' : '' }}"
                                            href="{{ route('manager.approvals') }}">
                                            <i class="bi bi-check2-square me-3"></i> อนุมัติเอกสาร
                                        </a>
                                    </li>
                                @endif

                                {{-- 2. ภาพรวมพนักงาน: ซ่อนไม่ให้ CEO และ ผอ.ฝ่าย เห็น (แสดงเฉพาะ Manager, HR Payroll, HR Manager, Super Admin) --}}
                                @if (in_array(Auth::user()->role, ['Manager', 'HR Payroll', 'HR Manager', 'Super Admin']))
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('manager.team') ? 'active' : '' }}"
                                            href="{{ route('manager.team') }}">
                                            <i class="bi bi-person-video3 me-3"></i> ภาพรวมพนักงาน
                                        </a>
                                    </li>
                                @endif

                                {{-- 3. ผลงาน: ซ่อนไม่ให้ CEO และ ผอ.ฝ่าย เห็น (แสดงเฉพาะ Manager, HR Manager, Super Admin) --}}
                                @if (in_array(Auth::user()->role, ['Manager', 'HR Manager', 'Super Admin']))
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('manager.review') ? 'active' : '' }}"
                                            href="{{ route('manager.review') }}">
                                            <i class="bi bi-bar-chart-line me-3"></i> ผลงาน
                                        </a>
                                    </li>
                                @endif

                                {{-- 4. รอบการจ่ายเงินเดือน: เฉพาะ Manager, HR Payroll และ Super Admin --}}
                                @if (in_array(Auth::user()->role, ['Manager', 'HR Payroll', 'Super Admin']))
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.payroll') ? 'active' : '' }}"
                                            href="{{ route('admin.payroll') }}">
                                            <i class="bi bi-currency-dollar me-3"></i> รอบการจ่ายเงินเดือน
                                        </a>
                                    </li>
                                @endif

                                {{-- 5. การวิเคราะห์: เห็นทุกคนในกลุ่มผู้จัดการรวมถึง CEO และ ผอ.ฝ่าย --}}
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}"
                                        href="{{ route('admin.reports') }}">
                                        <i class="bi bi-file-earmark-bar-graph me-3"></i>
                                        การวิเคราะห์การทำงานของพนักงาน
                                    </a>
                                </li>
                            </div>
                        @endif

                        {{-- 4. เมนู ADMIN (เฉพาะ Super Admin เท่านั้น) --}}
                        @if (Auth::check() && Auth::user()->role == 'Super Admin')
                            <div class="menu-header" data-bs-toggle="collapse" data-bs-target="#adminSectionMenu"
                                aria-expanded="{{ request()->routeIs('admin.employees') ? 'true' : 'false' }}">
                                <span>ADMIN</span>
                                <i class="bi bi-chevron-down arrow"></i>
                            </div>
                            <div class="collapse {{ request()->routeIs('admin.employees') ? 'show' : '' }}"
                                id="adminSectionMenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.employees') ? 'active' : '' }}"
                                        href="{{ route('admin.employees') }}">
                                        <i class="bi bi-people-fill me-3"></i> การจัดการพนักงาน
                                    </a>
                                </li>
                            </div>
                        @endif
                    </ul>
                </div>

                <div class="mt-auto">
                    <hr class="divider">
                    @auth
                        {{-- ส่วนแสดงรูปโปรไฟล์ของ Sidebar ด้านล่าง (อัปเดตใหม่เพื่อความถูกต้อง) --}}
                        <div class="mb-3 text-center">
                            @if (Auth::user()->image && file_exists(public_path('uploads/profiles/' . Auth::user()->image)))
                                <img src="{{ asset('uploads/profiles/' . Auth::user()->image) }}"
                                    class="rounded-circle img-thumbnail shadow-sm" width="120" height="120"
                                    style="object-fit: cover;" alt="Profile">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=128&background=random"
                                    class="rounded-circle img-thumbnail shadow-sm" width="120" alt="Profile">
                            @endif
                        </div>

                        <div class="px-3 mb-3">
                            <div class="menu-label small text-white mb-1">Logged in as:</div>
                            <div class="fw-bold text-white small mb-3 text-truncate px-3">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="px-2">
                                @csrf
                                <button type="submit"
                                    class="btn btn-light text-danger btn-sm w-100 rounded-pill fw-bold shadow-sm">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="px-3 text-center">
                            <a href="{{ route('login') }}"
                                class="btn btn-accent btn-sm w-75 mb-2 rounded-pill shadow-sm">Login</a>
                        </div>
                    @endauth
                </div>
            </nav>

            <main class="col-md-10 ms-sm-auto px-md-5 py-4">
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function fetchNotifications() {
            fetch("{{ route('notifications.api') }}")
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notif-badge');
                    const list = document.getElementById('notif-list');
                    const countText = document.getElementById('notif-count-text');

                    if (data.unread_count > 0) {
                        badge.innerText = data.unread_count;
                        badge.style.display = 'block';
                        countText.innerText = `${data.unread_count} รายการใหม่`;
                    } else {
                        badge.style.display = 'none';
                        countText.innerText = `ไม่มีรายการใหม่`;
                    }

                    if (data.notifications.length > 0) {
                        list.innerHTML = data.notifications.map(n => `
                        <a href="${n.link}" class="dropdown-item p-3 notif-item ${!n.read_at ? 'unread' : ''}">
                            <div class="d-flex align-items-start">
                                <div class="me-3 mt-1">${getIcon(n.type)}</div>
                                <div class="w-100">
                                    <div class="d-flex justify-content-between">
                                        <small class="fw-bold text-dark">${n.title}</small>
                                        <small class="text-muted" style="font-size: 0.7rem;">${n.created_at}</small>
                                    </div>
                                    <div class="text-muted small text-truncate" style="max-width: 200px;">${n.message}</div>
                                </div>
                            </div>
                        </a>
                    `).join('');
                    } else {
                        list.innerHTML = '<div class="p-4 text-center text-muted small">ไม่มีการแจ้งเตือน</div>';
                    }
                });
        }

        function getIcon(type) {
            const icons = {
                'leave': '<i class="bi bi-calendar-x text-danger"></i>',
                'ot': '<i class="bi bi-clock-history text-warning"></i>',
                'payroll': '<i class="bi bi-cash-stack text-success"></i>',
                'document': '<i class="bi bi-file-earmark-text text-primary"></i>'
            };
            return icons[type] || '<i class="bi bi-info-circle text-info"></i>';
        }

        function markAllRead() {
            fetch("{{ route('notifications.markAllRead') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => fetchNotifications());
        }

        function clearAllNotif() {
            if (confirm('ยืนยันลบการแจ้งเตือนทั้งหมด?')) {
                fetch("{{ route('notifications.clearAll') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => fetchNotifications());
            }
        }

        @auth
        fetchNotifications();
        setInterval(fetchNotifications, 10000);
        @endauth
    </script>
</body>

</html>
