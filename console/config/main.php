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
                'console\migrations\user',
                'console\migrations\file',
    			'console\migrations\chat',
                'console\migrations\incident',
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
