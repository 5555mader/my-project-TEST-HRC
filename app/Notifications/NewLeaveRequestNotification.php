<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLeaveRequestNotification extends Notification
{
    use Queueable;
    protected $employeeName;

    public function __construct($employeeName) {
        $this->employeeName = $employeeName;
    }

    public function via(object $notifiable): array {
        return ['database'];
    }

    public function toArray(object $notifiable): array {
        return [
            'title' => 'คำขออนุมัติการลาใหม่',
            'message' => 'คุณ ' . $this->employeeName . ' ได้ส่งคำขอลาเพื่อรอการตรวจสอบ',
            'link' => route('manager.approvals'),
            'type' => 'leave'
        ];
    }
}