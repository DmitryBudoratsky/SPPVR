<?php

namespace common\models\db;

class Message extends BaseMessage
{
    const TYPE_START = 0;
    const TYPE_USER = 1;
    const TYPE_AI = 2;

    const SOCKET_TYPE = 'message';

    const AI_NAME = 'Нейро';
}
