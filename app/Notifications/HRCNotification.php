<?php
// app/Notifications/HRCNotification.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class HRCNotification extends Notification
{
    use Queueable;

    protected $details;

    public function __construct($details)
    {
        // $details = ['title' => '...', 'message' => '...', 'link' => '...', 'type' => 'leave|ot|payroll|document']
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->details['title'],
            'message' => $this->details['message'],
            'link' => $this->details['link'],
            'type' => $this->details['type'] ?? 'info',
        ];
    }
}