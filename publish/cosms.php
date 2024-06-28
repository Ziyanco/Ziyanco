<?php
use function Hyperf\Support\env;
return [
    'default' => 'ali',
    'sms' => [
        'ali' => [    //阿里发送短信
            'driver' => \Ziyanco\Library\Sms\AliyunSms::class,
            'aliAppcode' => env('ALI_APP_CODE',''),  //阿里code
            'aliTemplateId' => env('ALI_TEMPLATE_ID',''),  //短信模板
            'aliDigit' => env('ALI_DIGIT',''),           //验证码位数
            'aliUseTime' => env('ALI_REDIS_USE_TIME',''),  // 有效时间
        ]
    ],
];
