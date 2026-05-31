<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        
        // เช็คว่าวันนี้ลงเวลาหรือยัง
        $todayAttendance = Attendance::where('user_id', $user->id)
                                     ->where('date', $today)->first();

        // ดึงประวัติย้อนหลัง 7 วัน
        $history = Attendance::where('user_id', $user->id)
                             ->orderBy('date', 'desc')
                             ->limit(7)->get();

        return view('attendance', compact('todayAttendance', 'history'));
    }

    public function checkIn(Request $request)
    {
        $now = Carbon::now();
        $status = $now->format('H:i') > '09:00' ? 'มาสาย' : 'ปกติ'; // กำหนดเวลาเข้างาน 09:00

        Attendance::create([
            'user_id' => Auth::id(),
            'date' => $now->toDateString(),
            'check_in' => $now->toTimeString(),
            'status' => $status
        ]);

        return back()->with('success', 'บันทึกเวลาเข้างานสำเร็จ!');
    }

    public function checkOut(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $attendance = Attendance::where('user_id', Auth::id())
                                ->where('date', $today)->first();

        if ($attendance) {
            $attendance->update([
                'check_out' => Carbon::now()->toTimeString()
            ]);
            return back()->with('success', 'บันทึกเวลาออกงานสำเร็จ!');
        }

        return back()->with('error', 'ไม่พบข้อมูลการเข้างานของวันนี้');
    }
}
