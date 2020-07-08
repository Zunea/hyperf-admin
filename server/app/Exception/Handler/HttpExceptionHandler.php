<?php

declare(strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Exception\Handler;

use App\Exception\HttpException;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;

use Psr\Http\Message\ResponseInterface;

use Throwable;

/**
 * Http异常接管
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Model
 */
class HttpExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $data = [
            'code'    => $throwable->getCode(),
            'message' => __($throwable->getMessage())
        ];

        // 阻止事件冒泡
        $this->stopPropagation();

        $response = $response->withStatus($throwable->getCode())
            ->withBody(new SwooleStream(json_encode($data, JSON_UNESCAPED_UNICODE)))
            ->withHeader('Content-Type', 'application/json;charset=utf-8');

        // 交换token
        if (Context::has('ExchangeToken')) {
            $response = $response->withHeader('Exchange-Token', Context::get('ExchangeToken'));
        }

        return $response;
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof HttpException;
    }
}
