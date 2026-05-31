<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentStatusUpdatedNotification extends Notification
{
    use Queueable;

    public $document;

    /**
     * Create a new notification instance.
     * รับค่า object document เข้ามาเพื่อใช้ในการแจ้งเตือน
     */
    public function __construct($document)
    {
        $this->document = $document;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // ลบ 'mail' ออก
        return ['database']; 
    }

    /**
     * Get the mail representation of the notification.
     * (ยังคงเก็บโค้ดนี้ไว้เผื่อต้องการเปิดใช้งานอีเมลอีกครั้งในอนาคต)
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('แจ้งเตือนสถานะบันทึกข้อความ')
                    ->line('บันทึกข้อความเรื่อง "' . $this->document->title . '" ของคุณได้รับการเปลี่ยนแปลงสถานะแล้ว')
                    ->action('ตรวจสอบสถานะเอกสาร', route('admin.archives.show-form', $this->document->id))
                    ->line('ขอบคุณที่ใช้งานระบบของเรา');
    }

    /**
     * Get the database representation of the notification.
     * ใช้สำหรับบันทึกลงในฐานข้อมูลโดยเฉพาะ
     */
    public function toDatabase($notifiable)
    {
        // ตรวจสอบสถานะเอกสารเพื่อให้ข้อความแจ้งเตือนตรงกับความเป็นจริง
        $statusMessage = $this->document->status == 'approved' 
            ? 'ได้รับการอนุมัติแล้ว' 
            : 'ถูกปฏิเสธการอนุมัติ';

        return [
            'title' => 'อัปเดตสถานะบันทึกข้อความ',
            'message' => 'บันทึกข้อความเรื่อง "' . $this->document->title . '" ของคุณ' . $statusMessage,
            'link' => route('admin.archives.show-form', $this->document->id), 
            // แก้ไข type ให้ตรงกับฟังก์ชัน getIcon ใน ess.blade.php
            'type' => 'document' 
        ];
    }
}