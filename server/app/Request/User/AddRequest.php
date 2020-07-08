<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Request\User;

use App\Request\RequestAbstract;

/**
 * ResourceRequest
 *
 * @author 李想
 * @package App\Request\User
 */
class AddRequest extends RequestAbstract
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
            'username' => 'required|alpha_dash|between:5,30',
            'password' => 'required|alpha_dash|between:6,30',
            'nickname' => 'max:50',
            'phone'    => 'required|max:50',
            'email'    => 'email|max:50'
        ];
    }
}