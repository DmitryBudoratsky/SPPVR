<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property int $fileId
 * @property string $url
 * @property string $type
 * @property string $mimeType
 * @property string $originalName
 * @property int $createdAt
 * @property int $updatedAt
 *
 * @property AudioFile[] $audioFiles
 * @property Chat[] $chats
 * @property ImageFile[] $imageFiles
 * @property LinkedFile[] $linkedFiles
 * @property Message[] $messages
 * @property User[] $users
 * @property VideoFile[] $videoFiles
 * @property VideoFile[] $videoFiles0
 */
class BaseFile extends \common\models\db\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdAt', 'updatedAt'], 'integer'],
            [['url', 'type', 'mimeType', 'originalName'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fileId' => 'File ID',
            'url' => 'Url',
            'type' => 'Type',
            'mimeType' => 'Mime Type',
            'originalName' => 'Original Name',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAudioFiles()
    {
        return $this->hasMany(AudioFile::className(), ['fileId' => 'fileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::className(), ['avatarFileId' => 'fileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageFiles()
    {
        return $this->hasMany(ImageFile::className(), ['fileId' => 'fileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinkedFiles()
    {
        return $this->hasMany(LinkedFile::className(), ['fileId' => 'fileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['fileId' => 'fileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['avatarFileId' => 'fileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideoFiles()
    {
        return $this->hasMany(VideoFile::className(), ['fileId' => 'fileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideoFiles0()
    {
        return $this->hasMany(VideoFile::className(), ['previewImageFileId' => 'fileId']);
    }
}
