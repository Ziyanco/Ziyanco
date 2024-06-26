<?php

namespace Ziyan\Ziyanco\Sms;

use Ziyan\Ziyanco\Extends\RequestLibrary;


class DingDingMessage
{
    const DINGDING_ACCESS_TOKEN = '';

    /**
     * 机器人发送消息勾子
     * @param $data
     * @return void
     */
    public static function send($message, $title = '异常通知:')
    {
        $data = ['msgtype' => 'markdown', 'markdown' => ['title' => $title, 'text' => $title . $message], 'isAtAll' => false];
        $dingPostUrl = \Hyperf\Support\env('DINGDING_ACCESS_TOKEN', 'https://oapi.dingtalk.com/robot/send?access_token=' . DingDingMessage::DINGDING_TOKEN);
        try {
            $res = RequestLibrary::requestPostResultJsonData($dingPostUrl, $data);
            if ($res['errcode'] != 0) {
                throw new \ErrorException($res['errmsg']);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}