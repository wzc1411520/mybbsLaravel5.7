<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TopicsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Topic);

        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->column(1/3,function ($filter){
                $filter->like('title','标题');
            });
            // 在这里添加字段过滤器
            $filter->column(1/3,function ($filter){
//                $filter->equal('category_id','分类')->select('api/category');
                $filter->equal('category_id','分类')->select(Category::pluck('name as text','id'));
//                $filter->like('category','作者');
            });
            // 在这里添加字段过滤器
            $filter->column(1/3,function ($filter){
                $filter->like('body','内容');
            });
        });


        $grid->id('Id');
        $grid->title('Title');
        $grid->excerpt('Excerpt');
        $grid->user()->display(function ($user){
            return $user['name'];
        });
        $grid->category('分类')->display(function($category){
            return $category['name'];
        });
        $grid->reply_count('Reply count')->sortable();
        $grid->view_count('View count')->sortable();
        $grid->slug('Slug');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Topic::findOrFail($id));

//        $show->id('Id');
        $show->title('Title');
        $show->body('Body');
//        $show->user('user',function ($user){
//            $user->setResource('/admin/users');
//            $user->name();
//        });
        $show->user('user')->as(function ($user){
            return $user->name;
        });
        $show->category('category')->as(function ($category){
            return $category->name;
        });
//        $show->category_id('category.name');
        $show->reply_count('Reply count');
        $show->view_count('View count');
        $show->excerpt('Excerpt');
        $show->slug('Slug');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Topic);

        $form->text('title', 'Title');
        $form->select('category_id','Category')->options(Category::getSelectOptions());
//        $form->textarea('body', 'Body');
        $form->simplemde('body');

        return $form;
    }
}
