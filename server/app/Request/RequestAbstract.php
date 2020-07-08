<?php

declare(strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */
namespace App\Request;

use Hyperf\Utils\Context;
use Hyperf\Validation\Request\FormRequest;

/**
 * 验证器基类
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Controller
 */
abstract class RequestAbstract extends FormRequest
{
    public function messages(): array
    {
        $deep = str_replace('\\', '.', get_called_class());
        $deep = str_replace('App.Request.', '', $deep);

        $messages = [];
        $placeholders = [];
        foreach ($this->rules() as $name => $rules) {
            $rule_map = is_array($rules) ? $rules : explode('|', $rules);
            foreach ($rule_map as $map) {
                $map = explode(':', $map);
                if (count($map) > 1) {
                    $placeholders[$name][$map[0]] = $map[1];
                }
                // 驼峰转小写
                $map = strtolower(preg_replace('/([A-Z])/','_$1', $map[0]));
                $key = $name . '.' . $map;
                $messages[$key] = "{$deep}.{$name}.{$map}";
            }
        }
        Context::set('ValidationPlaceholders', $placeholders);

        return $messages;
    }
}
