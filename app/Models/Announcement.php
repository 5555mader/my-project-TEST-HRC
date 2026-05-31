<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    // บังคับให้ใช้ตาราง posts แทน announcements เพื่อให้ตรงกับโครงสร้างคอลัมน์ (เช่น author)
    protected $table = 'posts'; 

    // กำหนดฟิลด์ที่อนุญาตให้บันทึกข้อมูลแบบ Mass Assignment
    protected $fillable = [
    'title', 'content', 'category', 'author', 'image',
    'target_branch', 'target_department' // เพิ่ม 2 ฟิลด์นี้
];

    /**
     * ความสัมพันธ์กับตาราง likes
     * หนึ่งประกาศสามารถมีการกดถูกใจได้หลายครั้ง
     */
    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }

    /**
     * ความสัมพันธ์กับตาราง comments
     * หนึ่งประกาศสามารถมีคอมเมนต์ได้หลายรายการ
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
}