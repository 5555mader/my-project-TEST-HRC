<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'category', 'file_name', 'file_size', 'file_extension',
        'user_id', 'doc_number', 'branch', 'department', 'to_position',
        'content', 'approver_id', 'approver_2_id', 'status', 
        'cc_users', 'reject_reason', 'amount'
    ];

    protected $casts = [
        'cc_users' => 'array',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function approver() 
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function approver2() 
    {
        return $this->belongsTo(User::class, 'approver_2_id');
    }

    public function files()
    {
        return $this->hasMany(DocumentFile::class, 'document_id');
    }

    /**
     * 🌟 เพิ่ม Accessor สำหรับจัดกลุ่มบันทึกภายในตามเรื่องที่ขออนุมัติ
     */
    public function getSubCategoryGroupAttribute()
    {
        $title = $this->title;

        // ดักจับกรณีเป็น "อื่นๆ (รายละเอียด...)" ให้เข้ากลุ่มหนังสือแจ้ง
        if (str_starts_with($title, 'อื่นๆ')) {
            return 'หนังสือแจ้ง / อื่นๆ';
        }

        $groups = [
            'จัดซื้อ / เบิกจ่าย / การเงิน' => [
                'ขอใบเสนอราคา (SO)', 'ขอ Quatation', 'ขอใบอนุมัติจัดซื้อ/จ้าง (PR)', 
                'ขอใบสั่งซื้อ/จ้าง (PO)', 'ขอเบิกจ่าย', 'ขอเบิกเงินทดรองจ่าย', 
                'ขอเบิกค่า Commission, Incentive', 'ขอคืนเงินประกันตามสัญญา'
            ],
            'นิติการ / สัญญา' => [
                'ขอทำสัญญาจ้าง/พัฒนา/ซื้อขาย', 'ขอส่งมอบงานตามสัญญา', 
                'ขอหนังสือมอบอำนาจทั่วไป', 'ขอหนังสือมอบอำนาจที่มีภาระผูกพันบริษัท'
            ],
            'บุคคล / ธุรการ / สถานที่' => [
                'ขออัตรากำลังคน', 'ขอบุคลากรร่วมงาน', 'ขอฝึกอบรมพัฒนาบุคลากร', 
                'ขอศึกษา/ดูงาน', 'ขอซ่อมบำรุง/อาคาร/สถานที่'
            ],
            'ไอที / พัฒนาระบบ' => [
                'ขอ Project Code Name', 'ขอพัฒาระบบโปรแกรม', 'ขอเปิดระบบทดลองใช้งาน'
            ],
            'ขออนุมัติจัดทำโครงการ' => [
                'ขอทำโครงการ ITI', 'ขอทำโครงการ (การตลาด)', 'ขอทำโครงการ (การเงิน)', 
                'ขอทำโครงการ (บัญชี)', 'ขอทำโครงการ (กฎหมาย)', 'ขอทำโครงการ (จัดซื้อ)', 
                'ขอทำโครงการ (ธุรการ)', 'ขอทำโครงการ (ตรอ.)', 'ขอทำโครงการ (โรงเรียน)'
            ],
            'ตรวจสอบภายใน (Internal Audit)' => [
                'Internal Audit ฝ่ายมาตราฐาน', 'Internal Audit ฝ่าย IDC (ITIและการตลาด)', 
                'Internal Audit ฝ่าย AC (บัญชี)', 'Internal Audit ฝ่าย CD (ตรอ.)', 
                'Internal Audit ฝ่าย IDD (โรงเรียน)'
            ],
            'หนังสือแจ้ง / อื่นๆ' => [
                'ขอจดหมาย', 'แจ้งเพื่อทราบ', 'แจ้งเพื่อทราบและดำเนินการด้วย', 'อื่นๆ'
            ]
        ];

        foreach ($groups as $groupName => $titles) {
            if (in_array($title, $titles)) {
                return $groupName;
            }
        }

        return 'หนังสือแจ้ง / อื่นๆ';
    }
}