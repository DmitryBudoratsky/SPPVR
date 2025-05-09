<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "chatMember".
 *
 * @property int $chatMemberId
 * @property int $userId
 * @property int $chatId
 * @property int $createdAt
 * @property int $updatedAt
 * @property int $notificationEnabled
 * @property int $blockedUntil
 * @property int $chatRole
 *
 * @property Chat $chat
 * @property User $user
 */
class BaseChatMember extends \common\models\db\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chatMember';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'chatId'], 'required'],
            [['userId', 'chatId', 'createdAt', 'updatedAt', 'notificationEnabled', 'blockedUntil', 'chatRole'], 'integer'],
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
            'chatMemberId' => 'Chat Member ID',
            'userId' => 'User ID',
            'chatId' => 'Chat ID',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'notificationEnabled' => 'Notification Enabled',
            'blockedUntil' => 'Blocked Until',
            'chatRole' => 'Chat Role',
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
