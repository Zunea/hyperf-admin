<?php

use Hyperf\Database\Migrations\Migration;

/**
 * 加载后台模块的初始数据
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Service
 */
class InitAdminDefaultData extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->initAdminUser();
        $this->initResource();
        $this->initDashboardMenu();
        $this->initManageMenu();
        $this->initConfig();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 撤销
    }

    /**
     * 创建超管账号
     */
    private function initAdminUser()
    {
        \App\Model\AdminUser::query()->updateOrCreate([
            'username' => 'admin'
        ], [
            'password' => '123456',
            'nickname' => 'admin',
            'phone'    => '13800138000',
            'email'    => 'admin@admin.com'
        ]);
    }

    /**
     * 加载资源列表
     */
    private function initResource()
    {
        // 初始化资源数据
        $resources = [
            ['name' => '控制台', 'path' => '/admin/dashboard/dashboard', 'method' => 'GET', 'is_system' => 0],
            ['name' => '后台账号列表', 'path' => '/admin/user', 'method' => 'GET', 'is_system' => 1],
            ['name' => '添加后台账号', 'path' => '/admin/user', 'method' => 'POST', 'is_system' => 1],
            ['name' => '修改后台账号', 'path' => '/admin/user/{id}', 'method' => 'PUT', 'is_system' => 1],
            ['name' => '删除后台账号', 'path' => '/admin/user/{id}', 'method' => 'DELETE', 'is_system' => 1],
            ['name' => '用户组列表', 'path' => '/admin/group', 'method' => 'GET', 'is_system' => 1],
            ['name' => '添加用户组', 'path' => '/admin/group', 'method' => 'POST', 'is_system' => 1],
            ['name' => '修改用户组', 'path' => '/admin/group/{id}', 'method' => 'PUT', 'is_system' => 1],
            ['name' => '删除用户组', 'path' => '/admin/group/{id}', 'method' => 'DELETE', 'is_system' => 1],
            ['name' => '修改用户组菜单权限', 'path' => '/admin/group/menu/{id}', 'method' => 'PUT', 'is_system' => 1],
            ['name' => '修改用户组资源权限', 'path' => '/admin/group/resource/{id}', 'method' => 'PUT', 'is_system' => 1],
            ['name' => '菜单列表', 'path' => '/admin/menu', 'method' => 'GET', 'is_system' => 1],
            ['name' => '添加菜单', 'path' => '/admin/menu', 'method' => 'POST', 'is_system' => 1],
            ['name' => '修改菜单', 'path' => '/admin/menu/{id}', 'method' => 'PUT', 'is_system' => 1],
            ['name' => '删除菜单', 'path' => '/admin/menu/{id}', 'method' => 'DELETE', 'is_system' => 1],
            ['name' => '资源列表', 'path' => '/admin/resource', 'method' => 'GET', 'is_system' => 1],
            ['name' => '添加资源', 'path' => '/admin/resource', 'method' => 'POST', 'is_system' => 1],
            ['name' => '修改资源', 'path' => '/admin/resource/{id}', 'method' => 'PUT', 'is_system' => 1],
            ['name' => '删除资源', 'path' => '/admin/resource/{id}', 'method' => 'DELETE', 'is_system' => 1],
            ['name' => '获取配置列表', 'path' => '/admin/config', 'method' => 'GET', 'is_system' => 1],
            ['name' => '添加配置', 'path' => '/admin/config', 'method' => 'POST', 'is_system' => 1],
            ['name' => '修改配置', 'path' => '/admin/config/{id}', 'method' => 'PUT', 'is_system' => 1],
            ['name' => '删除配置', 'path' => '/admin/config/{ids}', 'method' => 'DELETE', 'is_system' => 1],
            ['name' => '启用配置', 'path' => '/admin/config/enable/{ids}', 'method' => 'PUT', 'is_system' => 1],
            ['name' => '禁用配置', 'path' => '/admin/config/disable/{ids}', 'method' => 'PUT', 'is_system' => 1],
            ['name' => '文件上传', 'path' => '/admin/upload', 'method' => 'POST', 'is_system' => 1],
            ['name' => '获取设置列表', 'path' => '/admin/setting', 'method' => 'GET', 'is_system' => 1],
            ['name' => '修改设置', 'path' => '/admin/setting', 'method' => 'PUT', 'is_system' => 1],
        ];
        foreach ($resources as $resource) {
            \App\Model\AdminResource::query()->updateOrCreate([
                'path'   => $resource['path'],
                'method' => $resource['method']
            ], [
                'name'      => $resource['name'],
                'is_system' => 1
            ]);
        }
    }

    /**
     * 加载控制台菜单
     */
    private function initDashboardMenu()
    {
        $dashboard = \App\Model\AdminMenu::query()->firstOrCreate([
            'name' => 'DASHBOARD',
            'path' => 'dashboard'
        ], [
            'parent_id' => 0,
            'type'      => 1,
            'icon'      => 'el-icon-custom-dashboard',
            'component' => ''
        ]);
        // 控制台的二级菜单
        if ($dashboard) {
            // 工作台1
            \App\Model\AdminMenu::query()->firstOrCreate([
                'name' => 'workplace',
                'path' => 'workplace'
            ], [
                'parent_id' => $dashboard->id,
                'type'      => 1,
                'icon'      => '',
                'component' => 'dashboard/workplace'
            ]);
            // 工作台2
            \App\Model\AdminMenu::query()->firstOrCreate([
                'name' => 'workplace2',
                'path' => 'workplace2'
            ], [
                'parent_id' => $dashboard->id,
                'type'      => 1,
                'icon'      => '',
                'component' => 'dashboard/workplace2'
            ]);
        }
    }

    /**
     * 加载系统管理菜单
     */
    private function initManageMenu()
    {
        $system = \App\Model\AdminMenu::query()->firstOrCreate([
            'name' => 'SYSTEM_MANAGE',
            'path' => 'system'
        ], [
            'parent_id' => 0,
            'type'      => 1,
            'icon'      => 'el-icon-custom-setting1',
            'component' => '',
            'is_system' => 1
        ]);
        // 系统管理的二级菜单
        if ($system) {
            // 设置
            $setting = \App\Model\AdminMenu::query()->firstOrCreate([
                'parent_id' => $system->id,
                'name'      => 'SETTING',
                'path'      => 'setting'
            ], [
                'type'      => 1,
                'icon'      => 'el-icon-setting',
                'component' => 'system/setting',
                'is_system' => 1
            ]);
            // 设置按钮
            if ($setting) {
                $this->writeButton($setting->id, ['save']);
            }

            // 配置
            $config = \App\Model\AdminMenu::query()->firstOrCreate([
                'parent_id' => $system->id,
                'name'      => 'CONFIG',
                'path'      => 'config'
            ], [
                'type'      => 1,
                'icon'      => 'el-icon-custom-setting',
                'component' => 'system/config',
                'is_system' => 1
            ]);
            // 配置按钮
            if ($config) {
                $this->writeButton($config->id, ['add', 'update', 'delete', 'enable', 'disable']);
            }

            // 用户
            $user = \App\Model\AdminMenu::query()->firstOrCreate([
                'parent_id' => $system->id,
                'name'      => 'USER',
                'path'      => 'user'
            ], [
                'type'      => 1,
                'icon'      => 'el-icon-custom-admin-user',
                'component' => 'system/user',
                'is_system' => 1
            ]);
            // 用户按钮
            if ($user) {
                $this->writeButton($user->id, ['add', 'update', 'delete']);
            }

            // 用户组
            $group = \App\Model\AdminMenu::query()->firstOrCreate([
                'parent_id' => $system->id,
                'name'      => 'GROUP',
                'path'      => 'group'
            ], [
                'type'      => 1,
                'icon'      => 'el-icon-custom-role',
                'component' => 'system/group',
                'is_system' => 1
            ]);
            // 用户组按钮
            if ($group) {
                $this->writeButton($group->id, ['add', 'update', 'delete', 'menu', 'resource']);
            }

            // 菜单
            $menu = \App\Model\AdminMenu::query()->firstOrCreate([
                'parent_id' => $system->id,
                'name'      => 'MENU',
                'path'      => 'menu'
            ], [
                'type'      => 1,
                'icon'      => 'el-icon-custom-menu',
                'component' => 'system/menu',
                'is_system' => 1
            ]);
            // 菜单按钮
            if ($menu) {
                $this->writeButton($menu->id, ['add', 'update', 'delete']);
            }

            // 资源
            $resource = \App\Model\AdminMenu::query()->firstOrCreate([
                'parent_id' => $system->id,
                'name'      => 'RESOURCE',
                'path'      => 'resource'
            ], [
                'type'      => 1,
                'icon'      => 'el-icon-custom-resources',
                'component' => 'system/resource',
                'is_system' => 1
            ]);
            // 资源按钮
            if ($resource) {
                $this->writeButton($resource->id, ['add', 'update', 'delete']);
            }
        }
    }

    /**
     * 批量写入按钮
     *
     * @param int $parent_id
     * @param $paths
     */
    private function writeButton(int $parent_id, $paths)
    {
        foreach ($paths as $path) {
            \App\Model\AdminMenu::query()->firstOrCreate([
                'parent_id' => $parent_id,
                'path'      => $path
            ], [
                'name'      => '',
                'type'      => 2,
                'icon'      => '',
                'component' => '',
                'left_show' => false,
                'top_show'  => false,
                'target'    => 0,
                'status'    => 1,
                'is_system' => 1
            ]);
        }
    }

    /**
     * 加载配置表数据
     */
    private function initConfig()
    {
        \App\Model\AdminConfig::query()->updateOrCreate([
            'name' => 'configGroup'
        ], [
            'title'   => '配置分组',
            'group'   => 'base',
            'type'    => 'keyValue',
            'options' => '',
            'tips'    => '',
            'value'   => 'base:基础设置' . PHP_EOL . 'other:其它设置'
        ]);

        \App\Model\AdminConfig::query()->updateOrCreate([
            'name' => 'web_title'
        ], [
            'title'   => '网站标题',
            'group'   => 'base',
            'type'    => 'text',
            'options' => '',
            'tips'    => '',
            'value'   => 'test'
        ]);
    }
}
