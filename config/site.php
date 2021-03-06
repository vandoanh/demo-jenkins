<?php

return [
    'general' => [
        'site_name' => 'EAS HCM - Blog',
        'default_password' => 'Aa123456@',
        'seo' => [
            'title' => 'EAS HCM - Blog',
            'description' => 'EAS HCM - Blog',
            'keywords' => 'eas, eas hcm, blog',
        ],
        'pagination' => [
            'list' => [10, 20, 30, 50, 100, 200, 300, 500],
            'each_side' => 1,
            'default' => 20,
        ],
        'encrypt' => [
            'method' => 'AES-256-CBC',
            'secret_key' => env('ENCRYPT_SECRET_KEY'),
            'secret_iv' => env('ENCRYPT_SECRET_IV'),
        ],
        'timezone' => [
            'Pacific/Midway' => '(GMT-11:00) Midway Island',
            'US/Samoa' => '(GMT-11:00) Samoa',
            'US/Hawaii' => '(GMT-10:00) Hawaii',
            'US/Alaska' => '(GMT-09:00) Alaska',
            'US/Pacific' => '(GMT-08:00) Pacific Time (US &amp; Canada)',
            'America/Tijuana' => '(GMT-08:00) Tijuana',
            'US/Arizona' => '(GMT-07:00) Arizona',
            'US/Mountain' => '(GMT-07:00) Mountain Time (US &amp; Canada)',
            'America/Chihuahua' => '(GMT-07:00) Chihuahua',
            'America/Mazatlan' => '(GMT-07:00) Mazatlan',
            'America/Mexico_City' => '(GMT-06:00) Mexico City',
            'America/Monterrey' => '(GMT-06:00) Monterrey',
            'Canada/Saskatchewan' => '(GMT-06:00) Saskatchewan',
            'US/Central' => '(GMT-06:00) Central Time (US &amp; Canada)',
            'US/Eastern' => '(GMT-05:00) Eastern Time (US &amp; Canada)',
            'US/East-Indiana' => '(GMT-05:00) Indiana (East)',
            'America/Bogota' => '(GMT-05:00) Bogota',
            'America/Lima' => '(GMT-05:00) Lima',
            'America/Caracas' => '(GMT-04:30) Caracas',
            'Canada/Atlantic' => '(GMT-04:00) Atlantic Time (Canada)',
            'America/La_Paz' => '(GMT-04:00) La Paz',
            'America/Santiago' => '(GMT-04:00) Santiago',
            'Canada/Newfoundland' => '(GMT-03:30) Newfoundland',
            'America/Buenos_Aires' => '(GMT-03:00) Buenos Aires',
            'Greenland' => '(GMT-03:00) Greenland',
            'Atlantic/Stanley' => '(GMT-02:00) Stanley',
            'Atlantic/Azores' => '(GMT-01:00) Azores',
            'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is.',
            'Africa/Casablanca' => '(GMT+00:00) Casablanca',
            'Europe/Dublin' => '(GMT+00:00) Dublin',
            'Europe/Edinburgh' => '(GMT+00:00) Edinburgh',
            'Europe/Lisbon' => '(GMT+00:00) Lisbon',
            'Europe/London' => '(GMT+00:00) London',
            'Africa/Monrovia' => '(GMT+00:00) Monrovia',
            'UTC' => '(GMT+00:00) UTC',
            'Europe/Amsterdam' => '(GMT+01:00) Amsterdam',
            'Europe/Belgrade' => '(GMT+01:00) Belgrade',
            'Europe/Berlin' => '(GMT+01:00) Berlin',
            'Europe/Bratislava' => '(GMT+01:00) Bratislava',
            'Europe/Brussels' => '(GMT+01:00) Brussels',
            'Europe/Budapest' => '(GMT+01:00) Budapest',
            'Europe/Copenhagen' => '(GMT+01:00) Copenhagen',
            'Europe/Ljubljana' => '(GMT+01:00) Ljubljana',
            'Europe/Madrid' => '(GMT+01:00) Madrid',
            'Europe/Paris' => '(GMT+01:00) Paris',
            'Europe/Prague' => '(GMT+01:00) Prague',
            'Europe/Rome' => '(GMT+01:00) Rome',
            'Europe/Sarajevo' => '(GMT+01:00) Sarajevo',
            'Europe/Skopje' => '(GMT+01:00) Skopje',
            'Europe/Stockholm' => '(GMT+01:00) Stockholm',
            'Europe/Vienna' => '(GMT+01:00) Vienna',
            'Europe/Warsaw' => '(GMT+01:00) Warsaw',
            'Europe/Zagreb' => '(GMT+01:00) Zagreb',
            'Europe/Athens' => '(GMT+02:00) Athens',
            'Europe/Bucharest' => '(GMT+02:00) Bucharest',
            'Africa/Cairo' => '(GMT+02:00) Cairo',
            'Africa/Harare' => '(GMT+02:00) Harare',
            'Europe/Helsinki' => '(GMT+02:00) Helsinki',
            'Europe/Istanbul' => '(GMT+02:00) Istanbul',
            'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
            'Europe/Kiev' => '(GMT+02:00) Kyiv',
            'Europe/Minsk' => '(GMT+02:00) Minsk',
            'Europe/Riga' => '(GMT+02:00) Riga',
            'Europe/Sofia' => '(GMT+02:00) Sofia',
            'Europe/Tallinn' => '(GMT+02:00) Tallinn',
            'Europe/Vilnius' => '(GMT+02:00) Vilnius',
            'Asia/Baghdad' => '(GMT+03:00) Baghdad',
            'Asia/Kuwait' => '(GMT+03:00) Kuwait',
            'Africa/Nairobi' => '(GMT+03:00) Nairobi',
            'Asia/Riyadh' => '(GMT+03:00) Riyadh',
            'Europe/Moscow' => '(GMT+03:00) Moscow',
            'Asia/Tehran' => '(GMT+03:30) Tehran',
            'Asia/Baku' => '(GMT+04:00) Baku',
            'Europe/Volgograd' => '(GMT+04:00) Volgograd',
            'Asia/Muscat' => '(GMT+04:00) Muscat',
            'Asia/Tbilisi' => '(GMT+04:00) Tbilisi',
            'Asia/Yerevan' => '(GMT+04:00) Yerevan',
            'Asia/Kabul' => '(GMT+04:30) Kabul',
            'Asia/Karachi' => '(GMT+05:00) Karachi',
            'Asia/Tashkent' => '(GMT+05:00) Tashkent',
            'Asia/Kolkata' => '(GMT+05:30) Kolkata',
            'Asia/Kathmandu' => '(GMT+05:45) Kathmandu',
            'Asia/Yekaterinburg' => '(GMT+06:00) Ekaterinburg',
            'Asia/Almaty' => '(GMT+06:00) Almaty',
            'Asia/Dhaka' => '(GMT+06:00) Dhaka',
            'Asia/Novosibirsk' => '(GMT+07:00) Novosibirsk',
            'Asia/Bangkok' => '(GMT+07:00) Bangkok',
            'Asia/Ho_Chi_Minh' => '(GMT+07:00) Hanoi',
            'Asia/Jakarta' => '(GMT+07:00) Jakarta',
            'Asia/Krasnoyarsk' => '(GMT+08:00) Krasnoyarsk',
            'Asia/Chongqing' => '(GMT+08:00) Chongqing',
            'Asia/Hong_Kong' => '(GMT+08:00) Hong Kong',
            'Asia/Kuala_Lumpur' => '(GMT+08:00) Kuala Lumpur',
            'Australia/Perth' => '(GMT+08:00) Perth',
            'Asia/Singapore' => '(GMT+08:00) Singapore',
            'Asia/Taipei' => '(GMT+08:00) Taipei',
            'Asia/Ulaanbaatar' => '(GMT+08:00) Ulaan Bataar',
            'Asia/Urumqi' => '(GMT+08:00) Urumqi',
            'Asia/Irkutsk' => '(GMT+09:00) Irkutsk',
            'Asia/Seoul' => '(GMT+09:00) Seoul',
            'Asia/Tokyo' => '(GMT+09:00) Tokyo',
            'Australia/Adelaide' => '(GMT+09:30) Adelaide',
            'Australia/Darwin' => '(GMT+09:30) Darwin',
            'Asia/Yakutsk' => '(GMT+10:00) Yakutsk',
            'Australia/Brisbane' => '(GMT+10:00) Brisbane',
            'Australia/Canberra' => '(GMT+10:00) Canberra',
            'Pacific/Guam' => '(GMT+10:00) Guam',
            'Australia/Hobart' => '(GMT+10:00) Hobart',
            'Australia/Melbourne' => '(GMT+10:00) Melbourne',
            'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
            'Australia/Sydney' => '(GMT+10:00) Sydney',
            'Asia/Vladivostok' => '(GMT+11:00) Vladivostok',
            'Asia/Magadan' => '(GMT+12:00) Magadan',
            'Pacific/Auckland' => '(GMT+12:00) Auckland',
            'Pacific/Fiji' => '(GMT+12:00) Fiji',
        ],
        'social_login' => [
            'enable' => env('ENABLE_LOGIN_FACEBOOK', false) || env('ENABLE_LOGIN_GOOGLE', false),
            'facebook' => [
                'enable' => env('ENABLE_LOGIN_FACEBOOK', false),
            ],
            'google' => [
                'enable' => env('ENABLE_LOGIN_GOOGLE', false),
            ],
        ],
    ],
    'cache' => [
        'enable' => env('CACHE_ENABLE', false),
        'storage' => env('CACHE_DRIVER', 'file'),
        'lifetime' => env('CACHE_LIFETIME', 1440),
        'keys' => [
            'user_is_online' => 'user_is_online_%s_%s'
        ],
        'image' => [
            'enable' => env('CACHE_IMAGE_ENABLE', false),
            'storage' => env('CACHE_IMAGE_DRIVER', 'file'),
            'key' => 'imagecache_%s_%s',
            'sizes' => [
                '90x67',
                '40x40',
                '225x225',
                '268x200',
                '348x261',
                '545x410',
            ],
            'paths' => [
                'image',
                'avatar',
                'tmp',
            ],
            'lifetime' => env('CACHE_LIFETIME', 1440),
        ],
    ],
    'media' => [
        'storage' => env('MEDIA_STORAGE', 'local'),
        'path' => env('MEDIA_PATH', ''),
        'url' => [
            'image' => env('MEDIA_URL') . '/images',
            'file' => env('MEDIA_URL') . '/file',
            'upload' => [
                'form' => env('MEDIA_URL') . '/upload/form',
                'url' => env('MEDIA_URL') . '/upload/url',
                'save' => env('MEDIA_URL') . '/upload/save',
            ],
        ],
        'type' => [
            'image' => [
                'jpg',
                'jpeg',
                'png',
            ],
            'file' => [
                'doc',
                'docx',
                'xls',
                'xlsx',
                'pdf'
            ],
        ],
        'size' => [
            'image' => 5242880,
            'file' => 5242880,
        ],
    ],
    'captcha' => [
        'enable' => env('CAPTCHA_ENABLE', false),
        'site_key' => env('CAPTCHA_SITE_KEY', ''),
        'secret_key' => env('CAPTCHA_SECRET_KEY', ''),
        'verify_link' => 'https://www.google.com/recaptcha/api/siteverify',
    ],
    'notification' => [
        'vapid' => [
            'subject' => env('APP_URL'),
            'public_key' => 'BCFqHfLk1cHYa2NOqfJk92E6hnSYxo8O9HmqAf5/jh6hBZsK1gDI0oXAgOM2/QgKXdL3ry0v8pUaJTrxK5nLsow=',
            'private_key' => 'LSxRjLJho7qARF0tgBMHavOnMxl2KhvlQnOfVpuVCvM=',
        ],
    ],
    'api' => [
        'auth' => [
            'grant_client_id' => 2,
        ],
    ],
];
