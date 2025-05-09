<?php

namespace common\models\db;

use Yii;

/**
 * @OA\Schema(schema="AudioFileInfo", description="Информация об аудио-файле", properties={
 *     @OA\Property(property="duration", type="integer", description="Длительность аудио"),
 * })
 */
class AudioFile extends BaseAudioFile
{
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'audioFileId' => 'ID',
            'fileId' => 'Файл',
            'duration' => 'Длительность',
            'createdAt' => 'Дата создания',
            'updatedAt' => 'Дата обновления',
        ];
    }

    /**
     * @param $fileId
     * @param $duration
     * @return bool
     */
    public static function createAudioFile($fileId, $duration)
    {
        $audioFile = new AudioFile();
        $audioFile->fileId = $fileId;
        $audioFile->duration = $duration;
        return $audioFile->save();
    }

    /**
     * @return array
     */
    public function serializeToArray()
    {
        $audioFileInfoObj = [];
        $audioFileInfoObj["duration"] = floatval($this->duration);
        return $audioFileInfoObj;
    }
}
