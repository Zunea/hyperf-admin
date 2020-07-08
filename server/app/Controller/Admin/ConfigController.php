<?php
declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Request\Config\ConfigRequest;
use App\Service\ConfigService;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 后台配置管理
 *
 * @Controller()
 * @author Zunea(aile8880@qq.com)
 * @package App\Controller
 */
class ConfigController extends AbstractController
{
    /**
     * @Inject()
     * @var ConfigService
     */
    private $configService;

    /**
     * 获取接口
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = $this->request->all();

        $result = $this->configService->configDAO->getConfigList($params);

        $this->success($result);
    }

    /**
     * 创建接口
     *
     * @PostMapping(path="")
     * @param ConfigRequest $request
     */
    public function create(ConfigRequest $request)
    {
        $data = $request->post();

        isset($data['sort']) && $data['sort'] = (int)$data['sort'];

        $this->configService->configDAO->create($data);

        $this->success();
    }

    /**
     * 修改接口
     *
     * @PutMapping(path="{id}")
     * @param int $id
     * @param ConfigRequest $request
     */
    public function update(int $id, ConfigRequest $request)
    {
        $data = $request->post();

        isset($data['sort']) && $data['sort'] = (int)$data['sort'];
        isset($data['is_enable']) && $data['is_enable'] = (boolean)$data['is_enable'];

        $this->configService->configDAO->update($id, $data);

        $this->success();
    }

    /**
     * 批量删除
     *
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });
        $this->configService->configDAO->delete($ids);

        $this->success();
    }

    /**
     * 批量启用
     *
     * @PutMapping(path="enable/{ids}")
     * @param string $ids
     */
    public function enable(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });
        $this->configService->configDAO->setEnable($ids, 1);

        $this->success();
    }

    /**
     * 批量禁用
     *
     * @PutMapping(path="disable/{ids}")
     * @param string $ids
     */
    public function disable(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });
        $this->configService->configDAO->setEnable($ids, 0);

        $this->success();
    }
}