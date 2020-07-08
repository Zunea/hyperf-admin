<?php

declare(strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Exception\Handler;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 验证器异常接管
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Model
 */
class ValidationExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        /** @var ValidationException $throwable */
        $error        = $throwable->validator->errors()->first();
        $errors       = [];
        $placeholders = Context::get('ValidationPlaceholders', []);
        foreach ($throwable->validator->errors()->getMessages() as $column => $messages) {
            // 多条错误信息
            /*$props[$column] = array_map(function ($message) {
                return str_replace('validation.', '', __('validation.' . $message));
            }, $messages);*/

            // 占位符处理
            $placeholder = isset($placeholders[$column]) ? $placeholders[$column] : [];
            // 单条错误
            $errors[$column] = str_replace('validation.', '', __('validation.' . $messages[0], $placeholder));
        }

        $data = [
            'code'   => 400,
            // 'message' => str_replace('validation.', '', __('validation.' . $error)),
            'errors' => $errors
        ];
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        $this->stopPropagation();

        return $response->withStatus(200)
            ->withBody(new SwooleStream($data))
            ->withHeader('Content-Type', 'application/json;charset=utf-8');
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}
