<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationsResource;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type;

        $unreadNotifications = $this->user->unreadNotifications()->whereType($type)->count();
        $notificationsQuery = $unreadNotifications? $this->user->unreadNotifications():$this->user->notifications();

        $notifications = $notificationsQuery->whereType($type)->latest()->paginate(20);

        return NotificationsResource::collection($notifications);
    }

    public function stats()
    {
        return $this->response->array([
            'unread_count' => $this->user()->notification_count,
//            'unreadReply_count' => $this->user()->whereType('reply')->notification_count,
            'unreadTopicFavorite_count' => $this->user()->topic_notification_count,
            'unreadReplyFavorite_count' => $this->user()->reply_notification_count,
        ]);
    }

    public function read()
    {
        $type = request('type');

//        $this->notifications()->whereNull('read_at');
//        foreach ($this->unreadNotifications as $notification) {
//            $notification->markAsRead();
//        }
//        $this->user->unreadNotifications()->each(function ($notification) {
////            $notification->markAsRead();
//            return $notification;
//        });
        $this->user->unreadNotifications()->whereType($type)->update(['read_at' => now()]);
        $this->user()->markAsRead();

        return $this->response->noContent();
    }
}
