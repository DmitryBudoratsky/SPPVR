<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property int $chatId
 * @property int $isHidden
 * @property int $createdAt
 * @property int $updatedAt
 * @property string $title
 * @property int $type
 * @property int $avatarFileId
 * @property int $messageCount
 * @property int $chatMemberCount
 * @property int $isPublic
 *
 * @property File $avatarFile
 * @property ChatMember[] $chatMembers
 * @property Message[] $messages
 */
class BaseChat extends \common\models\db\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isHidden', 'createdAt', 'updatedAt', 'type', 'avatarFileId', 'messageCount', 'chatMemberCount', 'isPublic'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['avatarFileId'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['avatarFileId' => 'fileId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'chatId' => 'Chat ID',
            'isHidden' => 'Is Hidden',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'title' => 'Title',
            'type' => 'Type',
            'avatarFileId' => 'Avatar File ID',
            'messageCount' => 'Message Count',
            'chatMemberCount' => 'Chat Member Count',
            'isPublic' => 'Is Public',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvatarFile()
    {
        return $this->hasOne(File::className(), ['fileId' => 'avatarFileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChatMembers()
    {
        return $this->hasMany(ChatMember::className(), ['chatId' => 'chatId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['chatId' => 'chatId']);
    }
}
