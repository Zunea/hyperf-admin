<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Service;

use App\Common\Base;
use App\Event\UserLoginEvent;
use App\Model\AdminUser;
use App\Service\DAO\AdminGroupDAO;
use App\Service\DAO\AdminMenuDAO;
use App\Service\DAO\AdminResourceDAO;
use App\Service\DAO\AdminUserDAO;

use Hyperf\Di\Annotation\Inject;

/**
 * 后台用户服务
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Service
 */
class UserService extends Base
{
    /**
     * @Inject()
     * @var AdminUserDAO
     */
    public $userDAO;

    /**
     * @Inject()
     * @var AdminGroupDAO
     */
    private $groupDAO;

    /**
     * @Inject()
     * @var AdminResourceDAO
     */
    private $resourceDAO;

    /**
     * 登陆处理
     *
     * @param string $username 用户名
     * @param string $password 密码
     * @return AdminUser|null
     */
    public function loginHandle(string $username, string $password): ?AdminUser
    {
        // 普通账号
        $user = $this->container->get(AdminUserDAO::class)->firstByUsername($username);
        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

        // 判断密码是否正确
        if (!password_verify($password, $user->password)) {
            $this->error('logic.PASSWORD_ERROR');
        }

        // 账号状态
        if ($user->status !== 1) {
            $this->error('logic.USER_STATUS_UNUSUAL');
        }

        // 触发登录事件
        $this->eventDispatcher->dispatch(new UserLoginEvent($user));

        return $user;
    }

    /**
     * 检查用户权限
     *
     * @param string $path
     * @param string $method
     * @param int $user_id
     * @return bool
     */
    public function checkPermission(string $path, string $method, int $user_id): bool
    {
        // 获取资源是否存在
        if (!$resource = $this->resourceDAO->getResourceByPath($path, $method)) {
            $this->error('logic.RESOURCE_NOT_FOUND');
        }

        // 超级管理员
        if ($user_id === 1) {
            return true;
        }

        // 获取该用户加入的组
        foreach ($this->userDAO->getUserGroupIdsByUserId($user_id) as $group_id) {
            // 判断该组是否拥有该权限
            $resourceIds = array_column($this->groupDAO->getGroupResourceList($group_id)->toArray(), 'id');
            if (array_search($resource->id, $resourceIds) !== false) {
                return true;
            }
        }

        // 检查用户是否额外拥有该权限
        // return $this->userDAO->checkResourceByUser($user_id, $resource->id);
        return false;
    }

    /**
     * 获取用户菜单
     *
     * @param int $user_id
     * @return array
     */
    public function getMenus(int $user_id): array
    {
        // 超级管理员
        if ($user_id === 1) {
            $result = $this->container->get(AdminMenuDAO::class)->getMenuList(['status' => 1])->toArray();
        } else {
            // 获取用户组的菜单
            $result = [];
            foreach ($this->userDAO->getUserGroupIdsByUserId($user_id) as $group_id) {
                // 过滤掉已拥有的菜单
                $filter = array_filter($this->groupDAO->getGroupMenuList($group_id)->toArray(), function ($item) use ($result) {
                    return !in_array($item['id'], array_column($result, 'id'));
                });
                if (count($filter) === 0) continue;

                array_push($result, ...array_values($filter));
            }
        }

        foreach ($result as $key => $menu) {
            // 处理按钮
            if ($menu['type'] === 2) {
                unset($result[$key]);
                foreach ($result as $_key => $_menu) {
                    if ($menu['parent_id'] === $_menu['id']) {
                        $result[$_key]['actions'][] = $menu['path'];
                    }
                }
            }
        }

        return array_values($result);
    }

    /**
     * 获取选项
     *
     * @return array
     */
    public function getOptions(): array
    {
        $result = [];

        // 后台用户组列表
        $result['AdminGroupList'] = array_map(function ($item) {
            return ['label' => $item['name'], 'value' => $item['id']];
        }, $this->container->get(AdminGroupDAO::class)->getGroupList()->toArray());

        // 配置类型列表
        $configList                    = $this->container->get(ConfigService::class)::CONFIG_COMPONENTS;
        $result['AdminConfigTypeList'] = array_map(function ($item, $key) {
            return ['label' => __('view.' . $item), 'value' => $key];
        }, $configList, array_keys($configList));

        // 配置分组列表
        $configGroup                = getConfig('configGroup', []);
        $result['AdminConfigGroup'] = array_map(function ($item, $key) {
            return ['label' => $item, 'value' => $key];
        }, $configGroup, array_keys($configGroup));

        return $result;
    }
}