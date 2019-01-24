@if (count($favorites))

    <ul class="list-group mt-4 border-0 text-secondary">
        @foreach ($favorites as $favorite)
            <li class="list-group-item pl-2 pr-2 border-right-0 border-left-0 @if($loop->first) border-top-0 @endif">
                @if($favorite->favorited_type == 'App\Models\Topic')
                对话题<a href="{{ $favorite->favorited->link() }}">
                    {{ $favorite->favorited->title }}
                </a>进行了点赞<i class="fas fa-thumbs-up"></i>
                <span class="meta float-right">
                    {{ $favorite->created_at->diffForHumans() }}
                </span>
                @else
                    话题<a href="{{ route('topics.show', $favorite->favorited->topic()->value('id')) }}">
                        {{ $favorite->favorited->topic()->value('title') }}
                    </a>
                    <div class="reply-content mt-2 mb-2">
                        <a href="{{ route('users.show', $favorite->favorited->user()->value('id')) }}">
                            <i class="far fa-user"></i>{{$favorite->favorited->user()->value('name')}}
                        </a>的回复:{{ $favorite->favorited->content }} 进行了点赞<i class="fas fa-thumbs-up"></i>
                    </div>

                    <span class="meta float-right">
                    {{ $favorite->created_at->diffForHumans() }}
                    </span>
                @endif

            </li>
        @endforeach
    </ul>

@else
    <div class="empty-block">暂无数据 ~_~ </div>
@endif

{{-- 分页 --}}
<div class="mt-4 pt-1">
    {!! $favorites->appends(Request::except('page'))->render() !!}
</div>