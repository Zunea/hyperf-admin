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
use App\Request\Auth\LoginRequest;
use App\Service\UserService;

use Hyperf\HttpServer\Annotation\AutoController;

/**
 * 登陆控制器
 *
 * @AutoController()
 * @author Zunea(aile8880@qq.com)
 * @package App\Controller
 */
class AuthController extends AbstractController
{
    /**
     * 后台登陆接口
     *
     * @param LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
        $username = trim($request->input('username'));
        $password = trim($request->input('password'));

        $user = $this->container->get(UserService::class)->loginHandle($username, $password);

        // 生成Token
        $token = JwtInstance::instance()->encode($user);

        $this->success([
            'token' => $token
        ]);
    }
}