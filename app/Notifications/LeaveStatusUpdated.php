<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveStatusUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // สามารถรับค่า $leave เข้ามาตรงนี้ได้ถ้าต้องการระบุรายละเอียดใบลาเพิ่มเติม
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // สำคัญ: กำหนดให้บันทึกลงตาราง notifications ใน Database
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // โครงสร้างข้อมูลที่ NotificationController รอรับเพื่อนำไปแสดงผลที่กระดิ่ง
        return [
            'title' => 'อัปเดตสถานะการลา',
            'message' => 'คำขอลาของคุณได้รับการตรวจสอบและอัปเดตสถานะแล้ว',
            'link' => route('ess.leave'), // กดแล้วให้ไปหน้าประวัติการลา
            'type' => 'leave' // ชนิดไอคอนที่จะแสดง (อ้างอิงจากฟังก์ชัน getIcon ใน JavaScript)
        ];
    }
}