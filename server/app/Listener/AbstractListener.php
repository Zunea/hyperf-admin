<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

namespace App\Listener;

use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;

/**
 * 监听器抽象类
 *
 * @author Zunea(aile8880@qq.com)
 * @package App\Listener
 */
abstract class AbstractListener
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;
}