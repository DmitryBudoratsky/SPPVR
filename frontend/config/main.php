<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\db\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true
        ],
        'formatter' => [
            'dateFormat' => 'dd-MM-Y',
            'datetimeFormat' => 'dd-MM-Y H:i',
            'timeFormat' => 'H:i:s',

            'locale' => 'ru-RU',
            'defaultTimeZone' => 'Europe/Moscow',
        ],
        
    ],
	
	'modules' => [
		'gii' => [
			'class' => 'yii\gii\Module',
		],
		'debug' => [
			'class' => 'common\components\PrivateDebug',
			'allowedIPs' => ['*', '127.0.0.1', '::1'],
            'historySize' => 500,
		],
		'api' => [
			'class' => 'frontend\modules\api\Api',
		],
        'externalApi' => [
            'class' => 'frontend\modules\externalApi\ExternalApi',
        ],
	],
	
    'params' => $params,
];
