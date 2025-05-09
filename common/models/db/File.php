<?php

namespace common\models\db;

use common\components\converters\VideoConverterComponent;
use OpenApi\Annotations as OA;
use yii\helpers\Url;
use yii;
use common\components\helpers\FileTypeHelper;
use yii\helpers\FileHelper;

/**
 * @OA\Schema(schema="File", description="Файл, загруженный на сервер", allOf={
 *     @OA\Schema(ref="#/components/schemas/ImageFile"),
 *     @OA\Schema(
 *         @OA\Property(property="audioFile", ref="#/components/schemas/AudioFileInfo"),
 *         @OA\Property(property="videoFile", ref="#/components/schemas/VideoFileInfo"),
 *     )
 * })
 */
class File extends BaseFile
{
    const FORMAT_IMAGE_JPG = '.jpg';
    const FORMAT_VIDEO_PREVIEW_IMAGE_MIME_TYPE = "image/jpg";

    const DISPLAY_VIDEO_WIDTH = 460;
    const DISPLAY_VIDEO_HEIGHT = 200;

	public $subfolder;
	public $fileUrl;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'fileId' => 'ID',
			'url' => 'Ссылка',
			'type' => 'Тип',
			'mimeType' => 'Mime тип',
			'originalName' => 'Оригинальное название',
			'createdAt' => 'Дата создания',
			'updatedAt' => 'Дата обновления'
		];
	}


	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return array_merge(parent::rules(), [
			['fileUrl', 'string'],
		]);
	}

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @return bool|void
     * @throws yii\base\Exception
     * @throws yii\db\Exception
     */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		if ($this->isFileImage()) {
		    /**
			 * @var ImageFile $imageFile
			 */
			$imageFile = ImageFile::find()->where(['fileId' => $this->fileId])->one();
			if (!empty($imageFile)) {
				return;
			}

			$this->createImageFile();
		}

		if ($insert) {
            if ($this->isFileVideo()) {
                $this->createVideoFile();
            }
            if ($this->isFileAudio()) {
                $this->createAudioFile();
            }
        }

		return true;
	}

    /**
     * @throws yii\base\Exception
     */
	private function createImageFile()
    {
        /**
         * @var ImageFile $imageFile
         */
        $imageFile = new ImageFile();

        $subfolder = $this->subfolder;

        $uploadDir = $this->getUploadDir($subfolder);

        $fileName = \Yii::$app->security->generateRandomString() . self::FORMAT_IMAGE_JPG;

        $webPath = $this->getWebPath($fileName, $subfolder);

        $destFilePath = $uploadDir . '/' . $fileName;

        $imageFile->fileId = $this->fileId;
        $imageFile->previewUrl = Url::to("@webFilesUploads/{$webPath}");

        $imageFile->prepareImageFile(Yii::getAlias('@frontendWeb') . '/' . $this->url,
            $destFilePath,
            Yii::$app->params['maxImagePreviewWidth'],
            Yii::$app->params['maxImagePreviewHeight']);
    }

    private function createAudioFile()
    {
        $duration = \common\components\Yii::getAudioComponent()->getAudioDuration($this->url);
        AudioFile::createAudioFile($this->fileId, $duration);
    }

    /**
     * @return bool
     * @throws yii\db\Exception
     */
    public function createVideoFile()
    {
        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $filenameWithoutExtension = \Yii::$app->security->generateRandomString();
            $newFilename = $filenameWithoutExtension . VideoConverterComponent::VIDEO_FORMAT_CONVERT_EXTENSION;
            $fileUploadDirPath = Yii::getAlias('@uploadsVideoFiles');

            $previewImageFilename = $filenameWithoutExtension . self::FORMAT_IMAGE_JPG;
            $previewImageFileUploadPath = Yii::getAlias('@uploadsVideoFilesPreviewImages') . '/' . $previewImageFilename;

            $previewImageFileUploadDirPath = Yii::getAlias('@uploadsVideoFilesPreviewImages');

            $previousFileUrl = $this->url;
            $videoConverter = \common\components\Yii::getVideoConverterComponent();
            // convert video file
            if (!$videoConverter->convertToMp4($this->url, $newFilename, $fileUploadDirPath, $previewImageFileUploadDirPath, $previewImageFilename)) {
                $transaction->rollBack();
                return false;
            }
            $this->url = Yii::getAlias('@webVideoUploads') . '/' . $newFilename;
            $this->mimeType = VideoConverterComponent::VIDEO_FORMAT_CONVERT_MIME_TYPE;
            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }

            $previewUrl = Yii::getAlias('@webVideoUploadsPreviewImages') . '/' . $previewImageFilename;
            $pathInfo = pathinfo($previewImageFileUploadPath);
            /**
             * @var File $previewFile
             */
            $previewFile = self::createFile($previewUrl, FileTypeHelper::TYPE_IMAGE, self::FORMAT_VIDEO_PREVIEW_IMAGE_MIME_TYPE, $pathInfo["basename"]);
            if (empty($previewFile)) {
                $transaction->rollBack();
                return false;
            }

            VideoFile::createVideoFile(
                $this->fileId, $previewFile->fileId,
                $videoConverter->newWidth,
                $videoConverter->newHeight,
                $videoConverter->duration
            );

            $previousFilePath = Yii::getAlias("@frontendWeb") . '/' . $previousFileUrl;
            if (file_exists($previousFilePath)) {
                if (!@unlink($previousFilePath)) {
                    $transaction->rollBack();
                    return false;
                }
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
            return false;
        }
    }

    /**
     * @param $url
     * @param $type
     * @param $mimeType
     * @param $originalName
     * @return File|null
     */
    public static function createFile($url, $type, $mimeType, $originalName)
    {
        /**
         * @var File $previewFile
         */
        $previewFile = new File();
        $previewFile->url = $url;
        $previewFile->type = $type;
        $previewFile->mimeType = $mimeType;
        $previewFile->originalName = $originalName;
        return ($previewFile->save()) ? $previewFile : null;
    }

	/**
	 * @return array
	 */
	public function serializeToArray()
	{
		$fileInfo = [];
		$fileInfo["fileId"] = $this->fileId;
		$fileInfo["url"] = $this->getAbsoluteFileUrl();
		$fileInfo["type"] = $this->type;
		$fileInfo["mimeType"] = $this->mimeType;
		$fileInfo["originalName"] = $this->originalName;
		$fileInfo["createdAt"] = $this->createdAt;

		if ($this->isFileImage()) {
			/**
			 * @var ImageFile $imageFile
			 */
			$imageFile = $this->getImageFile()->one();
			if(!empty($imageFile)) {
				$fileInfo = array_merge($fileInfo, $imageFile->serializeToArray());
			}
		}

		if ($this->isFileVideo()) {
            /**
             * @var VideoFile $videoFile
             */
		    $videoFile = $this->getVideoFile()->one();
		    if (!empty($videoFile)) {
                $fileInfo["videoFile"] = $videoFile->serializeToArray();
            }
        }

        if ($this->isFileAudio()) {
            /**
             * @var AudioFile $audioFile
             */
            $audioFile = $this->getAudioFile()->one();
            if (!empty($audioFile)) {
                $fileInfo["audioFile"] = $audioFile->serializeToArray();
            }
        }

		return $fileInfo;
	}

    /**
     * @return bool
     */
    public function isFileImage()
    {
        return ($this->type == FileTypeHelper::TYPE_IMAGE);
    }

    /**
     * @return boolean
     */
    public function isFileVideo()
    {
        return ($this->type == FileTypeHelper::TYPE_VIDEO);
    }

    /**
     * @return boolean
     */
    public function isFileAudio()
    {
        return ($this->type == FileTypeHelper::TYPE_AUDIO);
    }

    /**
     * @param $file
     * @param null $subfolder
     * @return bool
     * @throws yii\base\Exception
     * @throws yii\db\Exception
     */
	public function upload($file, $subfolder = null)
	{
		if (!$this->validate()) {
			return false;
		}

		if (!empty($subfolder)) {
			$this->subfolder = $subfolder;
		}

		$uploadDir = $this->getUploadDir($subfolder);
		$fileName = Yii::$app->security->generateRandomString() . '.' . $file->extension;
		$filePath = $uploadDir . '/' . $fileName;

		$db = \Yii::$app->db;
		$transaction = $db->beginTransaction();
		try {
			if (!$file->saveAs($filePath)) {
				$transaction->rollBack();
				return false;
			}

            $webPath = $this->getWebPath($fileName, $subfolder);
			
			$this->url = Url::to("@webFilesUploads/{$webPath}");
			$this->type = FileTypeHelper::getFileType($file);
			$this->mimeType = $file->type;
			$this->originalName = $file->name;
			if (!$this->save()) {
				$transaction->rollBack();
				return false;
			}
			
			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw $e;
			return false;
		}
		
		return true;
	}

    /**
     * Получить абсолютный путь до файла
     * @param $fileName
     * @param $subfolder
     * @return string
     */
	private function getWebPath($fileName, $subfolder)
    {
        $webPath = $fileName;
        $yearWithMonthFormat = date("Ym");
        $webPath = $yearWithMonthFormat . '/' . $webPath;
        if (!empty($subfolder)) {
            $webPath = $subfolder . '/' . $webPath;
        }
        return $webPath;
    }

    /**
     * @param null $subfolder
     * @return bool|string
     * @throws yii\base\Exception
     */
	public function getUploadDir($subfolder = null)
	{
		$uploadDir = Yii::getAlias('@filesUploads');

        if (!empty($subfolder)) {
			$uploadDir .= '/' . $subfolder;
		}
        $yearWithMonthFormat = date("Ym");
        $uploadDir .= '/' . $yearWithMonthFormat;
		if (!file_exists($uploadDir)) {
			FileHelper::createDirectory($uploadDir);
		}
		return $uploadDir;
	}

	/**
	 * {@inheritDoc}
	 * @see \yii\db\BaseActiveRecord::beforeDelete()
	 */
	public function beforeDelete()
	{
	    if (!parent::beforeDelete()) {
	        return false;
        }

        $this->tryDeleteFile();
        return true;
	}

	/** Delete file
	 */
	public function tryDeleteFile()
	{
		// delete file
		if (empty($this->url)) {
			return;
		}
		$filePath = Yii::getAlias("@frontendWeb") . '/' . $this->url;

		if (file_exists($filePath)) {
			@unlink($filePath);
			$this->url = null;
		}
		/**
		 * @var ImageFile
		 */
		$imageFile = ImageFile::find()->where(['fileId' => $this->fileId])->one();
		if (!empty($imageFile)) {
			$uploadDir = $this->getUploadDir($this->subfolder);
			$filePath = Yii::getAlias("@frontendWeb") . '/' . $imageFile->previewUrl;
			if (file_exists($filePath)) {
				@unlink($filePath);
				$imageFile->delete();
			}
		}
        /**
         * @var VideoFile $videoFile
         */
        $videoFile = $this->getVideoFile()->one();
        if (!empty($videoFile)) {
            $videoFile->delete();
        }
	}

	/** Create absolute file url
	 * @return string
	 */
	public function getAbsoluteFileUrl()
	{
		if (empty($this->url)) {
			return null;
		}
        return \Yii::$app->frontendUrlManager->createAbsoluteUrl([$this->url]);
	}

    /**
     * Получить ссылку для предпросмотра картинки
     * @return string|null
     */
    public function getPreviewImageUrl()
    {
        $imageFile = ImageFile::findOne(['fileId' => $this->fileId]);

        if (empty($imageFile)) {
            return null;
        }
        return \Yii::$app->frontendUrlManager->createAbsoluteUrl([$imageFile->previewUrl]);
    }

    /**
     * @param $binaryImage
     * @param null $subfolder
     * @return bool
     * @throws yii\base\Exception
     */
	public function saveBinaryImage($binaryImage, $subfolder = null)
	{
		if (!empty($subfolder)) {
			$this->subfolder = $subfolder;
		}

		$uploadDir = $this->getUploadDir($subfolder);

		$fileName = \Yii::$app->security->generateRandomString() . self::FORMAT_IMAGE_JPG;

		$fp = fopen($uploadDir . '/' . $fileName, 'w');
		$result = fwrite($fp, $binaryImage);
		fclose($fp);

		if (empty($result)) {
			return false;
		}

		// размер картинки
		$imageInfo = getimagesize($uploadDir . '/' . $fileName);

        $yearWithMonthFormat = date("Ym");

        $webPath = $fileName;
		if (!empty($subfolder)) {
			$webPath = $subfolder . '/' . $yearWithMonthFormat . '/' . $webPath;
		}

		$this->url = Url::to("@webFilesUploads/{$webPath}");
		$this->mimeType = $imageInfo["mime"];
		if (in_array($this->mimeType, FileTypeHelper::imageMimeTypes())) {
			$this->type = FileTypeHelper::TYPE_IMAGE;
		}
		$this->createdAt = time();
		if (!$this->save()) {
			return false;
		}

		return true;
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideoPreviewImageFiles()
    {
        return $this->hasMany(VideoFile::className(), ['previewImageFileId' => 'fileId']);
    }
}
