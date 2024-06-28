<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace Ziyanco\Library;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'listeners' => [],
            // 合并到  config/autoload/annotations.php 文件
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'sms',
                    'description' => 'sms-config',
                    'source' => __DIR__ . '/../publish/cosms.php',
                    'destination' => BASE_PATH . '/config/autoload/cosms.php',
                ],
            ],
        ];
    }
}
