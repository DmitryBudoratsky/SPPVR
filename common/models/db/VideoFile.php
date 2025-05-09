<?php

namespace common\models\db;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="VideoFileInfo", description="Информация о видео-файле", properties={
 *     @OA\Property(property="width", type="integer", description="Ширина видео"),
 *     @OA\Property(property="height", type="string", description="Высота видео"),
 *     @OA\Property(property="duration", type="string", description="Длительность видео"),
 *     @OA\Property(property="videoPreviewImageFile", ref="#/components/schemas/ImageFile"),
 * })
 */
class VideoFile extends BaseVideoFile
{
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'videoFileId' => 'ID',
            'fileId' => 'Файл',
            'previewImageFileId' => 'Превью картинка',
            'width' => 'Ширина',
            'height' => 'Высота',
            'duration' => 'Длительность',
            'createdAt' => 'Дата создания',
            'updatedAt' => 'Дата обновления'
        ];
    }

    /**
     * @param $fileId
     * @param $imageFileId
     * @param $width
     * @param $height
     * @param $duration
     * @return bool
     */
    public static function createVideoFile($fileId, $imageFileId, $width, $height, $duration)
    {
        /**
         * @var VideoFile $videoFile
         */
        $videoFile = new VideoFile();
        $videoFile->fileId = $fileId;
        $videoFile->previewImageFileId = $imageFileId;
        $videoFile->width = $width;
        $videoFile->height = $height;
        $videoFile->duration = $duration;
        return $videoFile->save();
    }

    /**
     * @return array
     */
    public function serializeToArray()
    {
        $videoFileInfoObj = [];

        /**
         * @var File $videoPreviewImageFile
         */
        $videoPreviewImageFile = $this->previewImageFile;
        if (!empty($videoPreviewImageFile)) {
            /**
             * @var ImageFile $imageFile
             */
            $imageFile = $videoPreviewImageFile->getImageFile()->one();
            if(!empty($imageFile)) {
                $videoFileInfoObj["videoPreviewImageFile"] = $imageFile->serializeToArray();
            }
        }
        $videoFileInfoObj["width"] = $this->width;
        $videoFileInfoObj["height"] = $this->height;
        $videoFileInfoObj["duration"] = floatval($this->duration);

        return $videoFileInfoObj;
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }
        $file = $this->previewImageFile;
        if (!empty($file)) {
            $file->delete();
        }
        return true;
    }
}
