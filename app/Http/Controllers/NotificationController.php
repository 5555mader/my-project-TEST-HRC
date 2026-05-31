<?php
// app/Http/Controllers/NotificationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $user = Auth::user();
        return response()->json([
            'unread_count' => $user->unreadNotifications->count(),
            'notifications' => $user->notifications()->take(5)->get()->map(function($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->data['title'],
                    'message' => $n->data['message'],
                    'link' => route('notifications.read', $n->id),
                    'created_at' => $n->created_at->diffForHumans(),
                    'read_at' => $n->read_at,
                    'type' => $n->data['type']
                ];
            })
        ]);
    }

    public function read($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect($notification->data['link']);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success']);
    }

    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        return response()->json(['status' => 'success']);
    }
}