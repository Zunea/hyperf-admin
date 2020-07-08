<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Event;

use App\Model\AdminUser;

/**
 * 后台用户登录事件
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Event
 */
class UserLoginEvent
{
    /**
     * @var AdminUser
     */
    public $user;

    /**
     * UserLoginEvent constructor.
     *
     * @param AdminUser $user
     */
    public function __construct(AdminUser $user)
    {
        $this->user = $user;
    }
}