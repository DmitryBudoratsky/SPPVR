<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
    	'migrate' => [
    		'class' => 'yii\console\controllers\MigrateController',
    		'migrationTable' => 'migration',
    		'migrationNamespaces' => [
                //DONT touch
                'console\migrations\user',
                'console\migrations\page',
                'console\migrations\file',
                'console\migrations\push',
                'console\migrations\settings',

                //touch
    			'console\migrations\category',
    			'console\migrations\chat',
    			'console\migrations\comment',
    			'console\migrations\complaint',
    			'console\migrations\post',
    			'console\migrations\product',

                'console\migrations\country',
    			'console\migrations\aboutUs',
    			'console\migrations\userSubscription',	
    			'console\migrations\userRelation',	
    			'console\migrations\account',	
    			'console\migrations\request',

                'console\migrations\service',
                'console\migrations\organization',

                'console\migrations\vehicle',
                'console\migrations\promoCode',
                'console\migrations\shop',
                'console\migrations\notification',
                'console\migrations\specialistProfile',
                'console\migrations\review',

                'console\migrations\lmsCourse',
                'console\migrations\quiz',

                'console\migrations\bankCard',
                'console\migrations\paymentInfo',

                'console\migrations\favoriteItem',

                'console\migrations\uniqueIndexes',

                'console\migrations\geolocation',
            ],
    	],
    ],
	
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
            'scriptUrl' => 'http://',
            'hostInfo' => 'http://',
            'baseUrl' => 'http://',
        ],
    ],
    'params' => $params,
];
