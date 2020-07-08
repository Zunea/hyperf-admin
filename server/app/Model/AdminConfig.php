<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $group
 * @property string $type
 * @property string $value
 * @property string $options
 * @property object $attr
 * @property string $tips
 * @property string $format
 * @property int $sort
 * @property boolean $is_enable
 * @property boolean $is_system
 * @property int $create_time
 * @property int $update_time
 * @property string $value_type
 */
class AdminConfig extends Model
{
    /**
     * @var string
     */
    public $dateFormat = 'U';

    /**
     * @var string
     */
    const CREATED_AT = 'create_time';

    /**
     * @var string
     */
    const UPDATED_AT = 'update_time';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_config';
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
        'sort'        => 'integer',
        'is_enable'   => 'boolean',
        'is_system'   => 'boolean',
        'create_time' => 'integer',
        'update_time' => 'integer'
    ];

    /**
     * @var array
     */
    protected $appends = ['options_parse'];

    /**
     * @var array
     */
    protected $hidden = ['create_time', 'update_time', 'is_system'];

    /**
     * 值类型转换
     *
     * @param $value
     * @return array|string
     */
    public function getValueAttribute($value)
    {
        // 防止返回null值
        $value = $value === null ? '' : $value;

        switch ($this->value_type) {
            case 'integer': // 整数
                return (int)$value;

            case 'float': // 浮点数
                return (float)$value;

            case 'boolean': // 布尔值
                return (boolean)$value;

            case 'array': // 数组
                $array = json_decode($value, true);
                return $array === null ? [] : $array;

            default: // 字符串
                return $value;
        }
    }

    /**
     * 对选项的解析
     *
     * @return string
     */
    public function getOptionsParseAttribute()
    {
        if (!empty($this->options) && in_array($this->type, ['checkbox', 'radio', 'select'])) {
            return array_map(function ($option) {
                $opt = explode(':', $option);
                return [
                    'label' => trim(count($opt) > 1 ? $opt[1] : $option),
                    'value' => trim(count($opt) > 1 ? $opt[0] : $option)
                ];
            }, explode(PHP_EOL, $this->options));
        } else {
            return $this->options;
        }
    }

    /**
     * 组件属性，获取器
     *
     * @param $value
     * @return object
     */
    public function getAttrAttribute($value)
    {
        // 处理PHP和JS对`[]`和`{}`对象和数组的差异
        return (object)json_decode($value === null ? '{}' : $value);
    }

    /**
     * 组件属性，修改器
     *
     * @param $value
     */
    public function setAttrAttribute($value)
    {
        // 处理PHP和JS对`[]`和`{}`对象和数组的差异
        $this->attributes['attr'] = count($value) === 0 ? '{}' : json_encode($value);
    }

    /**
     * 值类型获取器
     *
     * @return string
     */
    public function getValueTypeAttribute()
    {
        switch ($this->getOriginal('type')) {
            // 必定返回数值的组件
            case 'number':
                // 如果精度属性等于0则代表整数，否则为小数
                return ($this->attr->precision ?? 0) === 0 ? 'integer' : 'float';

            // 必定返回布尔的组件
            case 'switch':
                return 'boolean';

            // 必定返回数组的组件
            case 'checkbox':
            case 'array':
            case 'tag':
                return 'array';

            // 键值对组件
            case 'key-value':
                return 'key-value';

            // 可能返回数组的组件
            case 'date':
                $in = ['dates', 'datetimerange', 'daterange', 'monthrange'];
                return in_array(($this->attr->type ?? ''), $in) ? 'array' : 'string';

            // 可能返回数组的组件
            case 'time':
                return ($this->attr->range ?? '') === true ? 'array' : 'integer';

            // 可能返回数组的组件
            case 'select':
                return ($this->attr->multiple ?? '') === true ? 'array' : 'string';

            // 可能返回数组的组件
            case 'upload':
                return ($this->attr->limit ?? 1) > 1 ? 'array' : 'string';

            // 可能返回数组的组件
            case 'slider':
                return ($this->attr->range ?? '') === true ? 'array' : 'integer';

            default:
                return 'string';
        }
    }
}