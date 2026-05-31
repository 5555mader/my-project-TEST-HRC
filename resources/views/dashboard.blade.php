@extends('layouts.ess')
@section('title', 'Dashboard - HR Center')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">แผงควบคุม (Dashboard)</h1>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary text-white rounded p-3 me-3">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">วันลาพักร้อนคงเหลือ</h6>
                        {{-- แสดงวันคงเหลือ / โควตาทั้งหมด --}}
                        <h3 class="mb-0">{{ $remaining_leave }} / {{ $total_quota }}</h3>
                        <div class="progress mt-2" style="height: 5px;">
                            {{-- ใช้ตัวแปร $percent เพื่อขยับแถบสีตามจริง --}}
                            <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%"
                                aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">ใช้ไปแล้ว {{ $total_quota - $remaining_leave }} วัน</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 h-100">
                <h6 class="text-muted">เวลาเข้างานวันนี้</h6>

                @if ($todayAttendance)
                    {{-- แสดงเวลาเข้างานจริง --}}
                    <h3 class="text-success">
                        {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}
                    </h3>
                    <span
                        class="badge {{ $todayAttendance->status == 'ปกติ' ? 'bg-success-light text-success' : 'bg-warning-light text-warning' }} w-50">
                        สถานะ: {{ $todayAttendance->status }}
                    </span>
                @else
                    {{-- กรณีที่ยังไม่ได้ลงเวลา --}}
                    <h3 class="text-muted">--:--</h3>
                    <span class="badge bg-light text-muted w-50">สถานะ: ยังไม่ได้เข้างาน</span>
                @endif

            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 h-100">
                <h6 class="text-muted">สวัสดิการที่ใช้ไป</h6>
                <h3 class="text-warning">฿4,500</h3>
                <small class="text-muted">จากวงเงิน ฿15,000</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 h-100">
                <h6 class="text-muted">คะแนนประเมิน (ล่าสุด)</h6>

                @if ($reviewScore > 0)
                    {{-- แสดงคะแนนทศนิยม 1 ตำแหน่ง --}}
                    <h3 class="text-info">{{ number_format($reviewScore, 1) }} / 5.0</h3>
                    <small class="text-muted">
                        อยู่ในระดับ:
                        @if ($reviewScore >= 4.5)
                            <span class="text-success fw-bold">ดีเยี่ยม</span>
                        @elseif($reviewScore >= 3.5)
                            <span class="text-primary fw-bold">ดี</span>
                        @elseif($reviewScore >= 2.5)
                            <span class="text-warning fw-bold">พอใช้</span>
                        @else
                            <span class="text-danger fw-bold">ต้องปรับปรุง</span>
                        @endif
                    </small>
                @else
                    {{-- กรณีที่ยังไม่เคยถูกประเมินเลย --}}
                    <h3 class="text-muted">- / 5.0</h3>
                    <small class="text-muted">ยังไม่มีผลการประเมิน</small>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong><i class="bi bi-megaphone text-danger me-2"></i>ประกาศสำคัญ & เรื่องด่วน</strong>
                    {{-- ลิงก์ไปหน้าแรกที่มีประกาศทั้งหมด --}}
                    <a href="{{ route('welcome') }}" class="btn btn-sm btn-link text-decoration-none">ดูทั้งหมด</a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">

                        {{-- วนลูปแสดงข้อมูลประกาศ --}}
                        @forelse($important_posts as $post)
                            <div class="list-group-item px-0 border-bottom">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <h6 class="mb-1 fw-bold">{{ $post->title }}</h6>
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                </div>

                                {{-- แสดงเนื้อหาแบบย่อ (ตัดแท็ก HTML ออกและจำกัดความยาว) --}}
                                <p class="mb-1 text-muted small text-truncate" style="max-width: 90%;">
                                    {{ strip_tags($post->content) }}
                                </p>

                                {{-- แสดง Badge ตามสีของประกาศ --}}
                                @if ($post->category == 'danger')
                                    <span class="badge bg-danger rounded-pill"><i
                                            class="bi bi-exclamation-triangle me-1"></i> ด่วนมาก</span>
                                @else
                                    <span class="badge bg-success rounded-pill"><i class="bi bi-info-circle me-1"></i>
                                        ประกาศสำคัญ</span>
                                @endif

                                <small class="text-muted ms-2">โดย {{ $post->author }}</small>
                            </div>
                        @empty
                            {{-- กรณีที่ยังไม่มีประกาศใดๆ --}}
                            <div class="text-center py-4">
                                <i class="bi bi-inbox text-muted fs-2"></i>
                                <p class="text-muted small mt-2">ยังไม่มีประกาศสำคัญในขณะนี้</p>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white font-weight-bold">
                    <strong>กิจกรรมที่กำลังจะมาถึง</strong>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="text-center me-3 border rounded px-2 py-1 bg-light">
                            <small class="d-block text-uppercase">พ.ค.</small>
                            <h5 class="mb-0">15</h5>
                        </div>
                        <div>
                            <h6 class="mb-0">Town Hall Meeting</h6>
                            <small class="text-muted">14:00 - 16:00 | Meeting Room A</small>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="text-center me-3 border rounded px-2 py-1 bg-light">
                            <small class="d-block text-uppercase">พ.ค.</small>
                            <h5 class="mb-0">20</h5>
                        </div>
                        <div>
                            <h6 class="mb-0">ส่งรายงานสรุปยอดขาย</h6>
                            <small class="text-muted">ก่อนเวลา 18:00 น.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
