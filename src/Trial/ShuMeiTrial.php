<?php

namespace Ziyanco\Library\Trial;

class ShuMeiTrial
{
    const  POST_CONTENT_URL='http://api-text-bj.fengkongcloud.com/text/v4';
    const  POST_IMG_URL='http://api-text-bj.fengkongcloud.com/text/v4';
    const  POST_VIDEO_URL='http://api-video-bj.fengkongcloud.com/video/v4';

    /**
     * 素美内容检测
     * @param $params
     * @return void
     */
    public static function smContentPostDataLogic($params)
    {
        if (empty($params['content'])) {
            return [];
        }
        $postData = [];
        $postData['accessKey'] = env('SM_ACCESS_KEY', 'ilPfIpaaoAOdF4oV89qA');
        $postData['appId'] = env('SM_APP_ID', 'default');
        $postData['eventId'] = 'article';
        $postData['type'] = 'TEXTRISK';
        $postData['data'] = ['text' => $params['content'], 'tokenId' => (string)$params['room_no']];
        $res=\Ziyanco\Library\Extends\RequestLibrary::requestPostResultJsonData(
             static::POST_CONTENT_URL,
             $postData
         );
        if ($res['code'] != 1100) {
            throw new ZyException(ZyCode::SERVER_ERROR, $res['message']);
        }
        if ($res['riskLevel'] != 'PASS') {
            throw new ZyException(ZyCode::SM_CONTEXT_ERROR);
        }
        return $res;
    }

    /**
     * 素美图片检测
     * @param $params
     * @return void
     */
    public static function smImagesPostDataLogic($params)
    {
        if (empty($params['photos'])) {
            return [];
        }
        $photos = explode(',', $params['photos']);
        $postData = [];
        $postData['accessKey'] = env('SM_ACCESS_KEY', 'ilPfIpaaoAOdF4oV89qA');
        $postData['appId'] = env('SM_APP_ID', 'default');
        $postData['eventId'] = "IMAGE";
        $postData['type'] = "POLITY_EROTIC_VIOLENT";
        $postData['data']['tokenId'] = (string)$params['room_no'];
        $postData['data']['imgs'] = [];
        foreach ($photos as $key => $val) {
            $lineData = [];
            $lineData['btId'] = (string)(time() + $key);
            $lineData['img'] = $val;
            $postData['data']['imgs'][] = $lineData;
        }
        $res=\Ziyanco\Library\Extends\RequestLibrary::requestPostResultJsonData(
            static::POST_IMG_URL,
            $postData
        );
        if ($res['code'] != 1100) {
            throw new ZyException(ZyCode::SERVER_ERROR, $res['message']);
        }
        if ($res['riskLevel'] != 'PASS') {
            throw new ZyException(ZyCode::SM_CONTEXT_ERROR);
        }
        return $res;
    }

    /**
     * 视频流验证
     * @param $params
     * @return void
     */
    public function smVideoStreamPostDataLogic($params)
    {
        $postData = [];
        $postData['accessKey'] = env('SM_ACCESS_KEY', 'ilPfIpaaoAOdF4oV89qA');
        $postData['appId'] = env('SM_APP_ID', 'default');
        $postData['eventId'] = 'video';
        $postData['imgType'] = "POLITY_EROTIC_ADVERT";
        $postData["imgBusinessType"] = "BODY_FOOD_3CPRODUCTSLOGO";
        $postData["audioType"] = "POLITY_EROTIC_ADVERT_MOAN";
        $postData["audioBusinessType"] = "SING_LANGUAGE";
        $postData["callback"] = env('SM_CALL_BACK') . '/sm/callback';
        $postData['data'] = [
            'tokenId' => (string)$params['room_no'],
            'url' => $params['url'],
            'btId' => (string)time(),
        ];
        $res=\Ziyanco\Library\Extends\RequestLibrary::requestPostResultJsonData(
            static::POST_VIDEO_URL,
            $postData
        );
        if ($res['code'] != 1100) {
            throw new ZyException(ZyCode::SERVER_ERROR, $res['message']);
        }
        if ($res['riskLevel'] != 'PASS') {
            throw new ZyException(ZyCode::SM_CONTEXT_ERROR);
        }
        return $res;
    }
}