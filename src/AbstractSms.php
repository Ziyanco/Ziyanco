<?php

namespace Ziyanco\Library;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractSms implements SmsInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;
    /**
     * sms配置前缀
     * @var string
     */
    private $configPrefix = 'cosms';

    public function __construct(ContainerInterface $container)
    {
        echo 'aa------1----------->>' . PHP_EOL;
        $this->container = $container;
        $this->config = $this->container->get(ConfigInterface::class);
        $config = $this->config->get($this->configPrefix);
        print_r($config);

    }

    public static function sendSmsCode($params): bool
    {
        echo 'aa------2----------->>' . PHP_EOL;
        return true;
    }

}