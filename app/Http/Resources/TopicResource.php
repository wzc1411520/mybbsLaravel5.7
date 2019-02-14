<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type  =  explode(',',$request->include);
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'user' =>in_array('user',$type)?new UserResource($this->user):$this->user_id,
            'category' => in_array('category',$type)?new CategoryResource($this->category):$this->category_id,
            'reply_count' => (int) $this->reply_count,
            'reply'=>in_array('reply',$type)?ReplyResource::collection($this->topicReplies):'',
            'view_count' => (int) $this->view_count,
            'last_reply_user_id' => (int) $this->last_reply_user_id,
            'isFavorited' => in_array('isFavorited',$type)?!!$this->favorites->where('user_id',$request->userId)->count():'',
            'favoritesCount' => in_array('isFavorited',$type)?$this->favoritesCount:'',
            'excerpt' => $this->excerpt,
            'slug' => $this->slug,
            'user_role' =>in_array('role',$type)?RoleResource::collection($this->user->roles):'',
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
