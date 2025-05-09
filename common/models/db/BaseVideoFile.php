<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "videoFile".
 *
 * @property int $videoFileId
 * @property int $fileId
 * @property int $previewImageFileId
 * @property int $width
 * @property int $height
 * @property string $duration
 * @property int $createdAt
 * @property int $updatedAt
 *
 * @property File $file
 * @property File $previewImageFile
 */
class BaseVideoFile extends \common\models\db\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'videoFile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fileId', 'previewImageFileId'], 'required'],
            [['fileId', 'previewImageFileId', 'width', 'height', 'createdAt', 'updatedAt'], 'integer'],
            [['duration'], 'number'],
            [['fileId'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['fileId' => 'fileId']],
            [['previewImageFileId'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['previewImageFileId' => 'fileId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'videoFileId' => 'Video File ID',
            'fileId' => 'File ID',
            'previewImageFileId' => 'Preview Image File ID',
            'width' => 'Width',
            'height' => 'Height',
            'duration' => 'Duration',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
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
    public function getPreviewImageFile()
    {
        return $this->hasOne(File::className(), ['fileId' => 'previewImageFileId']);
    }
}
