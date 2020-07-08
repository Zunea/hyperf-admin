<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int $user_id
 * @property int $group_id
 */
class AdminUserGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user_group';
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
    protected $casts = ['user_id' => 'integer', 'group_id' => 'integer'];
}