<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\AdminGroup;
use App\Model\AdminGroupMenu;
use App\Model\AdminGroupResource;
use App\Model\AdminMenu;
use App\Model\AdminResource;
use App\Model\AdminUserGroup;

use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Annotation\CacheEvict;
use Hyperf\Cache\Annotation\CachePut;
use Hyperf\Database\Model\Collection;
use Hyperf\DbConnection\Db;

/**
 * 后台用户组DAO
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Service\DAO
 */
class AdminGroupDAO extends Base
{
    /**
     * 获取用户组列表
     *
     * @return Collection
     */
    public function getGroupList()
    {
        return AdminGroup::query()->with('menus', 'resources')->get();
    }

    /**
     * 通过ID获取用户组
     *
     * @Cacheable(prefix="AdminGroup", ttl=3600)
     * @param int $id
     * @return mixed
     */
    public function first(int $id): ?AdminGroup
    {
        return AdminGroup::query()->find($id);
    }

    /**
     * 创建用户组
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): ?AdminGroup
    {
        // 检查名称
        if ($this->checkValueUnique('name', trim($data['name']))) {
            $this->formError([
                'name' => 'logic.GROUP_NAME_USED'
            ]);
        }

        return AdminGroup::query()->create([
            'name'   => $data['name'],
            'remark' => $data['remark'],
            'status' => 1
        ]);
    }

    /**
     * 修改数据
     *
     * @CachePut(prefix="AdminGroup", ttl=3600, value="#{id}")
     * @param int $id
     * @param array $data
     * @return AdminGroup
     */
    public function update(int $id, array $data): ?AdminGroup
    {
        /** @var AdminGroup $group */
        if (!$group = $this->first($id)) {
            $this->error('logic.GROUP_NOT_FOUND');
        }
        if (isset($data['name']) && $data['name'] !== $group->name) {
            if ($this->checkValueUnique('name', $data['name'])) {
                $this->formError([
                    'name' => 'logic.GROUP_NAME_USED'
                ]);
            }
            $group->name = $data['name'];
        }
        $group->remark = $data['remark'];
        $group->status = $data['status'];
        $group->save();

        // 清理该组的权限缓存
        $this->flushCache('AdminGroupResourceListUpdate', [$group->id]);
        $this->flushCache('AdminGroupMenuListUpdate', [$group->id]);

        return $group;
    }

    /**
     * 删除数据
     *
     * @CacheEvict(prefix="AdminGroup", value="#{id}")
     * @param int $id
     */
    public function delete(int $id)
    {
        Db::transaction(function () use ($id) {
            // 删除该组成员数据
            AdminUserGroup::query()->where('group_id', $id)->delete();
            // 删除该组菜单数据
            AdminGroupMenu::query()->where('group_id', $id)->delete();
            // 删除该组资源数据
            AdminGroupResource::query()->where('group_id', $id)->delete();
            // 删除该组
            AdminGroup::query()->where('id', $id)->delete();
            // 清理该组的权限缓存
            $this->flushCache('AdminGroupResourceListUpdate', [$id]);
            $this->flushCache('AdminGroupMenuListUpdate', [$id]);
        });
    }

    /**
     * 修改用户组菜单权限
     *
     * @CacheEvict(prefix="AdminGroupMenuList", value="#{group_id}")
     * @param int $group_id
     * @param array $menuIds
     */
    public function updateMenu(int $group_id, array $menuIds)
    {
        Db::transaction(function () use ($group_id, $menuIds) {
            AdminGroupMenu::query()->where('group_id', $group_id)->delete();
            if (empty($menuIds)) return;
            AdminGroupMenu::query()->insert(array_map(function ($menu_id) use ($group_id) {
                return ['group_id' => $group_id, 'menu_id' => $menu_id];
            }, $menuIds));
        });
    }

    /**
     * 修改用户组资源权限
     *
     * @CacheEvict(prefix="AdminGroupResourceList", value="#{group_id}")
     * @param int $group_id
     * @param array $resourceIds
     */
    public function updateResource(int $group_id, array $resourceIds)
    {
        Db::transaction(function () use ($group_id, $resourceIds) {
            AdminGroupResource::query()->where('group_id', $group_id)->delete();
            if (empty($resourceIds)) return;
            AdminGroupResource::query()->insert(array_map(function ($resource_id) use ($group_id) {
                return ['group_id' => $group_id, 'resource_id' => $resource_id];
            }, $resourceIds));
        });
    }

    /**
     * 获取该组菜单列表
     *
     * @Cacheable(prefix="AdminGroupMenuList", ttl=3600, listener="AdminGroupMenuListUpdate")
     * @param int $group_id
     * @return Collection
     */
    public function getGroupMenuList(int $group_id)
    {
        $group_menu = AdminGroupMenu::query()->where('group_id', $group_id)->whereHas('group')->get()->toArray();

        return AdminMenu::query()->whereIn('id', array_column($group_menu, 'menu_id'))->orderBy('sort')->orderBy('id')->get();
    }

    /**
     * 获取该组资源列表
     *
     * @Cacheable(prefix="AdminGroupResourceList", ttl=3600, listener="AdminGroupResourceListUpdate")
     * @param int $group_id
     * @return Collection
     */
    public function getGroupResourceList(int $group_id)
    {
        $group_resource = AdminGroupResource::query()->where('group_id', $group_id)->whereHas('group')->get()->toArray();

        return AdminResource::query()->whereIn('id', array_column($group_resource, 'resource_id'))->get();
    }

    /**
     * 检查唯一性
     *
     * @param string $column
     * @param string $value
     * @return bool
     */
    public function checkValueUnique(string $column, string $value)
    {
        return AdminGroup::query()->where($column, $value)->exists();
    }
}