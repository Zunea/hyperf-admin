<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\AdminConfig;
use App\Service\ConfigService;

use Hyperf\Database\Model\Builder;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * 后台配置DAO
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Service\DAO
 */
class AdminConfigDAO extends Base
{
    /**
     * 获取配置列表
     *
     * @param array $params
     * @return mixed
     */
    public function getConfigList(array $params = [])
    {
        $model = AdminConfig::query();

        // 配置分组
        if (isset($params['group']) && $params['group'] !== '') {
            $model = $model->where('group', $params['group']);
        }
        // 配置名
        if (isset($params['name']) && $params['name'] !== '') {
            $model = $model->where('name', 'like', '%' . $params['name'] . '%');
        }
        // 配置类型
        if (isset($params['type']) && $params['type'] !== '') {
            $model = $model->where('type', $params['type']);
        }
        // 配置标题
        if (isset($params['title']) && $params['title'] !== '') {
            $model = $model->where('title', 'like', '%' . $params['title'] . '%');
        }

        $model = $model->orderBy('sort')->orderBy('id');

        return isset($params['perPage']) ? $model->paginate((int)$params['perPage']) : $model->get();
    }

    /**
     * 获取网站设置列表
     *
     * @param array $params
     * @return array
     */
    public function getSettingList(array $params = []): array
    {
        $model = AdminConfig::query();

        if (isset($params['group']) && $params['group'] !== '') {
            $model = $model->where('group', $params['group']);
        }

        $model = $model->where('is_enable', 1);

        $model = $model->orderBy('sort')->orderBy('id');

        $model = $model->select('name', 'title', 'type', 'value', 'options', 'attr', 'tips', 'format');

        return $model->get()->toArray();
    }

    /**
     * 创建数据
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): ?AdminConfig
    {
        // 检查组件是否被使用
        if ($this->checkValueUnique('name', trim($data['name']))) {
            $this->formError([
                'name' => 'logic.CONFIG_NAME_EXISTED'
            ]);
        }

        return AdminConfig::query()->create([
            'name'    => $data['name'],
            'title'   => $data['title'],
            'group'   => $data['group'],
            'type'    => $data['type'],
            'options' => $data['options'],
            'attr'    => $data['attr'],
            'tips'    => $data['tips'],
            'format'  => $data['format'],
            'sort'    => $data['sort'],
        ]);
    }

    /**
     * 修改数据
     *
     * @param int $id
     * @param array $data
     * @return AdminConfig
     */
    public function update(int $id, array $data): ?AdminConfig
    {
        /** @var AdminConfig $config */
        if (!$config = AdminConfig::query()->find($id)) {
            $this->error('logic.CONFIG_NOT_FOUND');
        }
        // 检查配置名是否被使用
        if (isset($data['name']) && $data['name'] !== $config->name) {
            if ($this->checkValueUnique('name', trim($data['name']))) {
                $this->formError([
                    'name' => 'logic.CONFIG_NAME_EXISTED'
                ]);
            }
            $config->name = trim($data['name']);
        }
        $config->title     = $data['title'];
        $config->group     = $data['group'];
        $config->type      = $data['type'];
        $config->options   = $data['options'];
        $config->tips      = $data['tips'];
        $config->format    = $data['format'];
        $config->sort      = $data['sort'];
        $config->is_enable = $data['is_enable'];
        // 如果出现切换类型的场景，需要对value进行清空，以免类型切换的时候出现异常
        if ((array)$config->attr !== $data['attr'] || $config->getOriginal('type') !== $data['type']) {
            $config->value = null;
        }
        $config->attr = $data['attr'];
        $config->save();

        return $config;
    }

    /**
     * 批量删除配置
     *
     * @param array $ids
     * @return int
     */
    public function delete(array $ids)
    {
        if (empty($ids)) return 0;
        // 删除配置缓存
        try {
            $this->cache->delete($this->container->get(ConfigService::class)::CACHE_NAME);
        } catch (InvalidArgumentException $e) {
        }

        return AdminConfig::query()->whereIn('id', $ids)->where('is_system', 0)->delete();
    }

    /**
     * 修改配置启用状态
     *
     * @param array $ids
     * @param int $is_enable
     * @return int
     */
    public function setEnable(array $ids, int $is_enable)
    {
        if (empty($ids)) return 0;
        // 删除配置缓存
        try {
            $this->cache->delete($this->container->get(ConfigService::class)::CACHE_NAME);
        } catch (InvalidArgumentException $e) {
        }

        return AdminConfig::query()->whereIn('id', $ids)->where('is_system', 0)->update([
            'is_enable' => $is_enable
        ]);
    }

    /**
     * 检查唯一性
     *
     * @param string $column
     * @param string $value
     * @return bool
     */
    public function checkValueUnique(string $column, string $value): bool
    {
        if ($value === '') return false;

        return AdminConfig::query()->where($column, $value)->exists();
    }

    /**
     * 按名称更新值
     *
     * @param string $name
     * @param mixed $value
     * @return int
     */
    public function updateValueByName(string $name, $value)
    {
        return AdminConfig::query()->where('name', $name)->update([
            'value' => $value
        ]);
    }
}