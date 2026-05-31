<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use App\Models\Branch; // ต้องมีบรรทัดนี้อยู่บนสุด นอก class เพื่อเรียกใช้ Model Branch
use App\Models\Department; // เรียกใช้ Model Department (เพิ่มให้เพื่อความสมบูรณ์ เผื่อต้องการใช้แบบสั้นๆ)

class ProfileController extends Controller
{
    /**
     * แสดงหน้าฟอร์มข้อมูลส่วนตัว
     */
    public function edit(): View
    {
        // ดึงข้อมูล user ที่ login อยู่ปัจจุบัน
        $user = Auth::user(); 
        
        // ดึงข้อมูลสาขาทั้งหมดจาก DB
        $branches = Branch::all(); 
        
        // ดึงข้อมูลแผนกทั้งหมดจาก DB
        $departments = \App\Models\Department::all(); 
        
        // สำคัญ: ส่งข้อมูลทั้งหมดไปที่ View 'profile'
        return view('profile', compact('user', 'branches', 'departments')); 
    }

    /**
     * อัปเดตข้อมูลส่วนตัวของผู้ใช้งาน
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // 1. ตรวจสอบความถูกต้องของข้อมูลทั้งหมดร่วมกัน
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2040', 
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2040', // Validate ไฟล์ลายเซ็น
        ]);

        // 2. จัดการอัปโหลดรูปภาพโปรไฟล์ (ถ้ามีการเลือกไฟล์ใหม่)
        if ($request->hasFile('image')) {
            $path = public_path('uploads/profiles');
            
            // ตรวจสอบและสร้างโฟลเดอร์ถ้ายังไม่มี
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            // ลบรูปภาพเก่าออกก่อน (ถ้ามี) เพื่อประหยัดพื้นที่เซิร์ฟเวอร์
            if ($user->image && File::exists($path . '/' . $user->image)) {
                File::delete($path . '/' . $user->image);
            }

            // ตั้งชื่อไฟล์รูปภาพใหม่เพื่อป้องกันชื่อซ้ำ
            $fileName = 'profile_' . $user->id . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            
            // ย้ายไฟล์รูปไปไว้ที่ public/uploads/profiles/
            $request->file('image')->move($path, $fileName);
            
            // บันทึกชื่อไฟล์ลงในตัวแปรเพื่อใช้อัปเดตตาราง
            $user->image = $fileName;
        }

        // ==========================================
        // 🌟 เพิ่มระบบจัดการอัปโหลดรูปลายเซ็น (Signature) 🌟
        // ==========================================
        if ($request->hasFile('signature')) {
            $sigPath = public_path('uploads/signatures');
            
            // ตรวจสอบและสร้างโฟลเดอร์ถ้ายังไม่มี
            if (!File::isDirectory($sigPath)) {
                File::makeDirectory($sigPath, 0777, true, true);
            }

            // ลบลายเซ็นเก่าออกก่อน (ถ้ามี) เพื่อไม่ให้รกเซิร์ฟเวอร์
            if ($user->signature && File::exists($sigPath . '/' . $user->signature)) {
                File::delete($sigPath . '/' . $user->signature);
            }

            // ตั้งชื่อไฟล์ลายเซ็นใหม่ (เช่น sig_1_1716870000.png)
            $sigFileName = 'sig_' . $user->id . '_' . time() . '.' . $request->file('signature')->getClientOriginalExtension();
            
            // ย้ายไฟล์รูปไปไว้ที่ public/uploads/signatures/
            $request->file('signature')->move($sigPath, $sigFileName);
            
            // บันทึกชื่อไฟล์ลงในตาราง users
            $user->signature = $sigFileName;
        }
        // ==========================================

        // 3. อัปเดตข้อมูลส่วนตัวอื่นๆ
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->department = $request->department;
        $user->address = $request->address;
        $user->branch = $request->branch;
        
        $user->save(); // บันทึกข้อมูลทั้งหมดลงฐานข้อมูล

        return redirect()->back()->with('success', 'อัปเดตข้อมูลโปรไฟล์และรูปภาพเรียบร้อยแล้วครับ!');
    }
}