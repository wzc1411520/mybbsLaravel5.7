<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function show(User $user)
    {
//        dd($this->getActivity($user));
//        $users = $user->with('topics')->get();
//        dd($user->topics()->get());
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request,User $user,ImageUploadHandler $uploader)
    {
        $this->authorize('update', $user);
        $data = $request->all();

        if ($request->avatar) {
            $path = $uploader->save($request->avatar, 'avatars', $user->id);
//
            if ($path) {
                $data['avatar'] = $path;
            }
        }
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }

    protected function getActivity(User $user)
    {
        return $user->favorites()->latest()->with('favorited')->get()->toArray();
    }
}
