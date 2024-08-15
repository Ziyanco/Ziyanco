<?php
use function Hyperf\Support\env;
return [
    'default' => 'ali',
    'sms' => [
        'ali' => [    //阿里发送短信
            'driver' => \Ziyanco\Library\Sms\AliyunSms::class,
            'aliAppcode' => env('ALI_APP_CODE', 'ea98b3fe94ce432a83c95ad58964a942'),  //阿里code
            'aliTemplateId' => env('ALI_TEMPLATE_ID', 'CST_sxyzgjamunad10805'),  //短信模板
            'aliDigit' => env('ALI_DIGIT', 6),           //验证码位数
            'aliUseTime' => env('ALI_REDIS_USE_TIME', 600),  // 有效时间
        ],
        'hw' => [    //华为云送短信
            'driver' => \Ziyanco\Library\Sms\HuaweiSms::class,
            'HwyAppKey' => env('HWY_APP_KEY', '2awf1wTH1N19LX2sgySSrWt6HP3h11'),  //华为key
            'HwyAppSecret' => env('HWY_APP_SECRET', 'kZr1N0oWFDTM7S02KeL6w4l0JMPJ11'),  //华为secret
            'HwyAppForm' => env('HWY_APP_FROM', '882407051083011'),  //短信模板
            'HwyTemplateId' => env('HWY_TEMPLATE_ID', '00bddf24fcb14eb7ab76a985500facfc11'),  //短信模板
            'HwyDigit' => env('HWY_DIGIT', 6),           //验证码位数
            'HwyUseTime' => env('HWY_REDIS_USE_TIME', 60),  // 有效时间
        ]
    ],
];
