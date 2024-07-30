<?php

namespace Ziyanco\Library\Sms;


use Ziyanco\Library\Extends\RequestLibrary;

class DingDingMessage
{
    const DINGDING_TOKEN = '';

    /**
     * 机器人发送消息勾子
     * @param $data
     * @return void
     */
    public static function send($message, $title = '异常通知:')
    {
        $data = ['msgtype' => 'markdown', 'markdown' => ['title' => $title, 'text' => $title . $message], 'isAtAll' => false];
        $dingPostUrl = 'https://oapi.dingtalk.com/robot/send?access_token=' . \Hyperf\Support\env('DINGDING_ACCESS_TOKEN', DingDingMessage::DINGDING_TOKEN);
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