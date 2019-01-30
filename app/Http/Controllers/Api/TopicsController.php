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
            if ($categoryId = $request->category_id) {
                $query->where('category_id', $categoryId);
            }
        })->paginate(20);

        return TopicResource::collection($topics);
    }

    public function store(TopicRequest $request,Topic $topic)
    {
        $topic->fill($request->all());
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
        return new TopicResource($topic);
    }
}