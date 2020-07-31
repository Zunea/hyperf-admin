<?php
declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Request\Resource\ResourceRequest;
use App\Service\DAO\AdminResourceDAO;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 后台资源管理
 *
 * @Controller()
 * @author Zunea(aile8880@qq.com)
 * @package App\Controller
 */
class ResourceController extends AbstractController
{
    /**
     * @Inject()
     * @var AdminResourceDAO
     */
    private $resourceDAO;

    /**
     * 获取资源列表接口
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = $this->request->all();

        $result = $this->resourceDAO->getResourceList($params);

        $this->success($result);
    }

    /**
     * 创建后台资源接口
     *
     * @PostMapping(path="")
     * @param ResourceRequest $request
     */
    public function create(ResourceRequest $request)
    {
        $data = $request->post();

        $this->resourceDAO->create($data);

        $this->success();
    }

    /**
     * 修改后台资源接口
     *
     * @PutMapping(path="{id}")
     * @param int $id
     * @param ResourceRequest $request
     */
    public function update(int $id, ResourceRequest $request)
    {
        $data = $request->post();

        $this->resourceDAO->update($id, $data);

        $this->success();
    }

    /**
     * 删除资源
     *
     * @DeleteMapping(path="{id}")
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->resourceDAO->delete($id);

        $this->success();
    }
}