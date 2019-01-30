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
            "created_at"=> $this->created_at->toDateTimeString(),
            "updated_at"=> $this->updated_at->toDateTimeString(),
            "avatar"=> config('app.url').$this->avatar,
            "introduction"=> $this->introduction,
            "notification_count"=>$this->notification_count,
            'meta'=>[
                'access_token' => \Auth::guard('api')->fromUser(\Auth::guard('api')->user()),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ]
        ];
    }
}
