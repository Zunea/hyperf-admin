<?php
declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Request\Menu\AddRequest;
use App\Request\Menu\UpdateRequest;
use App\Service\DAO\AdminMenuDAO;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 后台菜单管理
 *
 * @Controller()
 * @author Zunea(aile8880@qq.com)
 * @package App\Controller
 */
class MenuController extends AbstractController
{
    /**
     * @Inject()
     * @var AdminMenuDAO
     */
    private $menuDAO;

    /**
     * 获取菜单列表接口
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = $this->request->all();

        $result = $this->menuDAO->getMenuList($params);

        $this->success($result);
    }

    /**
     * 创建菜单接口
     *
     * @PostMapping(path="")
     * @param AddRequest $request
     */
    public function create(AddRequest $request)
    {
        $data = $request->post();

        isset($data['parent_id']) && $data['parent_id'] = (int)$data['parent_id'];
        isset($data['type']) && $data['type'] = (int)$data['type'];
        isset($data['sort']) && $data['sort'] = (int)$data['sort'];

        $this->menuDAO->create($data);

        $this->success();
    }

    /**
     * 修改菜单接口
     *
     * @PutMapping(path="{id}")
     * @param int $id
     * @param UpdateRequest $request
     */
    public function update(int $id, UpdateRequest $request)
    {
        $data = $request->post();

        isset($data['parent_id']) && $data['parent_id'] = (int)$data['parent_id'];
        isset($data['type']) && $data['type'] = (int)$data['type'];
        isset($data['left_show']) && $data['left_show'] = (bool)$data['left_show'];
        isset($data['top_show']) && $data['top_show'] = (bool)$data['top_show'];
        isset($data['target']) && $data['target'] = (int)$data['target'];
        isset($data['status']) && $data['status'] = (int)$data['status'];
        isset($data['sort']) && $data['sort'] = (int)$data['sort'];
        isset($data['status']) && $data['status'] = (bool)$data['status'];

        $this->menuDAO->update($id, $data);

        $this->success();
    }

    /**
     * 删除菜单接口
     *
     * @DeleteMapping(path="{id}")
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->menuDAO->delete($id);

        $this->success();
    }
}