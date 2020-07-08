<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Request\Config;

use App\Request\RequestAbstract;

/**
 * ConfigRequest
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Request\User
 */
class ConfigRequest extends RequestAbstract
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'   => ['required', 'max:50'],
            'title'  => ['required', 'max:50'],
            'group'  => ['max:50'],
            'type'   => ['required', 'max:50'],
            'tips'   => ['max:50'],
            'format' => ['max:50'],
        ];
    }
}