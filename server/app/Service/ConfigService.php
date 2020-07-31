<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Service;

use App\Common\Base;
use App\Model\AdminConfig;
use App\Service\DAO\AdminConfigDAO;

use Hyperf\Di\Annotation\Inject;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * 系统配置服务
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Service
 */
class ConfigService extends Base
{
    /**
     * 配置组件列表
     */
    const CONFIG_COMPONENTS = [
        'text'     => 'CONFIG_INPUT_TEXT',
        'keyValue' => 'CONFIG_KEY_VALUE',
        'number'   => 'CONFIG_INPUT_NUMBER',
        'checkbox' => 'CONFIG_CHECKBOX',
        'radio'    => 'CONFIG_RADIO',
        'date'     => 'CONFIG_DATE',
        'time'     => 'CONFIG_TIME',
        'switch'   => 'CONFIG_SWITCH',
        'select'   => 'CONFIG_SELECT',
        'upload'   => 'CONFIG_UPLOAD',
        'slider'   => 'CONFIG_SLIDER',
        'color'    => 'CONFIG_COLOR'
    ];

    /**
     * 缓存名
     *
     * @var string
     */
    const CACHE_NAME = 'AppConfigs';

    /**
     * 缓存有效期
     *
     * @var int
     */
    const CACHE_TTL = 3600;

    /**
     * @Inject()
     * @var AdminConfigDAO
     */
    public $configDAO;

    /**
     * 获取配置
     *
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        $configs = $this->getConfigs();

        return $configs[$name] ?? config($name, $default);
    }

    /**
     * 设置配置接口
     *
     * @param array $data
     */
    public function setConfigs(array $data)
    {
        foreach ($data as $name => $value) {
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
            $this->configDAO->updateValueByName($name, $value);
        }
        // 删除配置缓存
        try { $this->cache->delete(self::CACHE_NAME); } catch (InvalidArgumentException $e) {}
    }

    /**
     * 初始化配置
     */
    public function initConfigs()
    {
        try {
            $this->cache->delete(self::CACHE_NAME);
        } catch (InvalidArgumentException $e) {
        }
        $this->getConfigs();
    }

    /**
     * 全部配置列表
     *
     * @return array
     */
    private function getConfigs()
    {
        try {
            if ($this->cache->has(self::CACHE_NAME)) {
                return $this->cache->get(self::CACHE_NAME);
            }
        } catch (InvalidArgumentException $e) {}

        $result = [];
        // 值类型转换
        foreach ($this->configDAO->getConfigList() as $config) {
            /** @var AdminConfig $config */
            $formatMethod = 'format' . ucfirst($config->type) . 'Value';
            if (method_exists($this, $formatMethod)) {
                $result[$config->name] = $this->{$formatMethod}($config);
            } else {
                $result[$config->name] = $config->value;
            }
        }

        // 缓存配置
        try {
            $this->cache->set(self::CACHE_NAME, $result, self::CACHE_TTL);
        } catch (InvalidArgumentException $e) {}

        return $result;
    }

    /**
     * Number组件
     *
     * @param AdminConfig $config
     * @return int
     */
    /* private function formatNumberValue(AdminConfig $config): int
    {
        $value = $config->getOriginal('value');

        return (int)$value;
    } */

    /**
     * Json组件
     *
     * @param AdminConfig $config
     * @return array
     */
    /* private function formatJsonValue(AdminConfig $config)
    {
        $value = $config->getOriginal('value');

        return $value === null ? [] : json_decode($value, true);
    } */

    /**
     * Checkbox组件
     *
     * @param AdminConfig $config
     * @return array
     */
    /* private function formatCheckboxValue(AdminConfig $config)
    {
        $value = $config->getOriginal('value');

        return $value === null ? [] : json_decode($value, true);
    } */

    /**
     * Date组件
     *
     * @param AdminConfig $config
     * @return array|string
     */
    /* private function formatDateValue(AdminConfig $config)
    {
        $value = $config->getOriginal('value');

        // 处理日期组件的多选属性，这时应该返回一个数组
        if ($config->type === 'date' && in_array($config->attr->type ?? '', ['dates', 'datetimerange', 'daterange', 'monthrange'])) {
            return $value === null ? [] : json_decode($value, true);
        }

        return $value;
    } */

    /**
     * Switch组件
     *
     * @param AdminConfig $config
     * @return bool
     */
    /* private function formatSwitchValue(AdminConfig $config): bool
    {
        $value = $config->getOriginal('value');

        return (bool)$value;
    } */

    /**
     * Select组件
     *
     * @param AdminConfig $config
     * @return array|string
     */
    /* private function formatSelectValue(AdminConfig $config)
    {
        $value = $config->getOriginal('value');

        if (($config->attr->multiple ?? false) === true) {
            return $value === null ? [] : json_decode($value, true);
        }

        return $value;
    } */

    /**
     * Upload组件
     *
     * @param AdminConfig $config
     * @return array|string
     */
    /* private function formatUploadValue(AdminConfig $config)
    {
        $value = $config->getOriginal('value');

        if (($config->attr->multiple ?? false) === true) {
            return $value === null ? [] : json_decode($value, true);
        }

        return $value;
    } */

    /**
     * Tag组件
     *
     * @param AdminConfig $config
     * @return array
     */
    /* private function formatTagValue(AdminConfig $config)
    {
        $value = $config->getOriginal('value');

        return $value === null ? [] : json_decode($value, true);
    } */

    /**
     * Slider组件
     *
     * @param AdminConfig $config
     * @return array|string
     */
    /* private function formatSliderValue(AdminConfig $config)
    {
        $value = $config->getOriginal('value');

        if (($config->attr->range ?? false) === true) {
            return $value === null ? [] : json_decode($value, true);
        }

        return $value;
    } */

    /**
     * 键值对组件
     *
     * @param AdminConfig $config
     * @return mixed
     */
    private function formatKeyValueValue(AdminConfig $config)
    {
        if (empty($config->value)) return [];
        $result = [];

        foreach (explode(PHP_EOL, $config->value) as $option) {
            $opt = explode(':', $option);
            $result[$opt[0]] = $opt[1] ?? $opt[0];
        }

        return $result;
    }
}