<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'defaultRoute' => 'user/index',
    'on beforeRequest'          => function ($event) {
        Yii::$container->set('yii\grid\DataColumn', [
            'filterInputOptions' => [
                'class'       => 'form-control',
                'placeholder' => "Введите данные для поиска"
            ]
        ]);
    },
    'modules' => [
        'debug' => [
            'class' => 'common\components\PrivateDebug',
            'allowedIPs' => ['*', '127.0.0.1', '::1'],
            'historySize' => 500,
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
            // see settings on http://demos.krajee.com/grid#module
        ],
        // If you use tree table
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
            // see settings on http://demos.krajee.com/tree-manager#module
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\db\User',
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'writeCallback' => function($session){
                return [
                    'user_id' => Yii::$app->user->id
                ];
            },
            'sessionTable' => 'userSession',
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
    'params' => $params,
];
