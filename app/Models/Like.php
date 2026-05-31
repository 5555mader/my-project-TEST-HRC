<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    /**
     * กำหนดฟิลด์ที่อนุญาตให้บันทึกข้อมูลแบบ Mass Assignment
     */
    protected $fillable = [
        'post_id', 
        'user_ip'
    ];

    /**
     * ความสัมพันธ์ย้อนกลับไปยัง Model Announcement (หรือ Post)
     * การกดถูกใจแต่ละครั้งจะเป็นของประกาศใดประกาศหนึ่ง
     */
    public function announcement()
    {
        return $this->belongsTo(Announcement::class, 'post_id');
    }
}