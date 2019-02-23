<?php

namespace App\Notifications;

use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TopicFavoriteNotification extends Notification
{
    use Queueable;

    public $topic;
    public $user;
    //通知类型
    public $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type,Topic $topic,User $user)
    {
        $this->topic = $topic;
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $topic = $this->topic;
        $link =  $topic->link();
        return [
            'type'=>'topic',
            'topic_id'=>$this->topic->id,
            'topic_title'=>$this->topic->title,
            'topic_link' => $link,
            'favorite_user_id'=>$this->user->id,
            'favorite_user_name'=>$this->user->name,
            'favorite_user_avatar'=>storage_url($this->user->avatar),
            "created_at"=> Carbon::now(),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
