<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\ActiveUserResource;
use App\Http\Resources\AuthorizationsResource;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function activedIndex(User $user)
    {
        $activedUser = $user->getActiveUsers();
        return UserResource::collection($activedUser);
    }

    public function me()
    {
        $user = $this->user();
        return (new UserResource($this->user()))->additional(['meta' => [
            'access_token' => \Auth::guard('api')->fromUser($user),
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]]);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }
    //编辑个人资料
    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'email', 'introduction','registration_id']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);

        return new UserResource($user);
    }

    public function weChatStore(UserRequest $request)
    {
        // 缓存中是否存在对应的 key
        $verifyData = \Cache::get($request->verification_key);

        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }
        // 判断验证码是否相等，不相等反回 401 错误
        if (!hash_equals((string)$verifyData['code'], $request->verification_code)) {
            return $this->response->errorUnauthorized('验证码错误');
        }

        // 获取微信的 openid 和 session_key
        $miniProgram = \EasyWeChat::miniProgram();
        $data = $miniProgram->auth->session($request->code);

        if (isset($data['errcode'])) {
            return $this->response->errorUnauthorized('code 不正确');
        }

        // 如果 openid 对应的用户已存在，报错403
        $user = User::where('weapp_openid', $data['openid'])->first();

        if ($user) {
            return $this->response->errorForbidden('微信已绑定其他用户，请直接登录');
        }
        // 创建用户
        $user = User::create([
            'name' => $request->wechatuserInfo['userInfo']['nickName'],
            'avatar' => $request->wechatuserInfo['userInfo']['avatarUrl'],
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password),
            'weapp_openid' => $data['openid'],
            'weixin_session_key' => $data['session_key'],
        ]);
        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        $token = \Auth::guard('api')->fromUser($user);

        return new AuthorizationsResource(['access_token' => $token,'userInfo'=>$user]);
    }
}
