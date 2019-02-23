<?php

namespace App\Notifications;

use App\Models\Reply;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReplyFavoriteNotification extends Notification
{
    use Queueable;

    public $reply;
    public $user;
    public $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type,Reply $reply,User $user)
    {
        $this->reply = $reply;
        $this->user = $user;
        $this->type =  $type;
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
        $topic = $this->reply->topic;
        $link  = $topic->link(['#reply'.$this->reply->id]);
        return [
            'type'=>'reply',
            'topic_id'=>$this->reply->topic_id,
            'topic_title'=>$this->reply->topic->title,
            'topic_link' => $link,
            'reply_content'=>$this->reply->content,
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
