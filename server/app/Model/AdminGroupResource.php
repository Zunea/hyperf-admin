<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;

/**
 * @property int $group_id 
 * @property int $resource_id 
 */
class AdminGroupResource extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_group_resource';
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
    protected $casts = ['group_id' => 'integer', 'resource_id' => 'integer'];

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