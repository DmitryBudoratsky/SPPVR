<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\compenents\helpers\multitype;

use common\models\db\Complaint;

/**
 * Description of ComplaintMultitypeModelViewHelper
 *
 */
class ComplaintMultitypeModelViewHelper extends AbstractMultitypeModelViewHelper
{	
	public static function getModelLabels()
	{
		return [
			'comment' => 'Жалоба на комментарий'
		];
	}

	public static function getModelsLabels()
	{
		return [
			'comment' => 'Жалобы на комментарии'
		];
	}
	
	public static function getModelsLabelsForOneObject()
	{
		return [
			'comment' => 'Жалобы на комментарий'
		];
	}

	public static function getNewModelLabels()
	{
		return [
			'comment' => 'Новая жалоба'
		];
	}
	
	/**
	 * 
	 * @param Complaint $model
	 * @return [] URL
	 */
	public static function getObjectViewLink($model)
	{
		if ($model->objectType == 'comment') {
			return ['/common/comment/view', 'id' => $model->objectId];
		} else {
			return ['#'];
		}
	}
}
