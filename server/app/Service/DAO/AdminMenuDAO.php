<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\AdminGroupMenu;
use App\Model\AdminMenu;
use App\Model\AdminUserMenu;

use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Annotation\CacheEvict;
use Hyperf\Cache\Annotation\CachePut;
use Hyperf\DbConnection\Db;

/**
 * 后台菜单DAO
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Service\DAO
 */
class AdminMenuDAO extends Base
{
    /**
     * 通过ID获取菜单
     *
     * @Cacheable(prefix="AdminMenu", ttl=3600, listener="AdminMenuUpdate")
     * @param int $id
     * @return mixed
     */
    public function first(int $id): ?AdminMenu
    {
        return AdminMenu::query()->find($id);
    }

    /**
     * 获取菜单列表
     *
     * @param array $maps
     * @return mixed
     */
    public function getMenuList(array $maps = [])
    {
        $model = AdminMenu::query();

        if (isset($maps['status'])) {
            $model = $model->where('status', $maps['status']);
        }

        $model = $model->orderBy('sort')->orderBy('id');

        return isset($maps['perPage']) ? $model->paginate((int)$maps['perPage']) : $model->get();
    }

    /**
     * 创建数据
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): ?AdminMenu
    {
        // 检查组件是否被使用
        if ($this->checkValueUnique('component', trim($data['component']))) {
            $this->formError([
                'component' => 'logic.COMPONENT_USED'
            ]);
        }

        return AdminMenu::query()->create([
            'parent_id' => $data['parent_id'],
            'name'      => $data['name'],
            'type'      => $data['type'],
            'icon'      => $data['icon'],
            'path'      => $data['path'],
            'component' => $data['component'],
            'sort'      => $data['sort']
        ]);
    }

    /**
     * 修改数据
     *
     * @CachePut(prefix="AdminMenu", value="#{id}")
     * @param int $id
     * @param array $data
     * @return AdminMenu
     */
    public function update(int $id, array $data): ?AdminMenu
    {
        /** @var AdminMenu $menu */
        if (!$menu = $this->first($id)) {
            $this->error('logic.MENU_NOT_FOUND');
        }
        // 上级菜单不能是自己
        if ($menu->id === $data['parent_id']) {
            $this->formError([
                'parent_id' => 'logic.PARENT_CANNOT_BE_HIMSELF'
            ]);
        }
        // 检查组件是否被使用
        if (isset($data['component']) && $data['component'] !== $menu->component) {
            if ($this->checkValueUnique('component', trim($data['component']))) {
                $this->formError([
                    'component' => 'logic.COMPONENT_USED'
                ]);
            }
            $menu->component = trim($data['component']);
        }
        $menu->name      = $data['name'];
        $menu->icon      = $data['icon'];
        $menu->path      = $data['path'];
        $menu->parent_id = $data['parent_id'];
        $menu->left_show = $data['left_show'];
        $menu->top_show  = $data['top_show'];
        $menu->target    = $data['target'];
        $menu->status    = $data['status'];
        $menu->sort      = $data['sort'];
        $menu->save();

        return $menu;
    }

    /**
     * 删除数据
     *
     * @CacheEvict(prefix="AdminMenu", value="#{id}")
     * @param int $id
     */
    public function delete(int $id)
    {
        Db::transaction(function () use ($id) {
            // 删除菜单
            AdminMenu::query()->where('id', $id)->where('is_system', 0)->delete();
            // 删除用户组和菜单的关联
            AdminGroupMenu::query()->where('menu_id', $id)->delete();
            // 删除用户和菜单的关联
            // AdminUserMenu::query()->where('menu_id', $id)->delete();
        });
    }

    /**
     * 检查唯一性
     *
     * @param string $column
     * @param string $value
     * @return bool
     */
    public function checkValueUnique(string $column, string $value): bool
    {
        if ($value === '') return false;

        return AdminMenu::query()->where($column, $value)->exists();
    }
}