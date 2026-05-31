<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayslipController extends Controller
{
    // 1. หน้าแสดงรายการสลิปเงินเดือน
    public function index()
    {
        // ดึงข้อมูลสลิปที่ถูก 'ปล่อย' แล้ว ของคนที่ Login อยู่เท่านั้น
        $payslips = \Illuminate\Support\Facades\DB::table('payrolls')
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('status', 'released')
            ->orderBy('month', 'desc')
            ->get();

        return view('payslip', compact('payslips'));
    }

    // ฟังก์ชันสร้างข้อมูลจำลองสำหรับแต่ละเดือน
    private function getPayslipData($id)
    {
        return [
            'user' => Auth::user(),
            'month' => $id == '2026-04' ? 'เมษายน 2569' : 'มีนาคม 2569',
            'base_salary' => 30000,
            'allowance' => 8000,
            'tax' => 2000,
            'social_security' => 1000,
            'net_total' => 35000
        ];
    }

    // 2. ฟังก์ชันสำหรับ "กดดู" (แสดง PDF บนเบราว์เซอร์)
    public function show($id)
    {
        $data = $this->getPayslipData($id);
        $pdf = Pdf::loadView('payslip_pdf', compact('data'));
        
        // ใช้ stream() เพื่อเปิดดูบนแท็บใหม่
        return $pdf->stream('payslip_' . $id . '.pdf');
    }

    // 3. ฟังก์ชันสำหรับ "ดาวน์โหลด"
    public function download($id)
    {
        $data = $this->getPayslipData($id);
        $pdf = Pdf::loadView('payslip_pdf', compact('data'));
        
        return $pdf->download('payslip_' . $id . '.pdf');
    }

    // 4. ฟังก์ชันสำหรับดูแบบ HTML (สำหรับการเรียกผ่าน API)
    public function getHtml($id) 
    {
        $data = $this->getPayslipData($id);
        // ส่ง view กลับไปเป็น HTML โดยตรง ไม่ต้องสร้าง PDF
        return view('payslip_pdf', compact('data')); 
    }

    // 5. ฟังก์ชันสำหรับ Preview สลิปเงินเดือน (ส่งกลับเป็น View ปกติ) ตามที่เพิ่มใหม่
    public function preview($id) {
        $data = $this->getPayslipData($id); // ใช้ฟังก์ชันจำลองข้อมูลที่คุณมีอยู่แล้ว
        return view('payslip_preview', compact('data')); // ส่งกลับเป็น View ปกติ
    }
}