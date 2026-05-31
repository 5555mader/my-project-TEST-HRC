@extends('layouts.ess')

@section('title', 'Time Attendance')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Time Attendance</h1>
        <div class="text-muted" id="current-time">
            <i class="bi bi-clock me-1"></i> กำลังโหลดเวลา...
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-5">
                    <h5 class="card-title mb-4">ลงเวลาปฏิบัติงาน</h5>

                    <div class="mb-4">
                        <div class="display-4 fw-bold text-primary" id="live-clock">00:00:00</div>
                        <p class="text-muted">{{ date('d F Y') }}</p>
                    </div>

                    {{-- แสดงข้อความแจ้งเตือน --}}
                    @if (session('success'))
                        <div class="alert alert-success small py-2"><i class="bi bi-check-circle"></i>
                            {{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger small py-2"><i class="bi bi-x-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div class="d-grid gap-3">
                        {{-- ถ้ายังไม่เคย Check-in ในวันนี้ --}}
                        @if (!$todayAttendance)
                            <form action="{{ route('ess.attendance.checkin') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg py-3 shadow-sm hover-scale w-100">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Check-in (เข้างาน)
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger btn-lg py-3 shadow-sm w-100" disabled>
                                <i class="bi bi-box-arrow-left me-2"></i> Check-out (ออกงาน)
                            </button>

                            {{-- ถ้า Check-in แล้ว แต่ยังไม่ได้ Check-out --}}
                        @elseif($todayAttendance && !$todayAttendance->check_out)
                            <button type="button" class="btn btn-success btn-lg py-3 shadow-sm w-100" disabled>
                                <i class="bi bi-check2-circle me-2"></i> เข้างานแล้ว
                                ({{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }})
                            </button>
                            <form action="{{ route('ess.attendance.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-lg py-3 shadow-sm hover-scale w-100">
                                    <i class="bi bi-box-arrow-left me-2"></i> Check-out (ออกงาน)
                                </button>
                            </form>

                            {{-- ถ้าลงเวลาครบทั้งเข้าและออกแล้ว --}}
                        @else
                            <button type="button" class="btn btn-success btn-lg py-3 shadow-sm w-100" disabled>
                                <i class="bi bi-check2-circle me-2"></i> เข้างานแล้ว
                                ({{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }})
                            </button>
                            <button type="button" class="btn btn-secondary btn-lg py-3 shadow-sm w-100" disabled>
                                <i class="bi bi-check2-all me-2"></i> ออกงานแล้ว
                                ({{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') }})
                            </button>
                        @endif
                    </div>

                    <p class="mt-4 mb-0 text-muted small">
                        <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                        สถานที่: สำนักงานใหญ่ (อาคาร A)
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">สรุปเวลาทำงานเดือนนี้</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h3 class="mb-0 text-success">20</h3>
                                <small class="text-muted">วันทำงาน</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h3 class="mb-0 text-warning">2</h3>
                                <small class="text-muted">มาสาย</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h3 class="mb-0 text-danger">0</h3>
                                <small class="text-muted">ขาดงาน</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>ประกาศล่าสุดจาก HR</h6>
                        <div class="alert alert-info border-0 shadow-sm small">
                            <i class="bi bi-info-circle me-2"></i>
                            พรุ่งนี้มีกิจกรรม Big Cleaning Day ทุกคนกรุณาลงเวลาเข้างานก่อน 08:30 น.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">ประวัติการเข้างานล่าสุด (7 วัน)</h5>
            <button class="btn btn-sm btn-outline-secondary">ดูทั้งหมด</button>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle">
                <thead class="table-light text-muted small">
                    <tr>
                        <th>วันที่</th>
                        <th>เข้างาน</th>
                        <th>ออกงาน</th>
                        <th>ชั่วโมงทำงาน</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($history as $record)
                        <tr>
                            {{-- แสดงวันที่ (แปลงเป็นรูปแบบไทย) --}}
                            <td>{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>

                            {{-- แสดงเวลาเข้างาน --}}
                            <td>{{ $record->check_in ? \Carbon\Carbon::parse($record->check_in)->format('H:i') : '-' }} น.
                            </td>

                            {{-- แสดงเวลาออกงาน --}}
                            <td>{{ $record->check_out ? \Carbon\Carbon::parse($record->check_out)->format('H:i') : '-' }}
                                น.</td>

                            {{-- คำนวณชั่วโมงทำงาน --}}
                            <td>
                                @if ($record->check_in && $record->check_out)
                                    @php
                                        $start = \Carbon\Carbon::parse($record->check_in);
                                        $end = \Carbon\Carbon::parse($record->check_out);
                                        $hours = $start->diffInHours($end);
                                        $minutes = $start->diffInMinutes($end) % 60;
                                    @endphp
                                    {{ $hours }} ชม. {{ $minutes }} นาที
                                @else
                                    -
                                @endif
                            </td>

                            {{-- แสดงสถานะพร้อมสี Badge --}}
                            <td>
                                @if ($record->status == 'ปกติ')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3">ปกติ</span>
                                @elseif($record->status == 'มาสาย')
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3">มาสาย</span>
                                @else
                                    <span
                                        class="badge bg-secondary bg-opacity-10 text-secondary px-3">{{ $record->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        {{-- กรณีที่ยังไม่มีข้อมูลการเข้างาน --}}
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">ไม่พบประวัติการเข้างานในขณะนี้</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('th-TH');
            document.getElementById('live-clock').innerText = timeString;
            document.getElementById('current-time').innerText = 'เวลาปัจจุบัน: ' + timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    <style>
        .hover-scale:hover {
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }
    </style>
@endsection
