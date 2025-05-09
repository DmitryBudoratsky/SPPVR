<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\db\base;

use yii\helpers\Html;
use common\models\db\File;

/**
 * Description of BaseModel
 *
 */
class BaseModel extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
	public function insert($runValidation = true, $attributes = null)
	{
		if ($this->hasAttribute('createdAt')) {
			$this->createdAt = time();
		}
		return parent::insert($runValidation, $attributes);
	}

	/**
     * @inheritdoc
     */
	public function save($runValidation = true, $attributeNames = null)
	{
		if ($this->hasAttribute('updatedAt')) {
			$this->updatedAt = time();
		}
		return parent::save($runValidation, $attributeNames);
	}
	

    /**
     * Общий метод для сохранения связанного с моделей файла с поддержкой хранения через сущность File.
     * Контролирует, чтобы при перезаписи старый загруженный файл также был удален с сервера.
     * @param yii\web\UploadedFile $uploadedFile загруженный на сервер файл.
     * @param string $fileRelationName
     * @param string $destinationFolder
     * @return boolean
     */
	public function saveModelFile($uploadedFile, $fileRelationName, $destinationFolder)
	{
		$fileRelationIdName = $fileRelationName . 'Id';
		/**
         * @var File $oldFileModel
         */
        $oldFileModel = $this->$fileRelationName;
        /**
         * @var File $fileModel
         */
    	$fileModel = new File();
    	$fileModel->upload($uploadedFile, $destinationFolder);
    	if (!$fileModel->save()) {
    		return false;
    	}

    	$this->$fileRelationIdName = $fileModel->fileId;
        if (!empty($oldFileModel)) {
            $oldFileModel->delete();
        }
    	return true;
	}
	
	public function createdAtToString()
	{
	    return \Yii::$app->formatter->asDatetime($this->createdAt, 'yyyy-MM-dd HH:mm:ss');
	}
	
	public function updatedAtToString()
	{
        return \Yii::$app->formatter->asDatetime( $this->updatedAt, 'yyyy-MM-dd HH:mm:ss');
	}
}