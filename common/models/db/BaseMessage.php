<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $messageId
 * @property int $chatId
 * @property int $senderUserId
 * @property string $text
 * @property int $isAutoMessage
 * @property int $fileId
 * @property int $createdAt
 * @property int $updatedAt
 * @property int $status
 * @property int $isSystem
 * @property int $quotedMessageId
 * @property int $isUpdated
 *
 * @property Chat $chat
 * @property File $file
 * @property BaseMessage $quotedMessage
 * @property BaseMessage[] $baseMessages
 * @property User $senderUser
 * @property UserMessage[] $userMessages
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
            [['chatId'], 'required'],
            [['chatId', 'senderUserId', 'isAutoMessage', 'fileId', 'createdAt', 'updatedAt', 'status', 'isSystem', 'quotedMessageId', 'isUpdated'], 'integer'],
            [['text'], 'string'],
            [['chatId'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::className(), 'targetAttribute' => ['chatId' => 'chatId']],
            [['fileId'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['fileId' => 'fileId']],
            [['quotedMessageId'], 'exist', 'skipOnError' => true, 'targetClass' => BaseMessage::className(), 'targetAttribute' => ['quotedMessageId' => 'messageId']],
            [['senderUserId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['senderUserId' => 'userId']],
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
            'senderUserId' => 'Sender User ID',
            'text' => 'Text',
            'isAutoMessage' => 'Is Auto Message',
            'fileId' => 'File ID',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'status' => 'Status',
            'isSystem' => 'Is System',
            'quotedMessageId' => 'Quoted Message ID',
            'isUpdated' => 'Is Updated',
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
    public function getFile()
    {
        return $this->hasOne(File::className(), ['fileId' => 'fileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotedMessage()
    {
        return $this->hasOne(BaseMessage::className(), ['messageId' => 'quotedMessageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseMessages()
    {
        return $this->hasMany(BaseMessage::className(), ['quotedMessageId' => 'messageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSenderUser()
    {
        return $this->hasOne(User::className(), ['userId' => 'senderUserId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserMessages()
    {
        return $this->hasMany(UserMessage::className(), ['messageId' => 'messageId']);
    }
}
