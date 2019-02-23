<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    public function userIndex()
    {
        $category =  $this->user->categories()->withCount('topics')->orderBy('sort')->get();
        return CategoryResource::collection($category);
    }

    public function store(CategoryRequest $request,Category $category)
    {
        $category->name = $request->name;
        $category->user_id = $this->user->id;
        $category->save();
        return new CategoryResource($category);
    }
}
