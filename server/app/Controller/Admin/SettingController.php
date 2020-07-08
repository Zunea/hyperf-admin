<?php
declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Service\ConfigService;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 系统设置控制器
 *
 * @Controller()
 * @author Zunea(aile8880@qq.com)
 * @package App\Controller
 */
class SettingController extends AbstractController
{
    /**
     * @Inject()
     * @var ConfigService
     */
    private $configService;

    /**
     * 获取配置列表接口
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = $this->request->all();

        $result = $this->configService->configDAO->getSettingList($params);

        $this->success($result);
    }

    /**
     * 配置修改接口
     *
     * @PutMapping(path="")
     */
    public function update()
    {
        $data = $this->request->all();

        $this->configService->setConfigs($data);

        $this->success();
    }
}