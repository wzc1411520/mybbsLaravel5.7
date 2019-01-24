@include('shared._messages')
@if(Auth::check())
<div class="reply-box">
    <form action="{{ route('replies.store') }}" method="POST" accept-charset="UTF-8">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="topic_id" value="{{ $topic->id }}">
        <div class="form-group">
            <textarea class="form-control" rows="3" placeholder="分享你的见解~" name="content"></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share mr-1"></i> 回复</button>
    </form>
</div>
@else
    <div class="reply-box">
        <p class="text-center">请先<a href="{{ route('login') }}" style="color:#0e9aef ">登录</a>，然后再发表 </p>
    </div>
@endif
<hr>
