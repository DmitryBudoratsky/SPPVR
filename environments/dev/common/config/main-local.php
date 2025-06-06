<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],

        // веб-сокеты
        'websocket' => [
            'servers' => [
                'personal-socket-server' => [
//                    'websocket' => 'tcp://0.0.0.0:8301',
//                    'localsocket' => 'tcp://127.0.0.1:8302',
                ],
            ],
        ],
    ],
];
