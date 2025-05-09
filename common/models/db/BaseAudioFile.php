<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "audioFile".
 *
 * @property int $audioFileId
 * @property int $fileId
 * @property string $duration
 * @property int $createdAt
 * @property int $updatedAt
 *
 * @property File $file
 */
class BaseAudioFile extends \common\models\db\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'audioFile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fileId'], 'required'],
            [['fileId', 'createdAt', 'updatedAt'], 'integer'],
            [['duration'], 'number'],
            [['fileId'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['fileId' => 'fileId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'audioFileId' => 'Audio File ID',
            'fileId' => 'File ID',
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
}
