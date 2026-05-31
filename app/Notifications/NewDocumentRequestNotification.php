<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewDocumentRequestNotification extends Notification
{
    use Queueable;
    protected $docTitle;

    public function __construct($docTitle) {
        $this->docTitle = $docTitle;
    }

    public function via(object $notifiable): array {
        return ['database'];
    }

    public function toArray(object $notifiable): array {
        return [
            'title' => 'คำขออนุมัติเอกสาร/บันทึกภายใน',
            'message' => 'มีเอกสารเรื่อง "' . $this->docTitle . '" รอการอนุมัติ',
            'link' => route('manager.approvals'),
            'type' => 'document'
        ];
    }
}