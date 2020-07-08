<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\SoftDeletes;

/**
 * 后台用户模型
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $nickname
 * @property string $phone
 * @property string $email
 * @property string $avatar
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 * @property AdminGroup $groups
 */
class AdminUser extends Model
{
    use SoftDeletes;

    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'integer',
        'status'     => 'integer',
        'created_at' => 'date:Y-m-d H:i',
        'updated_at' => 'date:Y-m-d H:i',
        'deleted_at' => 'integer'
    ];

    /**
     * @var array
     */
    protected $hidden = ['password', 'deleted_at'];

    /**
     * 用户组关联
     *
     * @return \Hyperf\Database\Model\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(AdminGroup::class, 'admin_user_group', 'user_id', 'group_id')->select('id', 'name');
    }

    /**
     * 密码修改器
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if ($value !== '') {
            $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
        }
    }
}