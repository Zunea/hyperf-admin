<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Request\Resource;

use App\Request\RequestAbstract;

/**
 * ResourceRequest
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Request\User
 */
class ResourceRequest extends RequestAbstract
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
            'path'   => ['required', 'max:255'],
            'method' => ['required', 'in:GET,POST,PUT,DELETE']
        ];
    }
}