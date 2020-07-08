<?php

declare(strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */
namespace App\Request\Auth;

use App\Request\RequestAbstract;

/**
 * 登陆验证器
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Controller
 */
class LoginRequest extends RequestAbstract
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
            'password' => 'required|alpha_dash|between:6,30'
        ];
    }
}
