<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TopicReplyNotification extends Notification
{
    use Queueable;

    public $reply;
    public $type;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type,Reply $reply)
    {
        $this->reply = $reply;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     * 开通频道
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

        //存入数据
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,
        ];
    }
}
