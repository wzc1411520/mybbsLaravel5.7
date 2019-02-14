<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    public function userIndex(User $user,Request $request)
    {
        $topics = $user->topics()->recent()
            ->paginate(20);
        return TopicResource::collection($topics);
    }
    public function index(Request $request,Topic $topic)
    {
        $topics = $topic->withOrder($request->order)->where(function ($query)use($request){
            if ($request->category_id&&$request->category_id!=0) {
                $query->where('category_id', $request->category_id);
            }
        })->paginate(20);

        return TopicResource::collection($topics);
    }

    public function store(TopicRequest $request,Topic $topic)
    {
        $str = '<div>';
//        $topic->fill($request->all());
        foreach ($request->avatar as $v){
            $str.="<img src='$v' width='200'/>";
        }
        $str .= "</div>";
        $topic->title = $request->title;
        $topic->category_id = $request->category_id;
        $topic->body = '<div>'.$request->content.'</div>'.$str;
        $topic->user_id = $this->user()->id;
        $topic->save();
        return new TopicResource($topic);
    }

    public function update(TopicRequest $request,Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());
        return new TopicResource($topic);
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);

        $topic->delete();
        return $this->response->noContent();
    }

    public function show(Topic $topic)
    {
        $topic->increment('view_count');
        $topic->save();
        return new TopicResource($topic);
    }
}
