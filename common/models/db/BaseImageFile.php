<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "imageFile".
 *
 * @property int $imageFileId
 * @property int $fileId
 * @property string $previewUrl
 * @property int $width
 * @property int $height
 * @property int $previewWidth
 * @property int $previewHeight
 * @property int $createdAt
 * @property int $updatedAt
 * @property string $blurHash
 *
 * @property File $file
 */
class BaseImageFile extends \common\models\db\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'imageFile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fileId'], 'required'],
            [['fileId', 'width', 'height', 'previewWidth', 'previewHeight', 'createdAt', 'updatedAt'], 'integer'],
            [['previewUrl', 'blurHash'], 'string', 'max' => 255],
            [['fileId'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['fileId' => 'fileId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'imageFileId' => 'Image File ID',
            'fileId' => 'File ID',
            'previewUrl' => 'Preview Url',
            'width' => 'Width',
            'height' => 'Height',
            'previewWidth' => 'Preview Width',
            'previewHeight' => 'Preview Height',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'blurHash' => 'Blur Hash',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::className(), ['fileId' => 'fileId']);
    }
}
