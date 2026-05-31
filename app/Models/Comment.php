<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // กำหนดฟิลด์ที่อนุญาตให้บันทึกข้อมูล
    protected $fillable = [
        'post_id',
        'author_name',
        'comment_text'
    ];

    // เชื่อมกลับไปยัง Model Announcement (หรือ Post)
    public function announcement()
    {
        return $this->belongsTo(Announcement::class, 'post_id');
    }
}