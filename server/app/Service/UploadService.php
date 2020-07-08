<?php
declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Service;

use App\Common\Base;
use App\Constants\Constants;
use App\Exception\LogicException;

use Hyperf\HttpMessage\Upload\UploadedFile;
use League\Flysystem\FileExistsException;

/**
 * 上传服务
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Service
 */
class UploadService extends Base
{
    /**
     * 文件上传处理
     *
     * @param UploadedFile $file
     * @param string $prefix
     * @return bool|array
     */
    public function handle(UploadedFile $file, string $prefix)
    {
        try {
            // 判断文件类型
            $mime      = strtolower($file->getMimeType());
            $directory = '';
            foreach (Constants::UPLOADS_CONFIG as $option) {
                if (in_array($mime, $option['mime'])) {
                    // 文件大小限制
                    if ($file->getSize() > $option['maxSize']) {
                        throw new LogicException('logic.IMAGE_SIZE_EXCEED');
                    }

                    // 目录
                    $directory = $option['directory'];
                }
            }

            // 如果没有取到目录，不支持的文件类型
            if ($directory === '') {
                throw new LogicException('logic.FILE_FORMAT_ERROR');
            }

            // 获取文件路径
            $tmp_file  = (string)$file->getRealPath();
            $file_name = md5_file($tmp_file) . '.' . $file->getExtension();

            $uri = $directory . '/' . $prefix . $file_name;
            // 执行上传
            if (!$this->upload()->writeStream($uri, fopen($tmp_file, 'r+'))) {
                throw new LogicException('logic.UPLOAD_FAIL');
            }
        } catch (LogicException $e) {
            // 逻辑错误
            $this->error($e->getMessage());
        } catch (FileExistsException $e) {
            // 文件已存在，正常返回不需要抛异常
        } catch (\Exception $e) {
            // 未知错误
            $this->logger('upload')->error($e->getMessage());
            $this->error('logic.UPLOAD_FAIL');
        }

        return [
            'filename' => $file->getClientFilename(),
            'uri'      => $uri ?? null,
            'full_uri' => env('OSS_URL') . ($uri ?? null)
        ];
    }
}