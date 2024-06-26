<?php

namespace Ziyan\Ziyanco\Extends;

class DingDingMessage
{
    /**
     * 机器人发送消息勾子
     * @param $data
     * @return void
     */
    public static function dingDingSendMessage($message, $title = '异常通知:')
    {
        $data = ['msgtype' => 'markdown', 'markdown' => ['title' => $title, 'text' => $title . $message], 'isAtAll' => false];
        $dingPostUrl = \Hyperf\Support\env('DINGDING_HOOK_URL','http://');
        try {
            $res =RequestLibrary::requestPostResultJsonData($dingPostUrl, $data);
            if ($res['errcode'] != 0) {
                throw new \ErrorException($res['errmsg']);
            }
        } catch (\Exception $e) {
            writeHyperfLog("钉钉发送异常:" . $e->getMessage());
        }
    }
}