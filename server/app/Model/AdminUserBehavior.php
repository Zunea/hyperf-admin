<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $action_ip
 * @property int $action_time
 * @property int $type
 * @property string $request_method
 * @property string $request_url
 */
class AdminUserBehavior extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user_behavior';
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
        'id'          => 'integer',
        'user_id'     => 'integer',
        'action_ip'   => 'integer',
        'action_time' => 'date:Y-m-d H:i:s',
        'type'        => 'integer'
    ];

    public function getActionIpAttribute($value)
    {
        return long2ip($value);
    }
}