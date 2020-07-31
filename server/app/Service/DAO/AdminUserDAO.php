<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\AdminUser;
use App\Model\AdminUserGroup;
use App\Model\AdminUserResource;

use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Annotation\CacheEvict;
use Hyperf\Cache\Annotation\CachePut;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;

/**
 * 后台用户DAO
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Service\DAO
 */
class AdminUserDAO extends Base
{
    /**
     * 通过ID获取用户
     *
     * @Cacheable(prefix="AdminUser", ttl=3600, listener="UpdateAdminUser")
     * @param int $id
     * @return mixed
     */
    public function first(int $id): ?AdminUser
    {
        return AdminUser::query()->find($id);
    }

    /**
     * 通过用户名获取用户
     *
     * @param string $username
     * @return mixed
     */
    public function firstByUsername(string $username): ?AdminUser
    {
        return AdminUser::query()->where('username', $username)->first();
    }

    /**
     * 获取用户列表
     *
     * @param array $params
     * @return \Hyperf\Contract\LengthAwarePaginatorInterface
     */
    public function getUserList(array $params)
    {
        $model = AdminUser::query();

        if (isset($params['username']) && $params['username'] !== '') {
            $model = $model->where('username', 'like', '%' . $params['username'] . '%');
        }

        if (isset($params['nickname']) && $params['nickname'] !== '') {
            $model = $model->where('nickname', 'like', '%' . $params['nickname'] . '%');
        }

        if (isset($params['phone']) && $params['phone'] !== '') {
            $model = $model->where('phone', 'like', '%' . $params['phone'] . '%');
        }

        if (isset($params['email']) && $params['email'] !== '') {
            $model = $model->where('email', 'like', '%' . $params['email'] . '%');
        }

        if (isset($params['status']) && $params['status'] !== '') {
            // 正常状态
            if ($params['status'] === 'normal') {
                $model = $model->where('status', 1);
            }
            // 禁用状态
            if ($params['status'] === 'disabled') {
                $model = $model->where('status', 2);
            }
        }

        if (isset($params['group_id']) && $params['group_id'] > 0) {
            $model = $model->whereHas('groups', function (Builder $builder) use ($params) {
                $builder->where('group_id', $params['group_id']);
            });
        }

        if (isset($params['reg_date']) && is_array($params['reg_date']) && ($params['reg_date'][0] ?? false) && ($params['reg_date'][1] ?? false)) {
            $between = [strtotime($params['reg_date'][0]), strtotime($params['reg_date'][1])];
            asort($between);
            $model = $model->whereBetween('created_at', $between);
        }

        return $model->with('groups')->orderBy('status')->orderBy('id')->paginate((int)($params['perPage'] ?? 10));
    }

    /**
     * 修改数据
     *
     * @CachePut(prefix="AdminUser", value="#{id}")
     * @param int $id
     * @param array $data
     * @return AdminUser
     */
    public function update(int $id, array $data): ?AdminUser
    {
        /** @var AdminUser $user */
        if (!$user = $this->first($id)) {
            $this->error('logic.USER_NOT_FOUND');
        }
        if (isset($data['username']) && $data['username'] !== $user->username) {
            if ($this->checkValueUnique('username', trim($data['username']))) {
                $this->formError([
                    'username' => 'logic.USERNAME_USED'
                ]);
            }
            $user->username = trim($data['username']);
        }
        if (isset($data['phone']) && $data['phone'] !== $user->phone) {
            if ($this->checkValueUnique('phone', trim($data['phone']))) {
                $this->formError([
                    'phone' => 'logic.PHONE_USED'
                ]);
            }
            $user->phone = trim($data['phone']);
        }
        $user->password = $data['password'];
        $user->nickname = $data['nickname'];
        $user->email    = $data['email'];
        $user->status   = $data['status'];
        $user->save();

        return $user;
    }

    /**
     * 创建数据
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): ?AdminUser
    {
        // 检查用户名
        if ($this->checkValueUnique('username', trim($data['username']))) {
            $this->formError([
                'username' => 'logic.USERNAME_USED'
            ]);
        }
        // 检查手机号
        if ($this->checkValueUnique('phone', trim($data['phone']))) {
            $this->formError([
                'phone' => 'logic.PHONE_USED'
            ]);
        }

        return AdminUser::query()->create([
            'username' => $data['username'],
            'password' => $data['password'],
            'nickname' => $data['nickname'],
            'phone'    => $data['phone'],
            'email'    => $data['email'],
            'avatar'   => '',
            'status'   => 1
        ]);
    }

    /**
     * 删除数据
     *
     * @CacheEvict(prefix="AdminUser", value="#{id}")
     * @param int $id
     */
    public function delete(int $id)
    {
        AdminUser::query()->where('id', $id)->delete();
    }

    /**
     * 通过用户ID获取该用户的用户组Ids
     *
     * @Cacheable(prefix="AdminUserGroupIds", ttl=3600)
     * @param int $user_id
     * @return array
     */
    public function getUserGroupIdsByUserId(int $user_id): array
    {
        return array_column(AdminUserGroup::query()->where('user_id', $user_id)->get()->toArray(), 'group_id');
    }

    /**
     * 检查用户是否拥有该资源
     *
     * @param int $user_id
     * @param int $resource_id
     * @return bool
     */
    public function checkResourceByUser(int $user_id, int $resource_id): bool
    {
        return AdminUserResource::query()->where('resource_id', $resource_id)->where('user_id', $user_id)->exists();
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
        return AdminUser::withTrashed()->where($column, $value)->exists();
    }

    /**
     * 设置用户的组
     *
     * @CacheEvict(prefix="AdminUserGroupIds", value="#{user_id}")
     * @param int $user_id
     * @param array $groupIds
     */
    public function setUserGroup(int $user_id, array $groupIds)
    {
        Db::transaction(function () use ($user_id, $groupIds) {
            AdminUserGroup::query()->where('user_id', $user_id)->delete();
            if (empty($groupIds)) return;
            AdminUserGroup::query()->insert(array_map(function ($group_id) use ($user_id) {
                return ['user_id' => $user_id, 'group_id' => $group_id];
            }, $groupIds));
        });
    }
}