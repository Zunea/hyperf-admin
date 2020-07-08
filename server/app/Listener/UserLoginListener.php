<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Listener;

use App\Event\UserLoginEvent;
use App\Model\AdminUser;
use App\Service\DAO\AdminUserBehaviorDAO;

use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * 用户登录监听器
 *
 * @Listener()
 * @author Zunea(aile8880@qq.com)
 * @package App\Listener
 */
class UserLoginListener extends AbstractListener implements ListenerInterface
{
    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        return [
            UserLoginEvent::class
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     * @param object $event
     */
    public function process(object $event)
    {
        if (!($event->user instanceof AdminUser)) {
            return;
        }

        $request = $this->container->get(RequestInterface::class);
        // 记录登陆行为
        $this->container->get(AdminUserBehaviorDAO::class)->create([
            'user_id'        => $event->user->id,
            'action_ip'      => ip2long($request->getServerParams()['remote_addr']),
            'action_time'    => time(),
            'type'           => 1,
            'request_method' => $request->getMethod(),
            'request_url'    => $request->getUri()->getPath()
        ]);
    }
}