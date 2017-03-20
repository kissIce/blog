<?php

namespace App\Http\Controllers\Admin\Controllers;

use App\Http\Controllers\Admin\Models\ArticleCate;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Encore\Admin\Controllers\ModelForm;

use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;

class ArticleCateController extends Controller
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
            $content->header('栏目管理');
            $content->description('列表');

            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());
                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('auth/article_cate'));
                    $form->select('parent_id', '上级栏目')->options(ArticleCate::selectOptions('顶级'));
                    $form->text('title', '栏目名称')->rules('required');
                    $form->image('icon', '栏目图片');
                    $form->switch('status','是否启用')->states([
                        'on'  => ['value' => 1, 'text' => '显示'],
                        'off' => ['value' => 0, 'text' => '不显示'],
                    ])->default(1);
                    $form->text('uri', '跳转路径')->placeholder('11');
                    $column->append((new Box(trans('admin::lang.new'), $form))->style('success'));
                });
            });
        });
    }

    /**
     * Redirect to edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->action(
            '\Encore\Admin\Controllers\ArticleCateController@edit', ['id' => $id]
        );
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        return ArticleCate::tree(function (Tree $tree) {
            $tree->disableCreate();

            $tree->branch(function ($branch) {
                $payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['title']}</strong>";
                if ($branch['uri'] != '0') {
                    $uri = $branch['uri'];
                    $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
                }
                return $payload;
            });
        });
    }

    /**
     * Edit interface.
     *
     * @param string $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('admin::lang.menu'));
            $content->description(trans('admin::lang.edit'));

            $content->row($this->form()->edit($id));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return ArticleCate::form(function (Form $form) {
            $form->display('id', 'ID');
            $form->select('parent_id', trans('admin::lang.parent_id'))->options(ArticleCate::selectOptions());
            $form->text('title', trans('admin::lang.title'))->rules('required');
            $form->icon('icon', trans('admin::lang.icon'));
            $form->switch('status','是否显示')->states([
                'on'  => ['value' => 1, 'text' => '显示'],
                'off' => ['value' => 0, 'text' => '不显示'],
            ]);
            $form->text('uri', trans('admin::lang.uri'));
            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }





}