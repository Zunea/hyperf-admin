<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Request\Menu;

use App\Request\RequestAbstract;

/**
 * UpdateRequest
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Request\User
 */
class UpdateRequest extends RequestAbstract
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
            'name'      => 'required_if:type,1|alpha_dash|max:30',
            'type'      => 'required|in:1,2',
            'icon'      => 'alpha_dash|max:30',
            'path'      => 'required|alpha_dash|max:30',
            'component' => 'max:50',
            'target'    => 'required|in:1,2',
            'status'    => 'required|in:1,2',
        ];
    }
}