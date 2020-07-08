<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int $user_id
 * @property int $menu_id
 */
class AdminUserMenu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user_menu';
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
    protected $casts = ['user_id' => 'integer', 'menu_id' => 'integer'];
}