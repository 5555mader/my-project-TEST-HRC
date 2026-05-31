<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
    'title', 'category', 'file_name', 'file_size', 'file_extension',
    'user_id', 'doc_number', 'branch', 'department', 'to_position', // เพิ่ม 'branch' ตรงนี้
    'content', 'approver_id', 'approver_2_id', 'status', 
    'cc_users', 'reject_reason', 'amount'
];
    /**
     * แปลงชนิดข้อมูล (Casting) อัตโนมัติ
     * เนื่องจากฟอร์มส่งข้อมูลมาเป็น Array และเรามักจะบันทึกเป็น JSON ในฐานข้อมูล
     */
    protected $casts = [
        'cc_users' => 'array',
    ];

    /**
     * ความสัมพันธ์เชื่อมไปที่ User (ผู้ส่งเอกสาร/เจ้าของเอกสาร)
     */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ความสัมพันธ์เชื่อมไปที่ User (ผู้อนุมัติเอกสาร)
     * โดยอ้างอิงผ่านคอลัมน์ approver_id
     */
    public function approver() 
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * ความสัมพันธ์เชื่อมไปที่ User (ผู้อนุมัติเอกสารคนที่ 2)
     * โดยอ้างอิงผ่านคอลัมน์ approver_2_id
     */
    public function approver2() 
    {
        return $this->belongsTo(User::class, 'approver_2_id');
    }

    /**
     * ความสัมพันธ์เชื่อมไปที่ DocumentFile (ไฟล์แนบเอกสาร)
     */
    public function files() 
    {
        return $this->hasMany(DocumentFile::class, 'document_id');
    }
}