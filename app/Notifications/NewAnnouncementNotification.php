<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewAnnouncementNotification extends Notification
{
    use Queueable;
    protected $title;

    public function __construct($title) {
        $this->title = $title;
    }

    public function via(object $notifiable): array {
        return ['database'];
    }

    public function toArray(object $notifiable): array {
        return [
            'title' => 'ประกาศใหม่จากบริษัท',
            'message' => 'มีประกาศใหม่เรื่อง: ' . $this->title,
            'link' => route('welcome'),
            'type' => 'document' // ใช้ไอคอนเอกสาร/ข่าวสาร
        ];
    }
}