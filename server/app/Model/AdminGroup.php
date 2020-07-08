<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsToMany;

/**
 * 后台用户组模型
 *
 * @property int $id
 * @property string $name
 * @property string $remark
 * @property int $status
 */
class AdminGroup extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_group';
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
    protected $casts = ['id' => 'integer', 'status' => 'integer'];

    /**
     * 菜单关联
     *
     * @return BelongsToMany
     */
    public function menus()
    {
        return $this->belongsToMany(AdminMenu::class, 'admin_group_menu', 'group_id', 'menu_id')->select('id', 'name');
    }

    /**
     * 资源关联
     *
     * @return BelongsToMany
     */
    public function resources()
    {
        return $this->belongsToMany(AdminResource::class, 'admin_group_resource', 'group_id', 'resource_id');
    }
}