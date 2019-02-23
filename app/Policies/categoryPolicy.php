<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class categoryPolicy
{
    public function store(User $user, Category $category)
    {
        return $user->isAuthorOf($category);
    }
}
