<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ReplyRequest;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function userIndex(User $user)
    {
        $replices = $user->replies()->paginate(15);
        return ReplyResource::collection($replices);
    }
    public function index(Topic $topic)
    {
        $replices = $topic->replies()->latest()->paginate(15);
        return ReplyResource::collection($replices);
    }
    public function store(ReplyRequest $request,Topic $topic,Reply $reply)
    {
        $reply->content = $request->content;
        $reply->topic_id = $topic->id;
        $reply->user_id = $this->user()->id;
        $reply->save();
        return new ReplyResource($reply);
    }

    public function destroy(Topic $topic, Reply $reply)
    {
        if ($reply->topic_id != $topic->id) {
            return $this->response->errorBadRequest();
        }
        $this->authorize('destroy', $reply);
        $reply->delete();

        return $this->response->noContent();
    }
}
