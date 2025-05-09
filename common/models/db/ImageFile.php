<?php

namespace common\models\db;

use common\components\Yii;
use OpenApi\Annotations as OA;
use yii\helpers\Url;

/**
 * @OA\Schema(schema="ImageFile", description="Изображение - файл", properties={
 *     @OA\Property(property="fileId", type="integer", description="ID файла"),
 *     @OA\Property(property="url", type="string", description="URL файла"),
 *     @OA\Property(property="previewUrl", type="string", description="Ссылка превью картинки"),
 *     @OA\Property(property="width", type="integer", description="Ширина картинки"),
 *     @OA\Property(property="height", type="integer", description="Высота картинки"),
 *     @OA\Property(property="previewWidth", type="integer", description="Ширина превью картинки"),
 *     @OA\Property(property="previewHeight", type="integer", description="Ширина превью картинки"),
 * })
 */
class ImageFile extends BaseImageFile
{
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'imageFileId' => 'ID',
			'fileId' => 'ID файла',
			'previewUrl' => 'Превью картинки',
			'width' => 'Ширина картинки',
			'height' => 'Высота картинки',
			'previewWidth' => 'Ширина превью картинки',
			'previewHeight' => 'Высота превью картинки',
			'createdAt' => 'Дата создания',
			'updatedAt' => 'Дата обновления'
		];
	}

	/**
	 * @return array
	 */
	public function serializeToArray()
	{
		$imageFileInfoObj = [];

        $imageFileInfoObj["url"] = $this->file->getAbsoluteFileUrl();

		$imageFileInfoObj["previewUrl"] = $this->getAbsolutePreviewImageUrl();
        $imageFileInfoObj["width"] = $this->width;
        $imageFileInfoObj["blurHash"] = $this->blurHash;
		$imageFileInfoObj["height"] = $this->height;
		$imageFileInfoObj["previewWidth"] = $this->previewWidth;
		$imageFileInfoObj["previewHeight"] = $this->previewHeight;

		return $imageFileInfoObj;
	}

	/** Create absolute image url
	 * @return string
	 */
	public function getAbsolutePreviewImageUrl()
	{
		if (empty($this->previewUrl)) {
			return null;
		}
		return Url::to($this->previewUrl, true);
	}

    /**
     * @param $sourceFile
     * @param $destImagePath
     * @param int $maxPreviewWidth
     * @param int $maxPreviewHeight
     * @return bool
     */
    public function prepareImageFile($sourceFile, $destImagePath, $maxPreviewWidth = 360, $maxPreviewHeight = 360)
    {
        if (!file_exists($sourceFile)) {
            return false;
        }

        $sourceImageContent = @file_get_contents($sourceFile);
        if (empty($sourceImageContent)) {
            return false;
        }
        //Yii::debug($sourceImageContent);
        if (exif_imagetype($sourceFile) == IMAGETYPE_WEBP) {
            $sourceImage = imagecreatefromwebp($sourceFile);
        } else {
            $sourceImage = imagecreatefromstring($sourceImageContent);
        }
        if (empty($sourceImage)) {
            return false;
        }

        // размер картинки
        list($width, $height) = getimagesize($sourceFile);
        $exifData = $this->getExifData($sourceFile);
        \Yii::info("Image exif data: " . var_export($exifData, true));

        $scaleWidth = ($width > $maxPreviewWidth) ? ($maxPreviewWidth / $width) : 1;
        $scaleHeight = ($height > $maxPreviewHeight) ? ($maxPreviewHeight / $height) : 1;

        $scale = min($scaleWidth, $scaleHeight);

        $newWidth = $width * $scale;
        $newHeight = $height * $scale;

        $width = round($width);
        $height = round($height);
        $newWidth = round($newWidth);
        $newHeight = round($newHeight);

        $destImage = imagecreatetruecolor($newWidth, $newHeight);
        $white = imagecolorallocate($destImage,  255, 255, 255);
        imagefilledrectangle($destImage, 0, 0, $width, $height, $white);
        imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $orientation = $this->getOrientation($exifData);
        $destImage = $this->processFileRotation($destImage, $orientation);

        if (exif_imagetype($sourceFile) == IMAGETYPE_WEBP) {
            $success = imagewebp ($destImage, $destImagePath, 95);
        } else {
            $success = imagejpeg($destImage, $destImagePath, 95);
        }


        imagedestroy($sourceImage);
        imagedestroy($destImage);

        $isImageRorated = in_array($orientation, [6, 8]);
        if ($isImageRorated) {
            $this->width = $height;
            $this->height = $width;
            $this->previewWidth = $newHeight;
            $this->previewHeight = $newWidth;
        } else {
            $this->width = $width;
            $this->height = $height;
            $this->previewWidth = $newWidth;
            $this->previewHeight = $newHeight;
        }

        if (!$this->save()) {
            $this->addError('imageFile', 'Не удалось сохранить информацию о прикрепленной картинке');
            return false;
        }

        return $success;
    }

	private function processFileRotation($destImage, $orientation)
	{
		if ($orientation !== null) {
			\Yii::info("Image orientation: $orientation");
			switch($orientation) {
				case 3:
					$destImage = imagerotate($destImage, 180, 0);
					break;
				case 6:
					$destImage = imagerotate($destImage, -90, 0);
					break;
				case 8:
					$destImage = imagerotate($destImage, 90, 0);
					break;
			}
		}
		return $destImage;
	}

	private function getOrientation($exifData)
	{
		if (isset($exifData["COMPUTED"])) {
			$computedData = $exifData["COMPUTED"];
			if (isset($computedData["Orientation"])) {
				return $computedData["Orientation"];
			}
		}
		return isset($exifData["Orientation"]) ? $exifData["Orientation"] : null;
	}

	private function getExifData($sourceFile)
	{
		$exifData = [];
		try {
			$exifData = @exif_read_data($sourceFile);
		} catch (Exception $e) {
			\Yii::error("Exif reading error: " . $e->getMessage());
			$exifData = [];
		}
		if (empty($exifData)) {
			$exifData = [];
		}
		return $exifData;
	}
}
