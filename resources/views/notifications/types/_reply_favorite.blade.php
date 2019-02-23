<li class="media @if ( ! $loop->last) border-bottom @endif">
    <div class="media-left">
        <a href="{{ route('users.show', $notification->data['favorite_user_id']) }}">
            <img class="media-object img-thumbnail mr-3" alt="{{ $notification->data['favorite_user_name'] }}" src="{{ $notification->data['favorite_user_avatar'] }}" style="width:48px;height:48px;" />
        </a>
    </div>

    <div class="media-body">
        <div class="media-heading mt-0 mb-1 text-secondary">
            <a href="{{ route('users.show', $notification->data['favorite_user_id']) }}">{{ $notification->data['favorite_user_name'] }}</a>
            👍赞了
            <a href="{{ $notification->data['topic_link'] }}">{{ $notification->data['topic_title'] }}</a>
            评论:{{ $notification->data['reply_content'] }}


            {{-- 回复删除按钮 --}}
            <span class="meta float-right" title="{{ $notification->created_at }}">
                <i class="far fa-clock"></i>{{ $notification->created_at->diffForHumans() }}
            </span>
        </div>
        {{--<div class="reply-content">--}}
            {{--{!! $notification->data['reply_content'] !!}--}}
        {{--</div>--}}
    </div>
</li>