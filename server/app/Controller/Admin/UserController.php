<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Request\User\AddRequest;
use App\Request\User\UpdateRequest;
use App\Service\DAO\AdminUserDAO;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 后台用户管理
 *
 * @Controller()
 * @author Zunea(aile8880@qq.com)
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Inject()
     * @var AdminUserDAO
     */
    private $userDAO;

    /**
     * 获取后台用户列表接口
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $data = $this->request->all();

        isset($data['group_id']) && $data['group_id'] = (int)$data['group_id'];

        $result = $this->userDAO->getUserList($data);

        $this->success($result);
    }

    /**
     * 创建后台用户接口
     *
     * @PostMapping(path="")
     * @param AddRequest $request
     */
    public function create(AddRequest $request)
    {
        $data = $request->post();

        $user = $this->userDAO->create($data);

        $this->userDAO->setUserGroup($user->id, array_map(function ($group) {
            return $group['id'];
        }, $data['groups'] ?? []));

        $this->success();
    }

    /**
     * 修改后台用户接口
     *
     * @PutMapping(path="{id}")
     * @param int $id
     * @param UpdateRequest $request
     */
    public function update(int $id, UpdateRequest $request)
    {
        $data = $request->post();

        $data['password'] = (isset($data['password']) && $data['password'] !== '') ? trim($data['password']) : '';
        isset($data['status']) && $data['status'] = (int)$data['status'];

        $this->userDAO->update($id, $data);

        $this->userDAO->setUserGroup($id, array_map(function ($group) {
            return $group['id'];
        }, $data['groups'] ?? []));

        $this->success();
    }

    /**
     * 删除用户接口
     *
     * @DeleteMapping(path="{id}")
     * @param int $id
     */
    public function delete(int $id)
    {
        if ($id <= 1) {
            $this->success();
        }

        $this->userDAO->delete($id);

        $this->success();
    }
}