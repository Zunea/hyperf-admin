<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Request\UserGroup;

use App\Request\RequestAbstract;

/**
 * UpdateRequest
 *
 * @author 李想
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
            'name'   => ['required', 'max:50'],
            'remark' => ['max:255'],
            'status' => ['required', 'in:1,2']
        ];
    }
}