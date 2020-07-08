<?php

declare (strict_types=1);

namespace App\Model;

/**
 * 后台菜单模型
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $icon
 * @property int $type
 * @property string $path
 * @property string $component
 * @property boolean $top_show
 * @property boolean $left_show
 * @property int $target
 * @property int $status
 * @property int $sort
 * @property boolean $is_system
 */
class AdminMenu extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_menu';
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
        'id'        => 'integer',
        'parent_id' => 'integer',
        'type'      => 'integer',
        'top_show'  => 'boolean',
        'left_show' => 'boolean',
        'target'    => 'integer',
        'status'    => 'integer',
        'sort'      => 'integer',
        'is_system' => 'boolean'
    ];
}