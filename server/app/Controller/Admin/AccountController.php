<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Kernel\Utils\JwtInstance;
use App\Request\Account\ChangePasswordRequest;
use App\Service\UserService;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 获取账户信息
 *
 * @Controller()
 * @author Zunea(aile8880@qq.com)
 * @package App\Controller
 */
class AccountController extends AbstractController
{
    /**
     * @Inject()
     * @var UserService
     */
    private $userService;

    /**
     * 用户信息接口
     *
     * @GetMapping(path="info")
     */
    public function info()
    {
        $user = JwtInstance::instance()->build()->getUser();

        $this->success([
            'id'       => $user->id,
            'username' => $user->username,
            'nickname' => $user->nickname,
            'phone'    => $user->phone,
            'email'    => $user->email,
            'avatar'   => $user->avatar
        ]);
    }

    /**
     * 退出接口
     *
     * @PostMapping(path="logout")
     */
    public function logout()
    {
        $this->success();
    }

    /**
     * 获取菜单接口
     *
     * @GetMapping(path="menu")
     */
    public function menu()
    {
        $user = JwtInstance::instance()->build()->getUser();

        $result = $this->userService->getMenus($user->id);

        $this->success($result);
    }

    /**
     * 获取选项接口
     *
     * @GetMapping(path="option")
     */
    public function option()
    {
        $result = $this->userService->getOptions();

        $this->success($result);
    }

    /**
     * 修改密码
     *
     * @PutMapping(path="password")
     * @param ChangePasswordRequest $request
     */
    public function password(ChangePasswordRequest $request)
    {
        $user = JwtInstance::instance()->build()->getUser();
        if (!password_verify($request->post('old_password'), $user->password)) {
            $this->formError([
                'old_password' => 'logic.PASSWORD_ERROR'
            ]);
        }
        $user->password = $request->post('new_password');
        $user->save();

        // 清理缓存
        $this->flushCache('UpdateAdminUser', [$user->id]);

        $this->success();
    }
}