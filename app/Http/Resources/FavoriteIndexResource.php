<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $include = strtolower($request->include);
        $type = strtolower(class_basename($this->favorited_type));
        $query=[];
        if('topic'==$include||$type == 'topic'){
            $query=[
                'type'=>'topic',
                'topic_id'=>$this->favorited->id,
                'topic_title'=>$this->favorited->title,
                'topic_user_id'=>$this->favorited->user->id,
                'topic_user_avatar'=>storage_url($this->favorited->user->avatar),
                "created_at"=>$this->created_at->diffForHumans(),
            ];
        }
        if('reply'==$include||$type=='reply'){
            $query=[
                'type'=>'reply',
                'topic_id'=>$this->favorited->topic_id,
                'topic_title'=>$this->favorited->topic->title,
                'reply_id'=>$this->favorited->id,
                'reply_user_id'=>$this->favorited->user_id,
                'reply_user_avatar'=>storage_url($this->favorited->user->avatar),
                'reply_content'=>$this->favorited->content,
                "created_at"=>$this->created_at->diffForHumans(),
            ];
        }
        return $query;
    }
}
