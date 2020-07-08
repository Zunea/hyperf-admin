<?php
declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Service\UploadService;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * 文件上传
 *
 * @Controller()
 * @author Zunea(aile8880@qq.com)
 * @package App\Controller
 */
class UploadController extends AbstractController
{
    /**
     * @Inject()
     * @var UploadService
     */
    private $uploadService;

    /**
     * 单个文件上传
     *
     * @PostMapping(path="")
     */
    public function single()
    {
        if (!$file = $this->request->file('file')) {
            $this->error('logic.PLEASE_SELECT_FILE');
        }

        $result = $this->uploadService->handle($file, '');

        $this->success($result);
    }
}