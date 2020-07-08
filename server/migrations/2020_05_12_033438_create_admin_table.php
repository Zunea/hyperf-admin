<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 配置表
        Schema::create('admin_config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50)->comment('配置名');
            $table->string('title', 50)->comment('标题');
            $table->string('group', 50)->comment('配置分组');
            $table->string('type', 50)->comment('配置类型');
            $table->text('value')->default(null)->nullable()->comment('配置值');
            $table->text('options')->comment('配置项');
            $table->json('attr')->default(null)->nullable()->comment('组件配置');
            $table->string('tips', 255)->comment('提示内容');
            $table->string('format', 50)->default(null)->nullable()->comment('格式');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('is_enable')->default(1)->comment('状态: 0=禁用;1=启用');
            $table->unsignedTinyInteger('is_system')->default(0)->comment('是否系统配置(不允许删除)');
            $table->unsignedInteger('create_time')->comment('创建时间');
            $table->unsignedInteger('update_time')->comment('修改时间');
        });

        // 后台用户表
        Schema::create('admin_user', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('username', 30)->unique()->comment('登陆账号');
            $table->string('password', 255)->comment('登陆密码');
            $table->string('nickname', 50)->comment('昵称');
            $table->string('phone', 50)->unique()->comment('手机号码');
            $table->string('email', 50)->comment('电子邮箱');
            $table->string('avatar', 255)->default(null)->nullable()->comment('头像');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态: 1=正常;2=禁用');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('修改时间');
            $table->unsignedInteger('deleted_at')->nullable()->default(null)->comment('删除时间');
        });

        // 用户组成员表
        Schema::create('admin_user_group', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('group_id')->comment('用户组ID');

            $table->unique(['user_id', 'group_id']);
        });

        // 后台用户组表
        Schema::create('admin_group', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name', 30)->comment('用户组名称');
            $table->string('remark', 255)->comment('备注');
            $table->unsignedTinyInteger('status')->comment('状态: 1=正常;2=禁用');
        });

        // 后台菜单表
        Schema::create('admin_menu', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger('parent_id')->comment('上级菜单');
            $table->string('name', 30)->comment('菜单名称');
            $table->string('icon', 30)->comment('菜单图标');
            $table->unsignedTinyInteger('type')->comment('菜单类型: 1=菜单;2=按钮');
            $table->string('path', 30)->comment('前端路由');
            $table->string('component', 50)->comment('前端组件名');
            $table->unsignedTinyInteger('left_show')->default(1)->comment('是否左侧显示');
            $table->unsignedTinyInteger('top_show')->default(0)->comment('是否首页显示');
            $table->unsignedTinyInteger('target')->default(1)->comment('页面打开方式: 1=_self;2=_blank');
            $table->unsignedTinyInteger('status')->default(1)->index()->comment('状态: 1=正常;2=禁用');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('is_system')->default(0)->comment('是否系统菜单(不允许删除和修改)');
        });

        // 后台资源表
        Schema::create('admin_resource', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name', 50)->comment('资源名称');
            $table->string('path')->comment('地址');
            $table->string('method')->comment('请求方法: GET;POST;PUT;DELETE');
            $table->unsignedTinyInteger('is_system')->default(0)->comment('是否系统资源(不允许删除和修改)');

            $table->unique(['path', 'method']);
        });

        // 用户组与菜单关联表
        Schema::create('admin_group_menu', function (Blueprint $table) {
            $table->unsignedInteger('group_id')->comment('用户组ID');
            $table->unsignedInteger('menu_id')->comment('菜单ID');

            $table->unique(['group_id', 'menu_id']);
        });

        // 用户组与资源关联表
        Schema::create('admin_group_resource', function (Blueprint $table) {
            $table->unsignedInteger('group_id')->comment('用户组ID');
            $table->unsignedInteger('resource_id')->comment('资源ID');

            $table->unique(['group_id', 'resource_id']);
        });

        // 后台用户菜单表
        Schema::create('admin_user_menu', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('menu_id')->comment('菜单ID');

            $table->unique(['user_id', 'menu_id']);
        });

        // 后台用户资源表
        Schema::create('admin_user_resource', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('resource_id')->comment('资源ID');

            $table->unique(['user_id', 'resource_id']);
        });

        // 后台用户行为表
        Schema::create('admin_user_behavior', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->comment('后台用户ID');
            $table->unsignedBigInteger('action_ip')->comment('访问IP');
            $table->unsignedInteger('action_time')->comment('记录时间');
            $table->unsignedTinyInteger('type')->comment('行为类型: 1=登陆;2=其它');
            $table->string('request_method', 10)->comment('请求方式: GET;POST;PUT;DELETE');
            $table->string('request_url')->comment('请求地址');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_config');
        Schema::dropIfExists('admin_user');
        Schema::dropIfExists('admin_user_group');
        Schema::dropIfExists('admin_menu');
        Schema::dropIfExists('admin_resource');
        Schema::dropIfExists('admin_group');
        Schema::dropIfExists('admin_group_menu');
        Schema::dropIfExists('admin_group_resource');
        Schema::dropIfExists('admin_user_menu');
        Schema::dropIfExists('admin_user_resource');
        Schema::dropIfExists('admin_user_behavior');
    }
}
