<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationsResource;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = $this->user->notifications()->paginate(20);

        return NotificationsResource::collection($notifications);
    }

    public function stats()
    {
        return $this->response->array([
            'unread_count' => $this->user()->notification_count,
        ]);
    }

    public function read()
    {
        $this->user()->markAsRead();

        return $this->response->noContent();
    }
}
