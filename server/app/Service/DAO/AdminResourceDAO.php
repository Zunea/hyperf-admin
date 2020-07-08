<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\AdminGroupResource;
use App\Model\AdminResource;
use App\Model\AdminUserResource;

use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Annotation\CachePut;
use Hyperf\DbConnection\Db;

/**
 * 后台资源DAO
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Service\DAO
 */
class AdminResourceDAO extends Base
{
    /**
     * 通过ID获取资源
     *
     * @Cacheable(prefix="AdminResource", ttl=3600, listener="AdminResourceUpdate")
     * @param int $id
     * @return mixed
     */
    public function first(int $id): ?AdminResource
    {
        return AdminResource::query()->find($id);
    }

    /**
     * 获取资源
     *
     * @Cacheable(prefix="AdminResourceByPath", ttl=3600, listener="AdminResourceByPathUpdate")
     * @param string $path
     * @param string $method
     * @return mixed
     */
    public function getResourceByPath(string $path, string $method): ?AdminResource
    {
        return AdminResource::query()->where('path', $path)->where('method', $method)->first();
    }

    /**
     * 获取资源列表
     *
     * @param array $params
     * @return mixed
     */
    public function getResourceList(array $params = [])
    {
        $model = AdminResource::query();

        if (isset($params['name'])) {
            if (isset($params['name']) && $params['name'] !== '') {
                $model = $model->where('name', 'like', '%' . $params['name'] . '%');
            }
        }

        if (isset($params['path']) && $params['path'] !== '') {
            $model = $model->where('path', 'like', '%' . $params['path'] . '%');
        }

        if (isset($params['method']) && in_array($params['method'], ['GET', 'POST', 'PUT', 'DELETE'])) {
            $model = $model->where('method', $params['method']);
        }

        $model = $model->orderBy('id');

        return isset($params['perPage']) ? $model->paginate((int)$params['perPage']) : $model->get();
    }

    /**
     * 创建资源
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): ?AdminResource
    {
        if (AdminResource::query()->where('path', $data['path'])->where('method', $data['method'])->exists()) {
            $this->formError([
                'path'   => 'logic.RESOURCE_EXISTED',
                'method' => 'logic.RESOURCE_EXISTED'
            ]);
        }

        $resource = AdminResource::query()->create([
            'name'   => $data['name'],
            'path'   => $data['path'],
            'method' => $data['method']
        ]);

        if ($resource) {
            // 清缓存
            $this->flushCache('AdminResourceByPathUpdate', [$resource->path, $resource->method]);
        }

        return $resource;
    }

    /**
     * 修改数据
     *
     * @CachePut(prefix="AdminResource", value="#{id}")
     * @param int $id
     * @param array $data
     * @return AdminResource
     */
    public function update(int $id, array $data): ?AdminResource
    {
        /** @var AdminResource $resource */
        if (!$resource = $this->first($id)) {
            $this->error('logic.RESOURCE_NOT_FOUND');
        }
        $resource->name   = $data['name'];
        $resource->path   = $data['path'];
        $resource->method = $data['method'];
        if (AdminResource::query()
            ->where('path', $resource->path)
            ->where('method', $resource->method)
            ->where('id', '<>', $resource->id)
            ->exists()
        ) {
            $this->formError([
                'path'   => 'logic.RESOURCE_EXISTED',
                'method' => 'logic.RESOURCE_EXISTED'
            ]);
        }
        $resource->save();

        // 清缓存
        $this->flushCache('AdminResourceByPathUpdate', [$resource->path, $resource->method]);

        return $resource;
    }

    /**
     * 删除数据
     * @param int $id
     */
    public function delete(int $id)
    {
        if (!$resource = $this->first($id)) {
            return;
        }
        if ($resource->is_system === true) {
            return;
        }

        Db::transaction(function () use ($resource) {
            // 手动清理缓存
            $this->flushCache('AdminResourceByPathUpdate', [$resource->path, $resource->method]);
            $this->flushCache('AdminResourceUpdate', [$resource->id]);
            try {
                $resource->delete();
            } catch (\Exception $e) {
            }

            // 删除用户组和资源的关联
            AdminGroupResource::query()->where('resource_id', $resource->id)->delete();
            // 删除用户和资源的关联
            // AdminUserResource::query()->where('resource_id', $resource->id)->delete();
        });
    }
}