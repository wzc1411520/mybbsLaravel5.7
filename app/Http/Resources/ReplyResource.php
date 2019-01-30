<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type = explode(',',$request->include);

        return [
            'id' => $this->id,
            'user' => in_array('user',$type)?new UserResource($this->user):$this->user_id,
            'topic' =>in_array('topic',$type)? new TopicResource($this->topic):$this->topic_id,
            'content' => $this->content,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
