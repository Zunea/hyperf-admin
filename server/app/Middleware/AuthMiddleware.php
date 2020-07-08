<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\Constants;
use App\Exception\ResponseException;
use App\Kernel\Utils\JwtInstance;
use App\Service\DAO\AdminUserBehaviorDAO;
use App\Service\UserService;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $dispatched = $request->getAttribute(\Hyperf\HttpServer\Router\Dispatched::class);
        if ($dispatched->handler === null) {
            throw new ResponseException('logic.RESOURCE_NOT_FOUND', 400);
        }
        $route  = $dispatched->handler->route;
        $method = $request->getMethod();
        $uri    = $request->getUri()->getPath();

        // 跳过白名单
        if (in_array($route, Constants::AUTH_WHITE_LIST)) {
            return $handler->handle($request);
        }

        // 获取Token
        $token = $request->getHeaderLine(Constants::AUTHORIZATION);
        if (empty($token)) {
            throw new ResponseException('logic.NEED_LOGIN', 401);
        }

        $user = JwtInstance::instance()->decode($token)->getUser();
        // 判断用户状态
        if (!$user || ($user->id > 0 && $user->status !== 1)) {
            throw new ResponseException('logic.USER_STATUS_UNUSUAL', 401);
        }

        // 跳过权限白名单
        if (!in_array($route, Constants::PERMISSION_WHITE_LIST)) {
            // 判断用户是否拥有该权限
            if (!$this->container->get(UserService::class)->checkPermission($route, $method, $user->id)) {
                throw new ResponseException('logic.PERMISSION_DENIED', 400);
            }
        }

        // 记录行为
        if (in_array($method, Constants::BEHAVIOR_METHOD)) {
            $this->container->get(AdminUserBehaviorDAO::class)->create([
                'user_id'        => $user->id,
                'action_ip'      => ip2long($request->getServerParams()['remote_addr']),
                'action_time'    => time(),
                'type'           => 2,
                'request_method' => $method,
                'request_url'    => $uri
            ]);
        }

        return $handler->handle($request);
    }
}