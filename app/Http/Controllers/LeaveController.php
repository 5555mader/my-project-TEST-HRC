<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use App\Notifications\LeaveStatusUpdated; 
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewLeaveRequestNotification;

class LeaveController extends Controller
{
    // แสดงหน้าฟอร์มลาและประวัติของตนเอง
    public function index() {
        $history = LeaveRequest::where('user_id', Auth::id())->latest()->get();
        return view('leave', compact('history'));
    }

    // บันทึกคำขอลา
    public function store(Request $request) {
        $request->validate([
            'leave_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'reason' => 'required',
        ]);

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending' // เริ่มต้นเป็นรออนุมัติ
        ]);

        // ดึงข้อมูล HR Manager ทุกคนเพื่อส่งแจ้งเตือนไปหา
        $hrManagers = User::where('role', 'HR Manager')->get();
        Notification::send($hrManagers, new NewLeaveRequestNotification(Auth::user()->name));

        return back()->with('success', 'ส่งคำขอลาเรียบร้อยแล้ว');
    }

    // สำหรับจัดการการอนุมัติ (แยกสิทธิ์การมองเห็นตาม Role)
    public function approvals() {
        $user = Auth::user();
        $pendingLeaves = [];
        $pendingDocuments = [];

        // ฝ่ายบุคคล หรือ แอดมินสูงสุด เห็นคำขอลาทั้งหมด และบันทึกข้อความที่ระบุชื่อตนเอง
        if ($user->role == 'HR Manager' || $user->role == 'Super Admin') {
            $pendingLeaves = LeaveRequest::with('user')->where('status', 'pending')->get();
            $pendingDocuments = \App\Models\Document::with('user')
                                ->where('category', 'บันทึกข้อความภายใน')
                                ->where('status', 'pending')
                                ->where('approver_id', $user->id)
                                ->get();

        // ผู้จัดการแผนกทั่วไป เห็นเฉพาะบันทึกข้อความที่ส่งตรงถึงตนเองในขั้นแรก (pending)
        } elseif ($user->role == 'Manager') {
            $pendingLeaves = LeaveRequest::with('user')
                                ->where('status', 'pending')
                                ->whereHas('user', function($q) use ($user) {
                                    $q->where('department', $user->department);
                                })->get();

            $pendingDocuments = \App\Models\Document::with('user')
                                ->where('category', 'บันทึกข้อความภายใน')
                                ->where('status', 'pending')
                                ->where('approver_id', $user->id)
                                ->get();

        // ผอ.ฝ่าย และ CEO จะเห็นบันทึกข้อความทั้งหมดที่ผ่านการอนุมัติขั้นที่ 1 มาแล้ว (pending_step_2) อัตโนมัติ
        } elseif (in_array($user->role, ['Director', 'CEO'])) {
            $pendingDocuments = \App\Models\Document::with('user')
                                ->where('category', 'บันทึกข้อความภายใน')
                                ->where('status', 'pending_step_2')
                                ->get();
        }

        return view('manager.approvals', compact('pendingLeaves', 'pendingDocuments'));
    }

    // สำหรับ Manager และผู้มีสิทธิ์อนุมัติ: อนุมัติหรือปฏิเสธคำขอลา
    public function updateStatus(Request $request, $id) {
        $leave = LeaveRequest::findOrFail($id);
        
        $leave->update([
            'status' => $request->status,
            // รับเหตุผลการไม่อนุมัติ (ถ้ามี)
            'reject_reason' => $request->has('reject_reason') ? $request->reject_reason : null
        ]);

        // สั่งให้ส่งการแจ้งเตือนไปหา "พนักงาน" ที่เป็นเจ้าของใบลา
        $leave->user->notify(new LeaveStatusUpdated());

        return back()->with('success', 'ดำเนินการเรียบร้อยแล้ว');
    }
}
