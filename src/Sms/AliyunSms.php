<?php

namespace Ziyan\Ziyanco\Sms;

use Ziyan\Ziyanco\Extends\RequestLibrary;
use Ziyanco\Library\Tool\RedisOptions;

class AliyunSms
{
    const ALI_DIGIT = 4;  //短信位数
    const ALI_APP_CODE = '';//阿里code
    const ALI_TEMPLATE_ID = '';//阿里code
    const ALI_REDIS_USE_TIME = 60;  //redis缓存
    const POST_RUL = 'https://dfsns.market.alicloudapi.com/data/send_sms';//阿里code
    const REDIS_KEY_SEND_PHONE = 'sms:send:phone:mobile_%s';  //redis缓存KEY

    /**
     * 发送短信
     * @param $mobile
     * @param $code
     * @return void
     */
    public static function sendSms($mobile, $code): bool
    {
        $postData = [];
        $code = rand(pow(10, AliyunSms::ALI_DIGIT), pow(10, AliyunSms::ALI_DIGIT) - 1);
        $postData['content'] = 'code:' . $code;
        $postData['phone_number'] = $mobile;
        $postData['template_id'] = \Hyperf\Support\env('TEMPLATE_ID', AliyunSms::ALI_TEMPLATE_ID);
        $res = RequestLibrary::requestPostResultJsonData(AliyunSms::POST_RUL, [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'APPCODE ' . \Hyperf\Support\env('ALI_APP_CODE', $postData, AliyunSms::ALI_APP_CODE)
        ], RequestLibrary::TYPE_BUILD_QUERY);
        if (strtolower($res['status']) == 'ok') {
            RedisOptions::set(sprintf(AliyunSms::REDIS_KEY_SEND_PHONE, $mobile), $code, \Hyperf\Support\env('ALI_REDIS_USE_TIME', AliyunSms::ALI_REDIS_USE_TIME));
        } else {
            throw new \ErrorException('短信发送失败!');
        }
        return true;
    }

    /**
     * 检测短信
     * @param $mobile
     * @param $code
     * @return void
     */
    public function checkSms($mobile, $code): bool
    {
        $redisKey = sprintf(AliyunSms::REDIS_KEY_SEND_PHONE, $mobile);
        $phoneCode = RedisOptions::get($redisKey);
        if ($phoneCode !== $code) {
            return false;
        }
        return true;
    }
}