<?php
declare (strict_types=1);
/**
 * @copyright 深圳市易果网络科技有限公司
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Request\Account;

use App\Request\RequestAbstract;

/**
 * 修改密码验证器
 *
 * @author 刘兴永(aile8880@qq.com)
 * @package App\Request\Account
 */
class ChangePasswordRequest extends RequestAbstract
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
            'old_password'              => 'required|alpha_dash|between:6,30',
            'new_password'              => 'required|confirmed|alpha_dash|between:6,30',
            'new_password_confirmation' => 'required',
        ];
    }
}