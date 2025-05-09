<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\db\base;

use common\models\db\Complaint;

/**
 *
 */
class AbstractUserGeneratedContent extends BaseModel
{
	public function getObjectName()
	{
		if ($this->hasAttribute('title')) {
			return $this->getAttribute('title');
		} elseif ($this->hasAttribute('name')) {
			return $this->getAttribute('name');
		} elseif ($this->hasAttribute('text')) {
			return $this->getAttribute('text');
		}
	}

	/**
	 * Слушатель для события принятия жалобы
	 * 
	 * @param Complaint $complaint
	 */
	public function onAcceptedComlaint($complaint)
	{
		\Yii::warning('AbstractUserGeneratedContent');
	}
	
	/**
	 * Слушатель для события отклонения жалобы
	 * 
	 * @param Complaint $complaint
	 */
	public function onRejectedComlaint($complaint)
	{
		\Yii::warning('AbstractUserGeneratedContent');
	}

	/**
	 * 
	 * @return \yii\db\ActiveQuery
	 */
	public function getComplaints()
	{
		$query = Complaint::find()->where(['objectId' => $this->primaryKey])->andWhere(['objectType' => $this->tableName()]);
		return $query;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \yii\db\BaseActiveRecord::beforeDelete()
	 */
	public function beforeDelete()
	{		
		if (parent::beforeDelete()) {
			foreach ($this->getComplaints()->each() as /** @var Complaints $complaint */ $complaint) {
				$complaint->delete();
			}
			return true;
		} else {
			return false;
		}
	}
}
