<?php

declare (strict_types=1);

namespace App\Model;

/**
 * 后台资源模型
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property string $method
 * @property boolean $is_system
 */
class AdminResource extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_resource';
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
    protected $casts = ['id' => 'integer', 'is_system' => 'boolean'];
}