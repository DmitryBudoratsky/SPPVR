<?php

namespace common\models\db;

class Message extends BaseMessage
{
    const TYPE_START = 0;
    const TYPE_USER = 1;
    const TYPE_AI = 2;

    const SOCKET_TYPE = 'message';

    const AI_NAME = 'Нейро';

    public $file;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'createdAt' => 'Дата и время отправки',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert && in_array($this->type, [self::TYPE_START, self::TYPE_USER])) {
            $this->sendToNeuro();
        }
    }

    public function sendToNeuro()
    {
        $response = $this->chat->sendToNeuro();
        if (is_null($response)) {
            return false;
        }

        $message = new Message(['type' => Message::TYPE_AI, 'text' => $response, 'chatId' => $this->chatId]);

        return $message->save();
    }
}
