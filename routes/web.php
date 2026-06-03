<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NotificationController; 
use App\Http\Controllers\CalendarController;     
use App\Models\Announcement;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use App\Models\PerformanceReview;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Document;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// หน้าแรกและระบบโต้ตอบ (Public)
// บังคับหน้าแรกให้วิ่งไปที่หน้า Login ทันที
Route::redirect('/', '/login');
Route::post('/post/{id}/like', [AnnouncementController::class, 'like'])->name('posts.like');
Route::post('/post/{id}/comment', [AnnouncementController::class, 'comment'])->name('posts.comment');

// บังคับ Login สำหรับระบบภายในทั้งหมด
Route::middleware(['auth'])->group(function () {

    // หน้าประกาศข่าวสาร (ย้ายเข้ามาไว้ในนี้และเปลี่ยน URL เป็น /home)
    Route::get('/home', [AnnouncementController::class, 'index'])->name('welcome');
    
    // 1. Dashboard พื้นฐาน (แก้ไขเพิ่มระบบคำนวณวันลาพักร้อนแล้ว)
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        // 1. กำหนดโควตาวันลาพักร้อนทั้งหมดของบริษัท (สามารถเปลี่ยนตัวเลขได้ตามต้องการ)
        $total_quota = 10; 
        
        // 2. ดึงประวัติการลาพักร้อนของพนักงานคนนี้ที่ "อนุมัติแล้ว" เพื่อนำมาคำนวณวันลาที่ใช้ไป
        $approved_leaves = \App\Models\LeaveRequest::where('user_id', $user->id)
            ->where('leave_type', 'ลาพักร้อน')
            ->where('status', 'approved')
            ->get();
            
        $used_leave = 0;
        foreach ($approved_leaves as $leave) {
            $start = \Carbon\Carbon::parse($leave->start_date);
            $end = \Carbon\Carbon::parse($leave->end_date);
            // คำนวณจำนวนวันลา (บวก 1 เพื่อให้นับรวมวันแรก)
            $used_leave += $start->diffInDays($end) + 1; 
        }
        
        // 3. คำนวณวันลาคงเหลือ
        $remaining_leave = $total_quota - $used_leave;
        if ($remaining_leave < 0) {
            $remaining_leave = 0;
        }
        
        // 4. คำนวณเปอร์เซ็นต์ความคืบหน้าสำหรับ Progress Bar บนหน้าเว็บ
        $percent = $total_quota > 0 ? ($remaining_leave / $total_quota) * 100 : 0;

        // 5. ดึงข้อมูลประกาศสำคัญล่าสุด 5 ประกาศ
        $important_posts = \App\Models\Announcement::whereIn('category', ['success', 'danger'])
                                                   ->latest()
                                                   ->take(5)
                                                   ->get();

        // 6. ส่งตัวแปรทั้งหมดเข้าไปในหน้า Dashboard ด้วย compact()
        return view('dashboard', compact('important_posts', 'remaining_leave', 'total_quota', 'percent'));
    })->name('dashboard');

    // -------------------------------------------------------------
    // ระบบจัดการเอกสาร (Document / Memo)
    // -------------------------------------------------------------
    // ลิงก์เปิดหน้าฟอร์มเอกสาร A4 พร้อมระบบคำนวณรันเลขหนังสืออัตโนมัติ (Method GET)
    Route::get('/document-form', function () {
        $currentYearThai = \Carbon\Carbon::now()->addYears(543)->format('Y');
        $latestDoc = \App\Models\Document::where('category', 'บันทึกข้อความภายใน')
                        ->whereYear('created_at', \Carbon\Carbon::now()->year)
                        ->orderBy('id', 'desc')
                        ->first();
        
        if ($latestDoc) {
            preg_match('/บข\.(\d+)\//', $latestDoc->doc_number, $matches);
            $nextNumber = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        } else {
            $nextNumber = 1;
        }
        
        $nextDocNumber = 'บข.' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT) . '/' . $currentYearThai;

        return view('form', compact('nextDocNumber'));
    })->name('document.form');

    // เส้นทางเพิ่มใหม่สำหรับบันทึก Memo ลงฐานข้อมูล (Method POST)
    Route::post('/document-form', [DocumentController::class, 'storeMemo'])->name('admin.archives.store-memo');

    // เส้นทางสำหรับประมวลผลเซฟบันทึกข้อความลงตาราง (เดิมที่มีอยู่)
    Route::post('/document-form/store', [DocumentController::class, 'storeArchive'])->name('admin.archives.store');
    // -------------------------------------------------------------


    // --- ระบบแจ้งเตือน (Notifications) ---
    Route::get('/api/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.api');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clearAll');
    
    // --- ระบบปฏิทิน (Calendar) ---
    Route::get('/calendar/events', [CalendarController::class, 'index'])->name('admin.calendar.events');

    // --- โปรไฟล์ผู้ใช้งาน (Main) ---
    Route::get('/profile', function () {
        return view('profile', [
            'user' => Auth::user(),
            'branches' => \App\Models\Branch::all(),
            'departments' => \App\Models\Department::all()
        ]);
    })->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- ระบบสลิปเงินเดือน (Payslip - ส่วนกลาง) ---
    Route::get('/payslip', [PayslipController::class, 'index'])->name('payslip.index');
    Route::get('/payslip/view/{id}', [App\Http\Controllers\PayslipController::class, 'show'])->name('payslip.show');

    // 2. ESS Routes Group
    Route::prefix('ess')->group(function () {
        Route::get('/dashboard', function () { 
            $user = Auth::user();
            $today = Carbon::today()->toDateString();
            $todayAttendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();
            $total_quota = 12;
            $used_leave = LeaveRequest::where('user_id', $user->id)->where('status', 'approved')->get()->sum(function($leave) {
                    return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
                });
            $remaining_leave = $total_quota - $used_leave;
            $percent = ($total_quota > 0) ? ($remaining_leave / $total_quota) * 100 : 0;
            $important_posts = Announcement::whereIn('category', ['success', 'danger'])->latest()->take(5)->get();
            $latestReview = PerformanceReview::where('user_id', $user->id)->latest()->first();
            $reviewScore = $latestReview ? ($latestReview->quality_score + $latestReview->punctuality_score) / 2 : 0;

            return view('dashboard', compact('important_posts', 'todayAttendance', 'remaining_leave', 'total_quota', 'percent', 'reviewScore')); 
        })->name('ess.dashboard');
        
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('ess.attendance');
        Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('ess.attendance.checkin');
        Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('ess.attendance.checkout');
        Route::get('/leave', [LeaveController::class, 'index'])->name('ess.leave');
        Route::post('/leave', [LeaveController::class, 'store'])->name('ess.leave.store');
        
        // ส่วนสลิปเงินเดือน (Payslip)
        Route::get('/payslip', [PayslipController::class, 'index'])->name('ess.payslip');
        Route::get('/payslip/{id}/view', [PayslipController::class, 'show'])->name('ess.payslip.show');
        Route::get('/payslip/{id}/download', [PayslipController::class, 'download'])->name('ess.payslip.download');
        Route::get('/payslip/{id}/preview', [PayslipController::class, 'preview'])->name('ess.payslip.preview');
        
        // Added API Route for Payslip
        Route::get('/api/payslip/{id}', [PayslipController::class, 'getHtml']);
        
        Route::get('/welfare', function () { return view('welfare'); })->name('ess.welfare');
        
        Route::get('/profile', function () {
            return view('profile', [
                'user' => Auth::user(),
                'branches' => \App\Models\Branch::all(),
                'departments' => \App\Models\Department::all()
            ]);
        })->name('ess.profile');

        Route::post('/profile', [ProfileController::class, 'update'])->name('ess.profile.update');
    });

    // --- Manager Routes Group ---
    Route::prefix('manager')->group(function () {
        Route::get('/approvals', [LeaveController::class, 'approvals'])->name('manager.approvals');
        Route::patch('/approvals/{id}', [LeaveController::class, 'updateStatus'])->name('manager.approvals.update');
        
        Route::get('/team', function () { 
            // 🔒 ป้องกันไม่ให้ CEO, ผอ.ฝ่าย หรือพนักงานทั่วไป เข้าหน้านี้
            if (in_array(Auth::user()->role, ['CEO', 'Director', 'Employee'])) {
                return redirect('/')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้าภาพรวมทีม');
            }

            $today = Carbon::today()->toDateString();
            $employees = User::all();
            $todayAttendances = Attendance::where('date', $today)->get()->keyBy('user_id');
            $todayLeaves = LeaveRequest::where('status', 'approved')->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->get()->keyBy('user_id');
            $stats = [
                'total'   => $employees->count(),
                'present' => $todayAttendances->count(),
                'late'    => $todayAttendances->where('status', 'มาสาย')->count(),
                'leave'   => $todayLeaves->count(),
            ];
            return view('manager.team', compact('employees', 'todayAttendances', 'todayLeaves', 'stats'));
        })->name('manager.team');
        
        Route::get('/review', function (Request $request) { 
            // 🔒 ป้องกันไม่ให้ CEO, ผอ.ฝ่าย หรือพนักงานทั่วไป เข้าหน้านี้
            if (in_array(Auth::user()->role, ['CEO', 'Director', 'Employee'])) {
                return redirect('/')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้าประเมินผลงาน');
            }

            $employees = User::all();
            $selectedEmployee = $request->has('user_id') ? User::find($request->user_id) : null;
            return view('manager.review', compact('employees', 'selectedEmployee')); 
        })->name('manager.review');

        Route::post('/review', function (Request $request) {
            PerformanceReview::create([
                'user_id' => $request->employee_id,
                'quality_score' => $request->quality_score,
                'punctuality_score' => $request->punctuality_score,
                'comments' => $request->comments,
            ]);
            return back()->with('success', 'บันทึกผลการประเมินเรียบร้อยแล้ว!');
        })->name('manager.review.store');
    });

    // --- Admin Routes Group ---
    Route::prefix('admin')->group(function () {
        
        Route::get('/employees', function (Request $request) {
            if (Auth::user()->role !== 'Super Admin') {
                return redirect('/')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
            }
            $query = User::latest();
            if ($request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            $employees = $query->get();
            $branches = \App\Models\Branch::all();
            $departments = \App\Models\Department::all();
            return view('admin.employees', compact('employees', 'branches', 'departments')); 
        })->name('admin.employees');

        Route::post('/employees/store', function (Request $request) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'role' => 'required',
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'department' => $request->department,
                'branch' => $request->branch,
                'role' => $request->role,
            ]);
            return redirect()->route('admin.employees')->with('success', 'ลงทะเบียนพนักงานใหม่เรียบร้อยแล้ว!');
        })->name('admin.employees.store');

        Route::patch('/employees/{id}', function (Request $request, $id) {
            if (Auth::user()->role !== 'Super Admin') {
                return redirect('/')->with('error', 'คุณไม่มีสิทธิ์ดำเนินการนี้');
            }
            $user = User::findOrFail($id);
            $data = $request->except(['password']);
            if ($request->filled('password')) {
                $request->validate(['password' => 'required|min:6']);
                $data['password'] = Hash::make($request->password);
            }
            $user->update($data);
            return back()->with('success', 'อัปเดตข้อมูลพนักงานและรหัสผ่านใหม่เรียบร้อยแล้ว!');
        })->name('admin.employees.update');

        Route::delete('/employees/{id}', function ($id) {
            if (Auth::user()->role !== 'Super Admin') {
                return redirect('/')->with('error', 'คุณไม่มีสิทธิ์ดำเนินการนี้');
            }
            User::findOrFail($id)->delete();
            return back()->with('success', 'ลบพนักงานออกจากระบบสำเร็จ');
        })->name('admin.employees.destroy');

        Route::put('/users/{id}/update-password', [DocumentController::class, 'updateUserPassword'])->name('admin.users.update-password');

        Route::get('/payroll', function (Request $request) { 
            $query = User::query();
            if ($request->has('search') && $request->search != '') {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('department', 'like', '%' . $request->search . '%');
            }
            $currentMonth = date('Y-m');
            $employees = $query->get()->map(function($employee) use ($currentMonth) {
                $lateCount = Attendance::where('user_id', $employee->id)
                    ->where('date', 'like', $currentMonth . '%')
                    ->where('status', 'มาสาย')
                    ->count();
                $absentCount = Attendance::where('user_id', $employee->id)
                    ->where('date', 'like', $currentMonth . '%')
                    ->where('status', 'ขาดงาน')
                    ->count();
                $lateRate = 50;
                $absentRate = 500;
                $employee->total_deduction = ($lateCount * $lateRate) + ($absentCount * $absentRate);
                $employee->late_count = $lateCount;
                return $employee;
            });
            return view('admin.payroll', compact('employees')); 
        })->name('admin.payroll');
        
        Route::post('/payroll/action', function (Request $request) {
            $selectedEmployees = $request->input('employee_ids');
            $actionType = $request->input('action_type');
            if (!$selectedEmployees) {
                return back()->withErrors('กรุณาเลือกพนักงานอย่างน้อย 1 คน');
            }
            if ($actionType == 'release') {
                $currentMonth = date('Y-m');
                foreach ($selectedEmployees as $userId) {
                    DB::table('payrolls')->updateOrInsert(
                        ['user_id' => $userId, 'month' => $currentMonth], 
                        [
                            'base_salary' => 30000.00,
                            'bonus' => 0.00,
                            'deduction' => 1500.00,
                            'net_total' => 28500.00,
                            'status' => 'released',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]
                    );
                }
                return back()->with('success', 'ปล่อยสลิปเงินเดือนให้พนักงานเรียบร้อยแล้ว!');
            }
            return back();
        })->name('admin.payroll.release');
        
        Route::get('/reports', function () {
            $currentMonth = Carbon::now()->format('Y-m');
            $totalExpenses = DB::table('payrolls')
                ->where('month', $currentMonth)
                ->where('status', 'released')
                ->sum('net_total');
            $totalEmployees = User::count();
            $totalSickDays = LeaveRequest::where('leave_type', 'ลาป่วย')
                ->where('status', 'approved')
                ->whereYear('start_date', date('Y'))
                ->get()
                ->sum(function($leave) {
                    return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
                });
            $avgSickLeave = $totalEmployees > 0 ? number_format($totalSickDays / $totalEmployees, 1) : 0;
            $dates = [];
            $presents = [];
            $lates = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i)->format('Y-m-d');
                $dates[] = Carbon::parse($date)->format('d/m');
                $presents[] = Attendance::whereDate('date', $date)->where('status', 'ปกติ')->count();
                $lates[] = Attendance::whereDate('date', $date)->where('status', 'มาสาย')->count();
            }
            return view('admin.reports', compact('totalExpenses', 'totalEmployees', 'avgSickLeave', 'dates', 'presents', 'lates'));
        })->name('admin.reports');
        
        Route::get('/documents', [DocumentController::class, 'index'])->name('admin.documents');
        Route::post('/documents/store', [DocumentController::class, 'store'])->name('admin.documents.store');
        Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('admin.documents.destroy');
        Route::patch('/documents/{id}/status', [DocumentController::class, 'updateStatus'])->name('admin.documents.update_status');
        
        Route::get('/archives', [DocumentController::class, 'archives'])->name('admin.archives');
        Route::post('/memos/store', [DocumentController::class, 'storeMemo'])->name('admin.memos.store');
        
        Route::patch('/archives/{id}', [DocumentController::class, 'updateArchive'])->name('admin.archives.update');
        Route::delete('/archives/{id}', [DocumentController::class, 'destroyArchive'])->name('admin.archives.destroy');
        
        // ✨ ใส่เพิ่มตรงนี้ตามที่คุณต้องการ (และได้จัดฟอร์แมตให้กระชับเข้ากับไฟล์เดิม)
        Route::get('/archives/show/{id}', [DocumentController::class, 'showForm'])->name('admin.archives.show-form');

        // 🆕 เพิ่ม Route สำหรับเปิดหน้าฟอร์มสร้างบันทึกข้อความใหม่ (form.blade.php)
        Route::get('/archives/create-memo', function () {
            // ลอจิกเจนเลขที่เอกสาร MEMO รันอัตโนมัติส่งไปที่หน้าฟอร์ม
            $count = \App\Models\Document::where('category', 'บันทึกข้อความภายใน')->count() + 1;
            $nextDocNumber = 'MEMO-' . date('Ym') . '-' . sprintf('%04d', $count);
            
            // ส่งค่า $nextDocNumber ไปที่หน้า view ชื่อ form.blade.php
            return view('form', compact('nextDocNumber'));
        })->name('admin.memos.create');

        // 🆕 เพิ่ม Route สำหรับปุ่มลบเอกสารบันทึกข้อความภายใน
        Route::delete('/archives/memo/{id}', [DocumentController::class, 'destroy'])->name('admin.memos.destroy');
        // 🆕 เพิ่ม Route สำหรับอัปเดต(แก้ไข) บันทึกข้อความภายใน
        Route::put('/archives/memo/{id}', [DocumentController::class, 'updateMemo'])->name('admin.memos.update');

        Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
        Route::post('/announcements/store', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
        Route::get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcements.edit');
        Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('admin.announcements.update');
        Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');

        Route::get('/calendar/manage', function () {
            return view('admin.calendar_manage');
        })->name('admin.calendar.manage');
        
        Route::post('/calendar/events', [CalendarController::class, 'store']);
        Route::put('/calendar/events/{id}', [CalendarController::class, 'update']);
        Route::delete('/calendar/events/{id}', [CalendarController::class, 'destroy']);

        Route::post('/branches', function (Illuminate\Http\Request $request) {
            $request->validate(['name' => 'required|unique:branches,name']);
            Branch::create(['name' => $request->name, 'location' => $request->location]);
            return back()->with('success', 'เพิ่มสาขาใหม่เรียบร้อยแล้ว!');
        })->name('admin.branches.store');

        Route::patch('/branches/{id}', function (Illuminate\Http\Request $request, $id) {
            $request->validate(['name' => 'required|unique:branches,name,' . $id]);
            $branch = \App\Models\Branch::findOrFail($id);
            $branch->update($request->all());
            return back()->with('success', 'อัปเดตข้อมูลสาขาเรียบร้อยแล้ว!');
        })->name('admin.branches.update');

        Route::delete('/branches/{id}', function ($id) {
            $branch = \App\Models\Branch::findOrFail($id);
            $branch->delete();
            return back()->with('success', 'ลบสาขาออกจากระบบสำเร็จ!');
        })->name('admin.branches.destroy');

        Route::post('/departments', function (Illuminate\Http\Request $request) {
            $request->validate(['name' => 'required|unique:departments,name']);
            \App\Models\Department::create(['name' => $request->name]);
            return back()->with('success', 'เพิ่มแผนกใหม่เรียบร้อยแล้ว!');
        })->name('admin.departments.store');

        Route::patch('/departments/{id}', function (Illuminate\Http\Request $request, $id) {
            $request->validate(['name' => 'required|unique:departments,name,' . $id]);
            $dept = \App\Models\Department::findOrFail($id);
            $dept->update($request->all());
            return back()->with('success', 'อัปเดตชื่อแผนกเรียบร้อยแล้ว!');
        })->name('admin.departments.update');

        Route::delete('/departments/{id}', function ($id) {
            $dept = \App\Models\Department::findOrFail($id);
            $dept->delete();
            return back()->with('success', 'ลบแผนกออกจากระบบสำเร็จแล้ว!');
        })->name('admin.departments.destroy');
    });
});

require __DIR__.'/auth.php';