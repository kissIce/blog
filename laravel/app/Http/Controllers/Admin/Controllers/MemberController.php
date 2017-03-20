<?php

namespace App\Http\Controllers\Admin\Controllers;

use App\Http\Controllers\Admin\Models\Member;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Encore\Admin\Controllers\ModelForm;

class MemberController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('会员中心');
            $content->description('列表');
            $content->body($this->grid()->render());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('会员中心');
            $content->description('编辑');
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('会员中心');
            $content->description('新增');
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Member::grid(function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->account('账号');
            $grid->truename('姓名');
            $grid->avatar('头像')->image();
            $grid->reg_time(trans('admin::lang.created_at'))->value(function ($reg_time) {
                return date('Y-m-d H:i',$reg_time);
            });
            $grid->update_time(trans('admin::lang.updated_at'))->value(function ($update_time) {
                return date('Y-m-d H:i',$update_time);
            });
            $grid->mobile('手机');
            $grid->email('邮箱');
            $grid->balance('余额')->value(function ($balance) {
                return "¥$balance";
            })->badge('green')->sortable();

            $grid->frozen_money('冻结金额')->value(function ($frozen_money) {
                return "¥$frozen_money";
            })->badge('#fff')->sortable();
            $grid->status('状态')->value(function ($status) {
                return $status ?
                    "<i class='fa fa-check' style='color:green'></i>" :
                    "<i class='fa fa-close' style='color:red'></i>";
            });
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->getKey() == 1) $actions->disableDelete();
            });
            $grid->filter(function ($filter) {
                $filter->like('account', '账号');
                $filter->like('mobile', '手机');
                $filter->like('email', '邮箱');
            });
            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
            $grid->allowBatchDeletion();
            $grid->disableExport();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Member::form(function (Form $form) {
            $form->display('id', 'id');
            $form->text('account', trans('admin::lang.username'))->rules('required');
            $form->text('truename', trans('admin::lang.name'))->rules('required');
            $form->text('mobile', '手机')->rules('required');
            $form->text('email', '邮箱')->rules('required');
            $form->image('avatar', '头像');
            $form->password('password', trans('admin::lang.password'))->rules('required|confirmed');
            $form->password('password_confirmation', trans('admin::lang.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;
                });
            $form->switch('status', '状态');
            $form->ignore(['password_confirmation']);
            $form->saving(function (form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });
        });
    }
}