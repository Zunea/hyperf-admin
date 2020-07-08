<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;

/**
 * 后台用户组菜单关联模型
 *
 * @property int $group_id 
 * @property int $menu_id 
 */
class AdminGroupMenu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_group_menu';
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
    protected $casts = ['group_id' => 'integer', 'menu_id' => 'integer'];

    /**
     * 关联后台用户组(有效的)
     *
     * @return HasOne
     */
    public function group(): HasOne
    {
        return $this->hasOne(AdminGroup::class,'id', 'group_id')->where('status', 1);
    }
}