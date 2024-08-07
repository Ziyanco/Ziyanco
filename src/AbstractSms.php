<?php

namespace Ziyanco\Library;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;
use Ziyanco\Library\Tool\RedisOptions;

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
    private static $configPrefix = 'cosms';
    const REDIS_SMS_CONFIG_DEFAULT = 'sms:config:sms_default';  //redis缓存KEY

    /**
     * @param $mobile
     * @return bool
     */
    public static function sendSmsCode($mobile): bool
    {
        $container = di(ConfigInterface::class);
        $config = $container->get(static::$configPrefix);
        $default = $config['sms_default']; //发送渠道
        $smsDefault = RedisOptions::get(static::REDIS_SMS_CONFIG_DEFAULT);
        if (!empty($smsDefault)) {
            $default = $smsDefault;
        }
        $driverClass = $config['sms'][$default]['driver'];
        $class = di($driverClass);
        $res=$class::sendSms($mobile);
        return $res;
    }

    /**
     * @param $mobile
     * @param $code
     * @return bool
     */
    public static function checkSms($mobile, $code): bool{
        $container = di(ConfigInterface::class);
        $config = $container->get(static::$configPrefix);
        $default = $config['sms_default']; //发送渠道
        $smsDefault = RedisOptions::get(static::REDIS_SMS_CONFIG_DEFAULT);
        if (!empty($smsDefault)) {
            $default = $smsDefault;
        }
        $driverClass = $config['sms'][$default]['driver'];
        $class = di($driverClass);
        $res=$class::checkSms($mobile,$code);
        return $res;
    }


}