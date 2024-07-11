<?php

namespace Ziyanco\Library;

interface SmsInterface
{
    public static function sendSmsCode($params);
}