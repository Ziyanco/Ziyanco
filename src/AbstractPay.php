<?php

namespace Ziyanco\Library;

use stdClass;

abstract class AbstractPay
{
    const  MULTIPLIER_MONEY = 100;

    public static function usSelectPay($record, $setting, $type)
    {
        switch ($type) {
            case 'alipay_h5':
                $class = di()->get(\Ziyanco\Library\Pay\AliPayClient::class);
                $object = new stdClass();
                $object->out_trade_no = $record->order_sn;
                $object->total_amount = (float)number_format((int)$record->fact_money / self::MULTIPLIER_MONEY, 2);
                $object->subject = 'H5钻石充值';
                $object->time_expire = date('Y-m-d H:i:s', $record->expire_time);
                $payRes = $class->alipayH5($setting, $object);
                break;
            case 'alipay_web':
                $class = di()->get(\Ziyanco\Library\Pay\AliPayClient::class);
                $object = new stdClass();
                $object->out_trade_no = $record->order_sn;
                $object->total_amount = (float)number_format($record->fact_money / self::MULTIPLIER_MONEY, 2);
                $object->subject = 'Web钻石充值';
                $object->time_expire = date('Y-m-d H:i:s', $record->expire_time);
                $payRes = $class->alipayWeb($setting, $object);
                break;
            case 'alipay_app':
                $class = di()->get(\Ziyanco\Library\Pay\AliPayClient::class);
                $object = new stdClass();
                $object->out_trade_no = $record->order_sn;
                $object->total_amount = number_format($record->fact_money / self::MULTIPLIER_MONEY, 2);
                $object->subject = 'App钻石充值';
                $object->time_expire = $record->expire_time;
                $payRes = $class->alipayApp($setting, $object);
                break;
            case 'ios_app':
                $payRes = $record;
                break;
            case 'wechat_app':  //微信app
                $class = di()->get(\Ziyanco\Library\Pay\WxPayClient::class);
                $object = new stdClass();
                $object->out_trade_no = $record->order_sn;
                $object->description = 'app充值';
                $object->time_expire = $record->expire_time;
                $object->amount = new stdClass();
                $object->amount->total = number_format($record->fact_money / self::MULTIPLIER_MONEY, 2) * 100;
                $object->amount->currency = 'CNY';
                $payRes = $class->appPay($setting, $object);
                break;
            case 'wechat_h5':  //微信h5
                $class = di()->get(\Ziyanco\Library\Pay\WxPayClient::class);
                $object = new stdClass();
                $object->out_trade_no = $record->order_sn;
                $object->description = 'h5充值';
                $object->time_expire = $record->expire_time;
                $object->amount = new stdClass();
                $object->amount->total = number_format($record->fact_money / self::MULTIPLIER_MONEY, 2) * 100;
                $object->amount->currency = 'CNY';
                $object->scene_info = new stdClass();
                $object->scene_info->payer_client_ip = !empty($record->ip) ? $record->ip : '117.30.48.109';
                $payRes = $class->h5Pay($setting, $object);
                break;
            case 'heepay_web':  //汇付web
                $class = di()->get(\Ziyanco\Library\Pay\HeepayClient::class);
                $payRes = $class->webPay($setting, $record);
                break;
            case 'fuiou_alipay':  //富友支付
                $class = di()->get(\Ziyanco\Library\Pay\FuiouPayClient::class);
                $payRes = $class->webPay($setting, $record, 'ALIPAY');
                break;
            case 'fuiou_weixin':  //富友支付
                $class = di()->get(\Ziyanco\Library\Pay\FuiouPayClient::class);
                $payRes = $class->webPay($setting, $record, 'WECHAT');
                break;
            //--合力宝
            case 'hlb_wap_alipay':  //合力宝支付宝-wap
                $class = di()->get(\Ziyanco\Library\Pay\FuiouPayClient::class);
                echo '===>合力宝支付宝-wap' . PHP_EOL;
                $payRes = $class->webPay($setting, $record, 'alipay.wap');
                break;
            case 'hlb_qrcode_alipay':  //合力宝支付宝-扫码
                $class = di()->get(\Ziyanco\Library\Pay\HlbPayClient::class);
                echo '===>合力宝支付宝-扫码' . PHP_EOL;
                $payRes = $class->webPay($setting, $record, 'alipay.qrcode');
                break;
            case 'hlb_qrcode_weixin':  //合力宝微信-扫码
                $class = di()->get(\Ziyanco\Library\Pay\HlbPayClient::class);
                echo '===>合力宝微信-扫码' . PHP_EOL;
                $payRes = $class->webPay($setting, $record, 'weixin.qrcode');
                break;
            case 'hlb_jspay_weixin':  //合力宝微信-jspay
                $class = di()->get(\Ziyanco\Library\Pay\HlbPayClient::class);
                $payRes = $class->webPay($setting, $record, 'weixin.jspay');
                break;
            case 'hlb_wap_weixin':  //合力宝微信-wap
                $class = di()->get(\Ziyanco\Library\Pay\HlbPayClient::class);
                $payRes = $class->webPay($setting, $record, 'weixin.wap');
                break;
            case 'wechat_web':  //微信web
                break;
        }
        return $payRes;
    }


    public static function checkParamsSign($object, $setting, $type)
    {
        switch ($type) {
            case 80001:  //支付宝回调
                $class = di()->get(\Ziyanco\Library\Pay\AliPayClient::class);
                $res = $class->checkSign($setting, $object);
                break;
            case 80002:  //微信回调
                $class = di()->get(\Ziyanco\Library\Pay\WxPayClient::class);
                $res = $class->decryptParamsNotice($setting, $object);
                break;
            case 80003:
                break;
            case 80004:
                break;
            case 80005: //IOS回调
                $class = di()->get(\Ziyanco\Library\Pay\IosClient::class);
                $res = $class->iosSignCheck($object);
                break;
        }
        return $res;
    }


}