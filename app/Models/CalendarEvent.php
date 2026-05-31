<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    // ต้องเพิ่มบรรทัดนี้ เพื่ออนุญาตให้ Insert ข้อมูลเหล่านี้ลง Database ได้
    protected $fillable = ['title', 'start', 'end', 'color', 'description', 'target_department'];
}