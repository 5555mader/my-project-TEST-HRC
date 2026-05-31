<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewDocumentRequestNotification;

class DocumentController extends Controller
{
    /**
     * หน้า Document Center (จัดการแบบฟอร์มเอกสารส่วนกลาง)
     */
    public function index(Request $request)
    {
        $query = Document::where('category', '!=', 'บันทึกข้อความภายใน')->latest();
        
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $documents = $query->get();
        return view('admin.documents', compact('documents'));
    }

    /**
     * เรียกดูรายการบันทึกข้อความภายในของตนเอง
     */
    public function archives(Request $request)
    {
        $userId = Auth::id();
        $userRole = Auth::user()->role;

        $query = Document::with(['user', 'approver'])
                        ->where('category', 'บันทึกข้อความภายใน');

        if ($userRole !== 'Super Admin') {
            $query->where(function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhere('approver_id', $userId)
                  ->orWhere('approver_2_id', $userId)
                  ->orWhereJsonContains('cc_users', $userId)
                  ->orWhereJsonContains('cc_users', (string)$userId);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('doc_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $documents = $query->latest()->get();
        return view('admin.archives', compact('documents'));
    }

    /**
     * เปิดดูรายละเอียดเอกสาร (เปลี่ยนชื่อเป็น showForm เพื่อให้ตรงตาม Route ใหม่แล้ว)
     */
    public function showForm($id)
    {
        $document = Document::findOrFail($id);
        $userId = Auth::id();
        $userRole = Auth::user()->role;

        if ($userRole !== 'Super Admin') {
            $isCcUser = is_array($document->cc_users) && (in_array($userId, $document->cc_users) || in_array((string)$userId, $document->cc_users));
            
            $hasAccess = ($document->user_id == $userId) || 
                         ($document->approver_id == $userId) || 
                         ($document->approver_2_id == $userId) || 
                         $isCcUser;

            if (!$hasAccess) {
                abort(403, 'ขออภัย คุณไม่มีสิทธิ์เปิดอ่านบันทึกข้อความฉบับนี้');
            }
        }

        return view('admin.view-form', compact('document'));
    }

    /**
     * บันทึกคำขอข้อความภายในใหม่
     */
    public function storeMemo(Request $request)
    {
        // 1. ตรวจสอบความถูกต้องของข้อมูลเบื้องต้น
        $request->validate([
            'department' => 'required',
            'doc_number' => 'required',
            'title' => 'required',
            'to_position' => 'required',
            'content' => 'required',
            'approver_id' => 'required',
            // เช็คไฟล์แนบแบบหลายไฟล์ (อนุญาตเฉพาะไฟล์บางประเภท ขนาดไม่เกิน 5MB)
            'document_files' => 'nullable|array',
            'document_files.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120', 
        ]);

        // 2. สร้างเอกสารใหม่
        $document = new Document();
        $document->user_id = Auth::id(); // คนที่ล็อกอินอยู่คือคนสร้าง
        $document->department = $request->department;
        $document->doc_number = $request->doc_number;
        $document->title = $request->title;
        $document->to_position = $request->to_position;
        $document->content = $request->content;
        $document->category = 'บันทึกข้อความภายใน';
        $document->status = 'pending'; // ตั้งสถานะเริ่มต้นเป็นรออนุมัติ
        
        // 🌟 รับค่าจำนวนเงิน (ถ้ามี)
        $document->amount = $request->amount; 

        // 🌟 รับค่าสำเนาส่ง CC (แปลงเป็น JSON ก่อนบันทึกตามที่ตั้งค่า Cast ไว้ใน Model)
        $document->cc_users = $request->cc_users;

        // 🌟 จัดการผู้อนุมัติ 1 คน หรือ 2 คน
        $document->approver_id = $request->approver_id; // ผู้อนุมัติคนที่ 1
        
        if ($request->approval_steps == 1) {
            // ถ้าเลือกอนุมัติ 1 คน ให้ผู้อนุมัติคนที่ 2 เป็น null
            $document->approver_2_id = null; 
        } else {
            // 🌟 ดึงค่าไอดีของ ผอ.ฝ่าย / CEO ที่พนักงานเลือกจากหน้าฟอร์มโดยตรง
            $document->approver_2_id = $request->approver_2_id; 
        }

        // เซฟข้อมูลเอกสารหลักก่อน เพื่อให้ได้ id ไปใช้กับไฟล์แนบ
        $document->save();

        // 3. จัดการอัปโหลดไฟล์แนบหลายไฟล์ (เชื่อมกับตาราง DocumentFile)
        if ($request->hasFile('document_files')) {
            foreach ($request->file('document_files') as $file) {
                $fileExtension = $file->getClientOriginalExtension();
                $fileSize = $file->getSize(); 
                
                // ใช้ uniqid() ป้องกันชื่อไฟล์ชนกันกรณีอัปโหลดพร้อมกันหลายไฟล์
                $fileName = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                
                // ย้ายไฟล์ไปยังโฟลเดอร์ public/uploads/documents/
                $file->move(public_path('uploads/documents'), $fileName);

                // บันทึกลงตารางใหม่
                \App\Models\DocumentFile::create([
                    'document_id' => $document->id,
                    'file_name' => $fileName,
                    'file_size' => $fileSize,
                    'file_extension' => $fileExtension
                ]);
            }
        }

        // แจ้งเตือนไปยังผู้อนุมัติ (ถ้ามีระบบแจ้งเตือน)
        if ($document->approver) {
            $document->approver->notify(new \App\Notifications\NewDocumentRequestNotification($document->title));
        }

        // 4. ส่งแจ้งเตือน (ถ้ามี) และ Redirect กลับ
        return redirect()->route('admin.archives')->with('success', 'ส่งเอกสารบันทึกข้อความเรียบร้อยแล้ว');
    }

    public function updateMemo(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        // ป้องกันไม่ให้แก้ไขเอกสารที่ถูกอนุมัติไปแล้ว
        if ($document->status !== 'pending') {
            return back()->with('error', 'ไม่อนุญาตให้แก้ไขเอกสารที่ถูกดำเนินการไปแล้ว');
        }

        // จัดการผู้อนุมัติคนที่ 2 (ถ้าเลือกแบบ 1 คน ให้ค่าเป็น null)
        $approver2 = $request->approval_steps == 1 ? null : $request->approver_2_id;

        // อัปเดตข้อมูลลงฐานข้อมูล
        $document->update([
            'branch' => $request->branch,
            'department' => $request->department,
            'title' => $request->title,
            'to_position' => $request->to_position,
            'amount' => $request->amount,
            'cc_users' => $request->cc_users,
            'approver_id' => $request->approver_id,
            'approver_2_id' => $approver2,
            'content' => $request->content,
        ]);

        return back()->with('success', 'แก้ไขข้อมูลบันทึกข้อความเรียบร้อยแล้ว!');
    }

    public function destroyMemo($id)
    {
        $document = Document::where('user_id', Auth::id())->findOrFail($id);

        if(!in_array($document->status, ['pending', 'pending_step_2'])) {
            return back()->with('error', 'เอกสารผ่านการพิจารณาแล้ว ไม่สามารถลบได้');
        }

        $document->delete();

        return back()->with('success', 'ลบบันทึกข้อความภายในเรียบร้อยแล้ว!');
    }

    /**
     * การแก้ไขข้อมูลบันทึกข้อความ (อัปเดตแล้ว รองรับการแนบไฟล์เพิ่ม)
     */
    public function updateArchive(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string',
            'to_position' => 'required|string',
            'content' => 'required|string',
            'approver_id' => 'required|integer',
            'approver_2_id' => 'nullable|integer',
            'attachments' => 'nullable|array|max:10',
            'attachments.*' => 'file|max:10240',
        ]);

        $document = Document::findOrFail($id);

        if ($document->status == 'approved') {
            return back()->with('error', 'ไม่สามารถแก้ไขเอกสารนี้ได้ เนื่องจากผ่านการอนุมัติเรียบร้อยแล้ว');
        }

        // 1. จัดการอัปเดตไฟล์แนบเพิ่มเติม
        $attachmentsList = [];
        // เก็บไฟล์เก่าไว้ก่อน (ถ้ามี)
        if ($document->attachments) {
            $existingAttachments = json_decode($document->attachments, true);
            if (is_array($existingAttachments)) {
                $attachmentsList = $existingAttachments;
            }
        }

        // 2. รับไฟล์ใหม่เข้ามาแนบรวม
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/documents'), $filename);
                $attachmentsList[] = $filename; 
            }
        }

        $document->update([
            'title' => $request->title,
            'department' => $request->department,
            'to_position' => $request->to_position,
            'content' => $request->content,
            'approver_id' => $request->approver_id,
            'approver_2_id' => $request->approver_2_id,
            'attachments' => count($attachmentsList) > 0 ? json_encode($attachmentsList, JSON_UNESCAPED_UNICODE) : null,
        ]);

        return back()->with('success', 'อัปเดตข้อมูลการแก้ไขบันทึกข้อความเรียบร้อยแล้ว!');
    }

    public function destroyArchive($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();

        return back()->with('success', 'ลบเอกสารบันทึกข้อความออกจากคลังสำเร็จ');
    }

    public function storeArchive(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'doc_number' => 'required|string',
            'department' => 'required|string',
            'to_position' => 'required|string',
            'content' => 'required|string',
            'approver_id' => 'required|integer',
            'approver_2_id' => 'nullable|integer',
            'cc_users' => 'nullable|array', 
            'attachments' => 'nullable|array|max:10', 
            'attachments.*' => 'file|max:10240', 
        ]);

        $attachmentsList = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/documents'), $filename);
                $attachmentsList[] = $filename; 
            }
        }

        $document = Document::create([
            'title' => $request->title,
            'category' => 'บันทึกข้อความภายใน', 
            'doc_number' => $request->doc_number,
            'department' => $request->department,
            'to_position' => $request->to_position,
            'content' => $request->content,
            'approver_id' => $request->approver_id,
            'approver_2_id' => $request->approver_2_id, 
            'user_id' => Auth::id(), 
            'status' => 'pending',   
            'cc_users' => $request->cc_users, 
            'attachments' => count($attachmentsList) > 0 ? json_encode($attachmentsList, JSON_UNESCAPED_UNICODE) : null,
        ]);

        $approver = User::find($request->approver_id);
        if ($approver) {
            $approver->notify(new NewDocumentRequestNotification($request->title));
        }

        return redirect()->route('admin.archives')->with('success', 'บันทึกเอกสารและส่งคำขอพร้อมแจ้งเตือนไปยังผู้อนุมัติเรียบร้อยแล้ว!');
    }

    /**
     * สำหรับการขอสร้างบันทึกข้อความ/เอกสาร
     */
    public function store(Request $request)
    {
        // === การอัปโหลดไฟล์เดี่ยวแบบเดิม (สำหรับ category อื่นๆ) ===
        $fileName = null;
        $fileSize = null;
        $fileExtension = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileSize = number_format($file->getSize() / 1048576, 2) . ' MB';
            $fileExtension = strtolower($file->getClientOriginalExtension());
            $file->move(public_path('uploads/documents'), $fileName);
        }

        $ccUsers = $request->has('cc_users') ? $request->cc_users : null;

        // === สำหรับสร้าง "บันทึกข้อความภายใน" รองรับแนบหลายไฟล์ (Attachments) ===
        if ($request->category == 'บันทึกข้อความภายใน' || $request->has('approver_id')) {
            $request->validate([
                'doc_number'  => 'required|unique:documents,doc_number',
                'title'       => 'required|string|max:255',
                'department'  => 'required|string',
                'to_position' => 'required|string',
                'content'     => 'required|string',
                'approver_id' => 'required|exists:users,id',
                'approver_2_id' => 'nullable|exists:users,id',
                'attachments' => 'nullable|array|max:10',
                'attachments.*' => 'file|max:10240',
            ]);

            // 1. เตรียมตัวแปรเก็บชื่อไฟล์เป็น Array
            $attachmentsList = [];

            // 2. ตรวจสอบว่ามีการแนบไฟล์แบบ multiple มาหรือไม่
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachFile) {
                    // ตั้งชื่อไฟล์ใหม่ไม่ให้ซ้ำกัน
                    $filename = time() . '_' . uniqid() . '_' . $attachFile->getClientOriginalName();
                    // ย้ายไฟล์ไปเก็บที่ public/uploads/documents
                    $attachFile->move(public_path('uploads/documents'), $filename);
                    // นำชื่อไฟล์เก็บเข้า Array
                    $attachmentsList[] = $filename; 
                }
            }

            // 3. เซฟข้อมูลลงตาราง Document
            Document::create([
                'user_id'       => Auth::id(),
                'title'         => $request->title,
                'doc_number'    => $request->doc_number,
                'department'    => $request->department,
                'to_position'   => $request->to_position,
                'content'       => $request->content,
                'approver_id'   => $request->approver_id,
                'approver_2_id' => $request->approver_2_id,
                'category'      => 'บันทึกข้อความภายใน',
                'status'        => 'pending',
                'cc_users'      => $ccUsers, 
                'file_name'     => $fileName, // ถ้ายังมีอัปโหลดไฟล์เดี่ยวแทรกอยู่
                'file_size'     => $fileSize,
                'file_extension'=> $fileExtension,
                // แปลง Array เป็น JSON ก่อนบันทึกลงคอลัมน์ attachments
                'attachments'   => count($attachmentsList) > 0 ? json_encode($attachmentsList, JSON_UNESCAPED_UNICODE) : null,
            ]);
            
            $approver = User::find($request->approver_id);
            if ($approver) {
                $approver->notify(new NewDocumentRequestNotification($request->title));
            }
            
            return back()->with('success', 'ส่งบันทึกข้อความไปยังผู้อนุมัติเรียบร้อยแล้ว');
        } 
        
        // === กรณีเอกสารหมวดหมู่อื่นๆ ===
        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        Document::create([
            'title' => $request->title,
            'category' => $request->category,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_extension' => $fileExtension,
            'cc_users' => null 
        ]);

        return back()->with('success', 'อัปโหลดเอกสารสำเร็จแล้ว');
    }

    public function updateStatus(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $user = Auth::user();

        if ($request->status == 'approved') {
            
            // เคสที่ 1: ผู้อนุมัติคนที่ 1 เป็นคนกดอนุมัติ (สถานะเดิมของเอกสารเป็น pending)
            if ($document->status == 'pending' && $document->approver_id == $user->id) {
                $document->update([
                    'status' => 'pending_step_2'
                ]);

                // ดึงรายชื่อ ผอ.ฝ่าย (Director) และ CEO ทั้งหมดในระบบเพื่อส่งการแจ้งเตือนแบบกลุ่ม
                $managementUsers = \App\Models\User::whereIn('role', ['Director', 'CEO'])->get();
                foreach ($managementUsers as $manager) {
                    $manager->notify(new NewDocumentRequestNotification($document->title));
                }

                return back()->with('success', 'อนุมัติขั้นแรกสำเร็จ ระบบได้ส่งต่อให้ ผอ.ฝ่าย และ CEO พิจารณาอัตโนมัติเรียบร้อยแล้ว');
            }

            // เคส: ผอ.ฝ่าย หรือ CEO เป็นคนกดอนุมัติขั้นตอนสุดท้าย
            if ($document->status == 'pending_step_2' && in_array($user->role, ['Director', 'CEO'])) {
                
                // 1. อัปเดตสถานะเอกสารเป็นอนุมัติ
                $document->update([
                    'status' => 'approved',
                    'approver_2_id' => $user->id
                ]);

                // 2. --- ส่วนการแจ้งเตือนกลับไปหาพนักงาน ---
                if ($document->user) {
                    // ส่ง Notification แจ้งผลว่าเอกสารได้รับการอนุมัติแล้ว
                    $document->user->notify(new \App\Notifications\DocumentStatusUpdatedNotification($document));
                }

                return back()->with('success', 'อนุมัติเอกสารเรียบร้อยแล้ว ระบบได้ส่งแจ้งเตือนกลับไปยังพนักงานเจ้าของเอกสาร');
            }

        } elseif ($request->status == 'rejected') {
            // หากมีการปฏิเสธในขั้นตอนใดก็ตาม ให้ตัดจบเป็นปฏิเสธทันที
            $document->update([
                'status' => 'rejected',
                'reject_reason' => $request->has('reject_reason') ? $request->reject_reason : null
            ]);

            if ($document->user) {
                if (class_exists('\App\Notifications\DocumentStatusUpdatedNotification')) {
                    $document->user->notify(new \App\Notifications\DocumentStatusUpdatedNotification($document));
                }
            }

            return back()->with('success', 'ปฏิเสธคำขออนุมัติเรียบร้อยแล้ว');
        }

        return back();
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        
        if ($document->status == 'approved') {
            return back()->with('error', 'ไม่อนุญาตให้ลบเอกสารที่ผ่านการอนุมัติเรียบร้อยแล้ว!');
        }
        
        if (File::exists(public_path('uploads/documents/' . $document->file_name))) {
            File::delete(public_path('uploads/documents/' . $document->file_name));
        }

        $document->delete();

        return back()->with('success', 'ลบเอกสารออกจากระบบเรียบร้อยแล้ว');
    }
}