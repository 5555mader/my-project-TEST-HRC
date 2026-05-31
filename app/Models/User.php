<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'department', 
        'position', 
        'role', 
        'branch',
        'phone',     
        'address',   
        'image',
        'signature'  // 🌟 เพิ่มบรรทัดนี้เข้าไป 🌟
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- Helper Methods ---

    public function isCEO()
    {
        return $this->role === 'CEO';
    }

    public function isDirector()
    {
        return $this->role === 'Director'; // สมมติว่าเก็บค่าเป็นคำว่า Director
    }

    public function isManagerial()
    {
        // รวมสิทธิ์ระดับบริหารทั้งหมดไว้ที่นี่ที่เดียว
        return in_array($this->role, ['Manager', 'HR Manager', 'Super Admin', 'Director', 'CEO']);
    }
}