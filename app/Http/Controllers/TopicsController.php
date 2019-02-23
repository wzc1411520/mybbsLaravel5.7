<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request)
	{
		$topics = Topic::withOrder($request->order)->paginate();
//        $links = $link->getAllCached();
//        view()->share('categories',$categories);
		return view('topics.index', compact('topics'));
	}

    public function show(Request $request,Topic $topic,User $user)
    {
         //URL 矫正
        if ( ! empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }
        //用戶看過那些話題
        $user->read($topic);
        //记录话题的浏览量
        $topic->recordVisit();
        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function store(TopicRequest $request,Topic $topic,Category $category)
	{
        $topic->fill($request->all());
	    if($request->categoryType == 'add'){
	        $category->description = $request->description;
	        $category->name = $request->category;
	        $category->user_id = \Auth::id();
	        $category->save();


            $topic->category_id = $category->id;
        }
        $topic->user_id = \Auth::id();
        $topic->save();
		return redirect()->to($topic->link())->with('success', '创建 successfully.');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function update(TopicRequest $request, Topic $topic,Category $category)
	{
		$this->authorize('update', $topic);
        if($request->categoryType == 'add'){
            $category->description = $request->description;
            $category->name = $request->category;
            $category->user_id = \Auth::id();
            $category->save();


            $request->category_id = $category->id;
        }
		$topic->update($request->except('description','category'));

		return redirect()->to($topic->link())->with('success', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', 'Deleted successfully.');
	}

    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];
        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $path = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($path) {
                $data['file_path'] = $path;
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;
    }
}