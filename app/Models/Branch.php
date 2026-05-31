<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    // เพิ่มฟิลด์ที่อนุญาตให้บันทึกข้อมูล
    protected $fillable = ['name', 'location'];
}