<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Service\DAO;

use App\Model\AdminUserBehavior;

/**
 * 后台用户行为DAO
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Service\DAO
 */
class AdminUserBehaviorDAO
{
    /**
     * 创建
     *
     * @param array $data
     */
    public function create(array $data)
    {
        AdminUserBehavior::query()->create($data);
    }
}