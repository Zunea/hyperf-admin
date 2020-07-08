<?php

declare(strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Middleware;

use Hyperf\Contract\TranslatorInterface;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
/**
 * 前端国际化中间件
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Model
 */
class TranslationMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var TranslatorInterface
     */
    private $translator;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $locale = $request->getHeaderLine('Locale');

        // 根据前端接口动态设置语言
        $this->translator->setLocale($locale ?: 'zh_CN');

        return $handler->handle($request);
    }
}
