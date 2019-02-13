<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"=> $this->id,
            "name"=> $this->name,
            "phone"=>$this->phone,
            "email"=> $this->email,
            "bound_weixin"=> $this->weixin_unionid??$this->weixin_openid??false,
            "bound_phone"=> $this->phone??false,
            "bound_email"=> $this->email_verified_at??false,
            "last_actived_at"=>$this->last_actived_at->diffForHumans(),
            "created_at"=> $this->created_at->diffForHumans(),
            "updated_at"=> $this->updated_at->diffForHumans(),
            "avatar"=> storage_url($this->avatar),
            "introduction"=> $this->introduction??'ta很懒,什么也没有写',
            "notification_count"=>$this->notification_count,
            'permission' => $request->include == 'permission'?PermissionResource::collection($this->getAllPermissions()):'',
        ];
    }
}
