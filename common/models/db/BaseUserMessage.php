<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "userMessage".
 *
 * @property int $userMessageId
 * @property int $messageId
 * @property int $userId
 * @property int $status
 * @property int $createdAt
 * @property int $updatedAt
 *
 * @property Message $message
 * @property User $user
 */
class BaseUserMessage extends \common\models\db\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userMessage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['messageId', 'userId'], 'required'],
            [['messageId', 'userId', 'status', 'createdAt', 'updatedAt'], 'integer'],
            [['messageId'], 'exist', 'skipOnError' => true, 'targetClass' => Message::className(), 'targetAttribute' => ['messageId' => 'messageId']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'userId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'userMessageId' => 'User Message ID',
            'messageId' => 'Message ID',
            'userId' => 'User ID',
            'status' => 'Status',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        return $this->hasOne(Message::className(), ['messageId' => 'messageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['userId' => 'userId']);
    }
}
