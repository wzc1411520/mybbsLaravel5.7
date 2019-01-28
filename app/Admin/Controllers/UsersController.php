<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UsersController extends Controller
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
        $grid = new Grid(new User);
        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->column(1/3,function ($filter){
                $filter->like('name','姓名');
            });
            // 在这里添加字段过滤器
            $filter->column(1/3,function ($filter){
                $filter->like('email','邮箱账号');
            });
        });
        $grid->id('Id');
        $grid->avatar('头像')->display(function ($avatar){
            return  '<img src="'.$avatar.'"width="100">';
        });
        $grid->name('Name');
        $grid->email('Email');
        $grid->introduction('Introduction');
        $grid->notification_count('Notification count');

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
        $show = new Show(User::findOrFail($id));

        $show->avatar('Avatar')->image();
        $show->name('Name');
        $show->email('Email');
        $show->introduction('Introduction');
        $show->notification_count('Notification count');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form->text('name', 'Name');
        $form->image('avatar', 'Avatar');
        $form->email('email', 'Email');
        $form->text('introduction', 'Introduction');

        return $form;
    }
}
