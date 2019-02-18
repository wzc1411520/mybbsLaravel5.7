<?php

namespace App\Listeners;

use App\Events\ReplyFavoriteEvents;
use App\Events\TopicFavoriteEvents;
use App\Notifications\ReplyFavoriteNotification;
use App\Notifications\TopicFavoriteNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class FavoriteEventListeners
{
    public function onStoreTopicFavorite($event){
//        Log::info('监听自定义事件StoreTopicFavorite');
        $event->topic->user->notify(new TopicFavoriteNotification('topic_favorite',$event->topic,$event->user));
        // 通知作者话题被回复了
//        $event->topic->user->notify(new TopicFavorite($event->topic));
    }
    public function onStoreReplyFavorite($event){
        // 通知作者话题被回复了
        $event->reply->user->notify(new ReplyFavoriteNotification('reply_favorite',$event->reply,$event->user));
    }

    /**
     * 为订阅者注册监听器
     *
     * @param  Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ReplyFavoriteEvents::class,
            FavoriteEventListeners::class . '@onStoreReplyFavorite'
        );

        $events->listen(
            TopicFavoriteEvents::class,
            FavoriteEventListeners::class . '@onStoreTopicFavorite'
        );
    }
}
