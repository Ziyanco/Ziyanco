<?php

namespace Ziyanco\Library\Pay;
use WeChatPay\Builder;
use WeChatPay\Crypto\Rsa;
use WeChatPay\Util\PemUtil;
class WxPayClient
{
    public function getInstance($setting)
    {
        $merchantId = $setting['mch_id'];
// 从本地文件中加载「商户API私钥」，「商户API私钥」会用来生成请求的签名
        $merchantPrivateKeyFilePath = $this->getStringToPrivateKey($setting['private_key']);  //私钥
        $merchantPrivateKeyInstance = Rsa::from($merchantPrivateKeyFilePath, Rsa::KEY_TYPE_PRIVATE);

// 「商户API证书」的「证书序列号」
        $merchantCertificateSerial = $setting['serial_number'];

// 从本地文件中加载「微信支付平台证书」，用来验证微信支付应答的签名
        $platformCertificateFilePath = $this->getStringToPublicKey($setting['apiclient_cert']); //公钥
        $platformPublicKeyInstance = Rsa::from($platformCertificateFilePath, Rsa::KEY_TYPE_PUBLIC);

        // 从「微信支付平台证书」中获取「证书序列号」
        $platformCertificateSerial = PemUtil::parseCertificateSerialNo($platformCertificateFilePath);

// 构造一个 APIv3 客户端实例
        $instance = Builder::factory([
            'mchid' => $merchantId,
            'serial' => $merchantCertificateSerial,
            'privateKey' => $merchantPrivateKeyInstance,
            'certs' => [
                $platformCertificateSerial => $platformPublicKeyInstance,
            ],
        ]);
        return $instance;
    }

    /**
     * 微信h5
     * @param $setting
     * @param $object
     * @return array
     */
    public function h5Pay($setting, $object)
    {
        $instance = $this->getInstance($setting);
        $mchid = $setting['mch_id'];
        $appid = $setting['app_id'];
        $notifyUrl = $setting['notify_url'];
        $object->appid = $appid;
        $object->mchid = $mchid;
        $object->notify_url = $notifyUrl;
        $object->time_expire = date('Y-m-d\TH:i:sP', $object->time_expire);
        $resp = $instance
            ->chain('v3/pay/transactions/h5')
            ->post(['json' => $object]);
        $result = json_decode($resp->getBody(), true);
        return ['type' => 'url', 'url' => !empty($result['h5_url']) ? $result['h5_url'] : ''];

    }


    /**
     * 微信app支付
     * @param $setting
     * @param $object
     * @return mixed
     */
    public function appPay($setting, $object)
    {
        $instance = $this->getInstance($setting);
        $mchid = $setting['mch_id'];
        $appid = $setting['app_id'];
        $notifyUrl = $setting['notify_url'];
        $object->appid = $appid;
        $object->mchid = $mchid;
        $object->notify_url = $notifyUrl;
        $object->time_expire = date('Y-m-d\TH:i:sP', $object->time_expire);
        $resp = $instance
            ->chain('v3/pay/transactions/app')
            ->post(['json' => $object]);
        $result = json_decode($resp->getBody(), true);

        //这边二次生成
        $values=$this->getOrder($result['prepay_id'],$setting);
        return $values;
    }
    public function getOrder($prepayId,$setting) {
        $values=array();
        $values["appid"] = $setting['app_id'];
        $values["partnerid"] = $setting['mch_id'];
        $values["prepayid"] = $prepayId;
        $values["package"] ='Sign=WXPay';
        $values["noncestr"] = $this->getNonceStr();
        $values["timestamp"] =  "".time()."";
        $message=$this->getBuilMessage($values);
        $merchantPrivateKeyFilePath = $this->getStringToPrivateKey($setting['private_key']);  //私钥
        $values["sign"] =  Rsa::sign($message,$merchantPrivateKeyFilePath);
        return $values;
    }

    public function getBuilMessage($values){
        return $values['appid'] . "\n" .
            $values['timestamp'] . "\n" .
            $values['noncestr']. "\n" .
            $values['prepayid'] . "\n";
    }


    /**
     * 产生的随机字符串
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return
     */
    public static function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }
    /*

    /**
     * 将字符串转成私钥
     * @param $string
     * @return string
     */
    public function getStringToPrivateKey($string)
    {
        $pkString = "-----BEGIN PRIVATE KEY-----\n" . chunk_split($string, 64, "\n") . "-----END PRIVATE KEY-----\n";
        openssl_pkey_get_private($pkString);
        return $pkString;
    }

    /**
     * 将字符串转成公钥
     * @param $string
     * @return string
     */
    public function getStringToPublicKey($string)
    {
        $publicKeyString = "-----BEGIN CERTIFICATE-----\n" .
            chunk_split($string, 64, "\n") .
            "-----END CERTIFICATE-----\n";
        openssl_pkey_get_public($publicKeyString);
        return $publicKeyString;
    }
    public static function pay($object,$setting,$type='h5'){

    }

    public static function sign($object,$setting,$type='h5'){

    }
}