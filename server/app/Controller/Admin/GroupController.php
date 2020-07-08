<?php
declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Request\UserGroup\AddRequest;
use App\Request\UserGroup\UpdateRequest;
use App\Service\DAO\AdminGroupDAO;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 后台用户组管理
 *
 * @Controller()
 * @author Zunea(aile8880@qq.com)
 * @package App\Controller
 */
class GroupController extends AbstractController
{
    /**
     * @Inject()
     * @var AdminGroupDAO
     */
    private $groupDAO;

    /**
     * 获取后台用户组列表接口
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $result = $this->groupDAO->getGroupList();

        $this->success($result);
    }

    /**
     * 创建后台用户组接口
     *
     * @PostMapping(path="")
     * @param AddRequest $request
     */
    public function create(AddRequest $request)
    {
        $data = $request->post();

        $this->groupDAO->create($data);

        $this->success();
    }

    /**
     * 修改用户组接口
     *
     * @PutMapping(path="{id}")
     * @param int $id
     * @param UpdateRequest $request
     */
    public function update(int $id, UpdateRequest $request)
    {
        $data = $request->post();

        isset($data['status']) && $data['status'] = (int)$data['status'];

        $this->groupDAO->update($id, $data);

        $this->success();
    }

    /**
     * 删除用户组接口
     *
     * @DeleteMapping(path="{id}")
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->groupDAO->delete($id);

        $this->success();
    }

    /**
     * 修改用户组菜单接口
     *
     * @PutMapping(path="menu/{id}")
     * @param int $id
     */
    public function menu(int $id)
    {
        $menuIds = $this->request->input('menuIds', []);

        $this->groupDAO->updateMenu($id, $menuIds);

        $this->success();
    }

    /**
     * 修改用户组资源接口
     *
     * @PutMapping(path="resource/{id}")
     * @param int $id
     */
    public function resource(int $id)
    {
        $resourceIds = $this->request->input('resourceIds', []);

        $this->groupDAO->updateResource($id, $resourceIds);

        $this->success();
    }
}