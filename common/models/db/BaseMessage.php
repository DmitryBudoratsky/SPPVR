<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $messageId
 * @property int $chatId
 * @property int $userId
 * @property int $type
 * @property string $text
 * @property int $createdAt
 * @property int $updatedAt
 *
 * @property Chat $chat
 * @property User $user
 */
class BaseMessage extends \common\models\db\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chatId', 'type'], 'required'],
            [['chatId', 'userId', 'type', 'createdAt', 'updatedAt'], 'integer'],
            [['text'], 'string'],
            [['chatId'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::className(), 'targetAttribute' => ['chatId' => 'chatId']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'userId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'messageId' => 'Message ID',
            'chatId' => 'Chat ID',
            'userId' => 'User ID',
            'type' => 'Type',
            'text' => 'Text',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chat::className(), ['chatId' => 'chatId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['userId' => 'userId']);
    }
}
