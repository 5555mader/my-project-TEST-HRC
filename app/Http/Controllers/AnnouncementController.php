<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement; 
use App\Models\Like;    
use App\Models\Comment; 
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Branch; 
use App\Models\Department;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewAnnouncementNotification;

class AnnouncementController extends Controller
{
    /**
     * แสดงรายการประกาศพร้อมระบบค้นหาและตัวกรองตามสิทธิ์ สาขา/แผนก ของพนักงาน
     */
    public function index(Request $request)
    {
        $query = Announcement::latest(); // เรียกใช้โมเดลโพสประกาศ

        // ตรวจสอบสิทธิ์การมองเห็นพนักงานทั่วไป (ยกเว้นสิทธิ์บริหาร เช่น Super Admin หรือ HR Manager ให้เห็นหมด)
        if (Auth::check()) {
            $user = Auth::user();
            
            // เช็คสิทธิ์ด้วยระบบใหม่ (หากไม่มีสิทธิ์ HR Manager และ Super Admin พ่วงอยู่ด้วย จะถูกกรองตามสาขา/แผนกตนเอง)
            if (!str_contains($user->role, 'HR Manager') && !str_contains($user->role, 'Super Admin')) { 
                
                // เงื่อนไขสาขา: ต้องเปิดให้ "ทั้งหมด" หรือตรงกับสาขาที่พนักงานสังกัด
                $query->where(function($q) use ($user) {
                    $q->where('target_branch', 'ทั้งหมด')
                      ->orWhere('target_branch', $user->branch);
                });

                // เงื่อนไขแผนก: ต้องเปิดให้ "ทั้งหมด" หรือตรงกับแผนกที่พนักงานสังกัด
                $query->where(function($q) use ($user) {
                    $q->where('target_department', 'ทั้งหมด')
                      ->orWhere('target_department', $user->department);
                });
            }
        } else {
            // กรณีผู้เยี่ยมชมที่ไม่ได้ล็อกอินระบบ ให้เห็นเฉพาะประกาศที่เป็นสาธารณะ (ทั้งหมด)
            $query->where('target_branch', 'ทั้งหมด')
                  ->where('target_department', 'ทั้งหมด');
        }

        // คงลอจิกการค้นหาคำและกรองหมวดหมู่เดิมที่มีอยู่แล้วบนหน้าเว็บไว้ ไม่ให้ฟังก์ชันค้นหาพัง
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        // ดึงข้อมูลส่งไปที่หน้าเว็บ (ทำ Eager Loading และผูกตัวแปรคู่ขนานเพื่อให้เข้ากับหน้า welcome.blade.php)
        $posts = $query->with(['likes', 'comments'])->get();
        $important_posts = $posts;
        
        $branches = Branch::all(); // สำหรับส่งไปให้หน้า welcome.blade.php แสดงใน Dropdown

        return view('welcome', compact('posts', 'important_posts', 'branches'));
    }

    /**
     * แสดงหน้าต่างสร้างประกาศ (ถ้ามีแยกไฟล์)
     */
    public function create() {
        return view('admin.announcements_create'); 
    }

    /**
     * บันทึกข้อมูลประกาศใหม่ลงฐานข้อมูล พร้อมส่งแจ้งเตือนภัยพนักงาน
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'target_branch' => 'required|string',
            'target_department' => 'required|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx|max:5120', 
        ]);

        $data = $request->all();

        // จัดการอัปโหลดไฟล์แนบภาพหรือเอกสาร (ถ้ามี)
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('uploads/announcements'), $imageName);
            $data['image'] = $imageName; 
        }

        // 定 กำหนดชื่อผู้โพสต์ประกาศตาม Account ของผู้ใช้งานปัจจุบันโดยอัตโนมัติ
        $data['author'] = Auth::user()->name; 

        $post = Announcement::create($data);

        // ส่งระบบการแจ้งเตือน (Notification) ไปหาพนักงานทุกคนในระบบตามโครงสร้างเดิม
        $allUsers = User::all();
        Notification::send($allUsers, new NewAnnouncementNotification($post->title));

        return redirect()->route('welcome')->with('success', 'สร้างประกาศและส่งข้อมูลไปยังกลุ่มเป้าหมายสำเร็จแล้ว!');
    }

    /**
     * ระบบกดถูกใจประกาศ (Like)
     */
    public function like($id) {
        Like::firstOrCreate([
            'post_id' => $id,
            'user_ip' => request()->ip()
        ]);
        return back();
    }

    /**
     * ระบบแสดงความคิดเห็นใต้ประกาศ (Comment)
     */
    public function comment(Request $request, $id) {
        $request->validate([
            'comment_text' => 'required'
        ]);

        Comment::create([
            'post_id' => $id,
            'author_name' => Auth::user()->name ?? 'Guest',
            'comment_text' => $request->comment_text
        ]);

        return back()->with('success', 'แสดงความคิดเห็นเรียบร้อยแล้ว!');
    }

    /**
     * หน้าแก้ไขประกาศ
     */
    public function edit($id) {
        $post = Announcement::findOrFail($id);

        // เช็คสิทธิ์ก่อนให้เข้าถึงหน้าแก้ไข
        if (Auth::user()->name !== $post->author && !str_contains(Auth::user()->role, 'Super Admin')) {
            return redirect()->route('welcome')->with('error', 'คุณไม่มีสิทธิ์แก้ไขโพสต์นี้');
        }

        return view('admin.announcements_edit', compact('post'));
    }

    /**
     * อัปเดตข้อมูลการแก้ไขประกาศ
     */
    public function update(Request $request, $id) {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
        ]);

        $post = Announcement::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            // ลบรูปภาพ/ไฟล์เก่าออกก่อนถ้ามีการอัปโหลดไฟล์ชุดใหม่เข้ามาแทนที่
            if ($post->image) {
                File::delete(public_path('uploads/announcements/' . $post->image));
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/announcements'), $imageName);
            $data['image'] = $imageName;
        }

        $post->update($data);
        return redirect()->route('welcome')->with('success', 'อัปเดตประกาศเรียบร้อยแล้ว!');
    }

    /**
     * ลบข้อมูลประกาศออกจากระบบ
     */
    public function destroy($id) {
        $post = Announcement::findOrFail($id);
        
        // ลบรูปภาพหรือไฟล์เอกสารออกจาก Folder เซิร์ฟเวอร์
        if ($post->image) {
            File::delete(public_path('uploads/announcements/' . $post->image));
        }
        $post->delete();
        return back()->with('success', 'ลบประกาศเรียบร้อยแล้ว!');
    }
}