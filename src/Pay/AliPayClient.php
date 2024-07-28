<?php

namespace Ziyanco\Library\Pay;
use App\Lib\Alipay\V2\Aop\AlipayConfig;
use App\Lib\Alipay\V2\Aop\AopClient;
use App\Lib\Alipay\V2\Aop\Request\AlipayTradeWapPayRequest;

/**
 * 支付宝支付
 */
class AliPayClient
{
    /**
     * @param $params
     * @return void
     */
    public function checkSign($setting,$params){
        //签名验证
        $aop = new AopClient ();
        $aop->alipayrsaPublicKey = $setting['alipay_public_cert_path'];
        return $aop->rsaCheckV1($params, NULL, "RSA2");
    }
    public static function pay($object,$setting,$type='h5'){

    }

    public static function sign($object,$setting){

    }
    public function getClient($config)
    {
        $appId = $config['app_id'];
        $privateKey = $config['app_secret_cert'];
        $alipayPublicKey = $config['alipay_public_cert_path'];
        $aop = new AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $appId;
        $aop->rsaPrivateKey = $privateKey;
        $aop->alipayrsaPublicKey = $alipayPublicKey;
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'UTF-8';
        $aop->format = 'json';
        return $aop;
    }

    /**
     * 阿里H5支付
     * @return void
     */
    public function alipayH5($settings, $object)
    {
        $object->product_code = 'QUICK_WAP_WAY';
        $appId = (String)trim($settings['app_id']);
        $privateKey = $settings['app_secret_cert'];
        $alipayPublicKey = $settings['alipay_public_cert_path'];
        $alipayConfig = new AlipayConfig();
        $alipayConfig->setServerUrl("https://openapi.alipay.com/gateway.do");
        $alipayConfig->setAppId($appId);
        $alipayConfig->setPrivateKey($privateKey);
        $alipayConfig->setFormat("json");
        $alipayConfig->setAlipayPublicKey($alipayPublicKey);
        $alipayConfig->setCharset("UTF-8");
        $alipayConfig->setSignType("RSA2");
        $alipayClient = new AopClient($alipayConfig);
        $request = new AlipayTradeWapPayRequest();
        $json = json_encode($object);
        $request->setBizContent($json);
        $request->setReturnUrl($settings['return_url']);
        $request->setNotifyUrl($settings['notify_url']);
        $pageRedirectionData = $alipayClient->pageExecute($request,"GET");
        return ['type'=>'url','url'=>$pageRedirectionData];
    }

    /**
     * 阿里Web支付
     * @return void
     */
    public function alipayWeb($settings, $object)
    {


//        $privateKey = "MIIEowIBAAKCAQEApesCbMZq8AB9a184CItPDHM43MCvSCujVY/qOz5pbvkzSKeuBXuJv/04XmdaqsfLh+jfKojKPdht7EYptYMiPGuMxvzH6I9/8rz0cAt3860pFYRLqYn8ABmhrF56Limxy84q3JQcDcddAuCb38o41QtodH5KBVjIdBQeflw+67Es92M23M8q/F12dWatXi+u4kYql63vEX6425xIF4nvp2uYNUX0Wwe4QG5r4jcCiAyXgS6AYK5e3vdbhg3mhD4mLCYHe9SOBj03tHF/964r1NCUIfRWo2nRaGt6DQpaE/SC4XG7DZwwLdExe4hUf3tuDPR9FecepMyoFMAhgupkYwIDAQABAoIBABCRNtzFs0fjxKJED8RKpMoJd1QTqWz7boPMqwbfZIfAOm62z5mcjFSvZEGI8HS9HoWjqyUEI62pPtCo8OE/BtwhoRz9gLioG46Rb5cAjim20LNlUmjLbm5UIOClTm3tm+NN4tnDsElv7smqw+XEu4gKa5O2SI3gH6P3nlPfwiimjtt26uhuxEeacz5I7LFX0LwTalwiBlNYj8e11ORsn9sjjG/WB07vG1P2IK2B3YyQGvgb5zo2Rtx6+3G5/emdXlwZHHNQTmab667xpe5Zg7Lkc9HJmnypBovmbJ77srN5p0rd/BmrXCjsY05xvdCIo9RKqp3Sjn0dpCw8IAAA5wECgYEA7FLFBSX2cXySZf5J1Ndd6Fi4Lxip/mPWlnZfDOa8KS0gHkK98pgUj1VaedWP63wcflVfN+Mdc2EBAuD3gLvQjJC9oDF7VV5LJEmvhRu4CUfGFbQiRQ/FUVXzpn1MjIEc8lGEf5+qgjFPDQ5zzfkqZsfI3Z63N32jHNK/0uojdJsCgYEAs7uNC48JDH83jK5X4ONHXdtjKxVgesu+i4ny4UiHgVK6l9vm3ePfWt8g6IS6r9Ty7unR0BNCAytE5ttUYKJuijb4B6o9nSh7D9zHbCZ3np9/51bDMUDiVzHh5iCg8ei8OqJ/bJPVSQvSaDou9dlPmxtK9iLMhsZL60M9y05B99kCgYEAnJ72aEUSoX/33pS7Bc5+NECoQL5N24T7cNieggRha8C2ape9H4xfA+eDgP9i1KR6ldeqtXVVzWPasbe7EJxoyOAcbJCetqM5laEoLEWEoNHqm/O4SqD7Gr6mLg8Yrn3bW1VfRN3iuUpIo9SSN04NJfT+ULKhpqsjcTTTYMtAGEUCgYABAEFn2BMX07mOegYZRYrHNgqWWdcgt/PGuSz2Hj5K9Rf+8oWVpMhArE91nA+iHRCBTiISA5lxhRMsKfqNUzpEYMv+1u7i6i/NRdCChLBT3NWMh0otAwx/qdJ1QqdC0aYCuZb2FtiwQHWaiQBr9BriZUOEnWsAMSiJFeXJNRVe+QKBgGTfUE6tNgPtYwG4Q2Z2sLFZ56SOXOhcX9PQpF7n74ESbtmJoitgfn9YDnssm3R6wDxYcgpul4ymii9AyLdQyiHaK/2kQqP+cHtfceHuOeFe9ZAPI5BwZ4NAM7Mjr0SdmwQYUcmwz6A2fKpyCSCyUiEPJpEvbwrAFx9ktdLIOYlV";
//        $alipayPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuzjwPMYttT87upBKnEtbT68T6ztQ2jlNdJY6ppIvBaMkbpKWi0+A91zkRr+i1KP8KXDjTYJ+FupA1jVpHVIYlQweXce4b5MAr33KrcpaPqJDHUztU1pPfqPWUhNS9T8Pu+/KefhWxafhiLjTRMuBYIkzaKvfeehxyDpUyf1d5Q2Pt4i98mfazm8MaKHqyw4Hy6/QlJopPCwoNfzV6EldF7H5S3Mn8MO+VJBKqmkVxOIUqDu+p97dRolhqrGB6vJl0vsh0g9w0UD6+QGBolAo+dQ7a/vlkYZOeB2l/3fU/hUtva5Xy7zmAfM4NEJu6QNZtagG9YwkSXl7l+3Mv0s88wIDAQAB";
//        $alipayConfig = new AlipayConfig();
//        $alipayConfig->setServerUrl("https://openapi-sandbox.dl.alipaydev.com/gateway.do");
//        $alipayConfig->setAppId("9021000134604348");
//        $alipayConfig->setPrivateKey($privateKey);
//        $alipayConfig->setFormat("json");
//        $alipayConfig->setAlipayPublicKey($alipayPublicKey);
//        $alipayConfig->setCharset("UTF-8");
//        $alipayConfig->setSignType("RSA2");
//        $alipayClient = new AopClient($alipayConfig);
//        $request = new AlipayTradeWapPayRequest();
//        $request->setBizContent("{".
//            "\"out_trade_no\":\"70501111111S001111119\",".
//            "\"total_amount\":\"9.00\",".
//            "\"subject\":\"大乐透\",".
//            "\"product_code\":\"QUICK_WAP_WAY\",".
//            "\"seller_id\":\"2088102147948060\"".
//            "\"time_expire\":\"2024-02-20 16:00:00\"".
//            "}");
//        $pageRedirectionData = $alipayClient->pageExecute($request,"GET");


        $object->product_code = 'FAST_INSTANT_TRADE_PAY';
        $appId = (String)trim($settings['app_id']);
        $privateKey = $settings['app_secret_cert'];
        $alipayPublicKey = $settings['alipay_public_cert_path'];
        $alipayConfig = new AlipayConfig();
        $alipayConfig->setServerUrl("https://openapi.alipay.com/gateway.do");
        $alipayConfig->setAppId($appId);
        $alipayConfig->setPrivateKey($privateKey);
        $alipayConfig->setFormat("json");
        $alipayConfig->setAlipayPublicKey($alipayPublicKey);
        $alipayConfig->setCharset("UTF-8");
        $alipayConfig->setSignType("RSA2");
        $alipayClient = new AopClient($alipayConfig);
        $request = new AlipayTradeWapPayRequest();
        $json = json_encode($object);
        echo $json;
        $request->setBizContent($json);
        $request->setReturnUrl($settings['return_url']);
        $request->setNotifyUrl($settings['notify_url']);
        $pageRedirectionData = $alipayClient->pageExecute($request,"GET");
        return ['type'=>'url','url'=>$pageRedirectionData];
    }

    /**
     * 阿里APP支付
     * @return void
     */
    public function alipayApp($settings, $object)
    {
        $object->product_code = 'QUICK_MSECURITY_PAY';
        $appId = (String)trim($settings['app_id']);
        $privateKey = $settings['app_secret_cert'];
        $alipayPublicKey = $settings['alipay_public_cert_path'];
        $alipayConfig = new AlipayConfig();
        $alipayConfig->setServerUrl("https://openapi.alipay.com/gateway.do");
        $alipayConfig->setAppId($appId);
        $alipayConfig->setPrivateKey($privateKey);
        $alipayConfig->setFormat("json");
        $alipayConfig->setAlipayPublicKey($alipayPublicKey);
        $alipayConfig->setCharset("UTF-8");
        $alipayConfig->setSignType("RSA2");
        $alipayClient = new AopClient($alipayConfig);
        $request = new AlipayTradeWapPayRequest();
        $json = json_encode($object);
        $request->setBizContent($json);
        $request->setReturnUrl($settings['return_url']);
        $request->setNotifyUrl($settings['notify_url']);
        $pageRedirectionData = $alipayClient->sdkExecute($request);
//        $aop = $this->getClient($settings);
//        $json = json_encode($object);
//        $request = new AlipayTradePagePayRequest();
//        $request->setBizContent($json);
//        $request->setReturnUrl($settings['return_url']);
//        $request->setNotifyUrl($settings['notify_url']);
//        $pageRedirectionData = $aop->sdkExecute($request);
        return $pageRedirectionData;
    }


}