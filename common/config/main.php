<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'name' => 'Система поддержки принятия врачебных решений',
	'language' => 'ru-RU',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        	'cachePath' => '@frontend/runtime/cache'
        ],

    	// смс
    	'smsApi' => [
    		'class' => 'common\components\sms\SmsApiComponent',
    		// sms.ru
    		'smsRuApiId' => '',
    		'smsApiUrl' => 'http://sms.ru/sms/send?',
    	],

    	// push-уведомления
    	'push' => [
    		'class' => 'common\components\pushNotification\PushComponent',
    		'commonTopic' => 'commonTopic'
    	],

        // веб-сокеты
        'websocket' => [
            'class' => 'common\components\webSocket\WebSocketComponent',
            'servers' => [
                // сокет персональный
                'personal-socket-server' => [
                    'class' => 'common\components\webSocket\PersonalSocketServerDaemonHandler',
                ],
            ],
        ],

        // ratchet-сокеты
        'ratchet' => [
            'class' => 'common\components\ratchet\RatchetSocketComponent',
            'port' => 8080
        ],

        // конвертация видео файлов
        'videoConverter' => [
            'class' => 'common\components\converters\VideoConverterComponent',
            'pathToFFMPEG' => '/usr/bin/ffmpeg',
            'pathToFFPROBE' => '/usr/bin/ffprobe',
            'videoMaxSize' => 854,
        ],

        // аудио компонент
        'audio' => [
            'class' => 'common\components\media\AudioComponent',
            'pathToFFMPEG' => '/usr/bin/ffmpeg',
            'pathToFFPROBE' => '/usr/bin/ffprobe',
        ],

    	// формат времени
    	'formatter' => [
    		'class' => 'common\components\formatters\CustomFormatter',
    		'defaultTimeZone' => 'Europe/Moscow',
    		'timeZone' => '+3',
    	],
    	
    	// формат валюты
    	'currencyFormater' => [
    		'class' => 'common\components\formatters\CurrencyFormater',
    		'thousandSeparator' => ' ',
    		'decimalSeparator' => '.',
    		'currencyCode' => '₽',
    		'postfix' => true,
    	],

        'assetManager' => [
            'appendTimestamp' => true
        ],

        'frontendUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],

        'yandexMapsApi' => [
            'class' => 'mirocow\yandexmaps\Api',
            'apikey' => ''
        ]

    ],

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],

	
	'controllerMap' => [
		'websocket' => 'morozovsk\yii2websocket\console\controllers\WebsocketController'
	],
];
