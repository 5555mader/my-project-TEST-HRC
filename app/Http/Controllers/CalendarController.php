<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use App\Models\Department; // เรียกใช้โมเดลแผนก
use Illuminate\Support\Facades\Auth; // เรียกใช้ Auth

class CalendarController extends Controller {
    
    // 1. โหลดข้อมูลลงปฏิทิน (กรองตามแผนก)
    public function index() {
        $user = Auth::user();

        // 1. ในฟังก์ชัน index() ให้เห็นกิจกรรมทั้งหมด (รวม Super Admin, HR Manager, CEO, Director)
        if (in_array($user->role, ['Super Admin', 'HR Manager', 'CEO', 'Director'])) {
            return response()->json(CalendarEvent::all());
        }

        // สำหรับพนักงานทั่วไป หรือ Manager ให้เห็นเฉพาะที่ตั้งเป็น 'ทั้งหมด' หรือ 'แผนกตัวเอง'
        $events = CalendarEvent::where(function($query) use ($user) {
                        $query->where('target_department', 'ทั้งหมด')
                              ->orWhere('target_department', $user->department);
                    })->get();

        return response()->json($events);
    }

    // 2. เพิ่มกิจกรรมใหม่
    public function store(Request $request) {
        // 2. ปรับการเช็คสิทธิ์ให้จัดการกิจกรรมได้
        if (!in_array(Auth::user()->role, ['Manager', 'HR Manager', 'Super Admin', 'Director', 'CEO'])) {
            return response()->json(['error' => 'คุณไม่มีสิทธิ์จัดการกิจกรรม'], 403);
        }

        $request->validate([
            'title' => 'required',
            'start' => 'required|date',
            'target_department' => 'required' // เพิ่มการตรวจสอบความถูกต้อง
        ]);
        
        $event = CalendarEvent::create($request->all());
        return response()->json($event);
    }

    // 3. อัปเดตกิจกรรมเดิม
    public function update(Request $request, $id) {
        // 2. ปรับการเช็คสิทธิ์ให้จัดการกิจกรรมได้
        if (!in_array(Auth::user()->role, ['Manager', 'HR Manager', 'Super Admin', 'Director', 'CEO'])) {
            return response()->json(['error' => 'คุณไม่มีสิทธิ์จัดการกิจกรรม'], 403);
        }

        $event = CalendarEvent::findOrFail($id);
        $event->update($request->all());
        return response()->json($event);
    }

    // 4. ลบกิจกรรม
    public function destroy($id) {
        // 2. ปรับการเช็คสิทธิ์ให้จัดการกิจกรรมได้
        if (!in_array(Auth::user()->role, ['Manager', 'HR Manager', 'Super Admin', 'Director', 'CEO'])) {
            return response()->json(['error' => 'คุณไม่มีสิทธิ์จัดการกิจกรรม'], 403);
        }

        CalendarEvent::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}