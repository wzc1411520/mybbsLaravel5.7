<?php

namespace App\Observers;

use App\Models\Reply;
use App\Models\Topic;
use App\Notifications\TopicReplied;
use App\Notifications\TopicReplyNotification;
use Illuminate\Support\Facades\Log;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{

    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }
    public function created(Reply $reply)
    {
        $reply->topic->updateReplyCount();
        // 通知作者话题被回复了
        $reply->topic->user->notify(new TopicReplyNotification('reply',$reply));
    }

    public function updating(Reply $reply)
    {
        //
    }

    public function deleted(Reply $reply)
    {
        $reply->topic->decrement('reply_count',1);
    }
}