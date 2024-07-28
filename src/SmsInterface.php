<?php

namespace Ziyanco\Library;

interface SmsInterface
{
    /**
     * 发送短信
     * @param $params
     * @return bool
     */
    public static function sendSmsCode($params): bool;

    /**
     * 检验短信是否正确
     * @param $mobile
     * @param $code
     * @return bool
     */
    public static function checkSms($mobile, $code): bool;
}