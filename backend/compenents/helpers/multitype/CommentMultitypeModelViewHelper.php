<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\compenents\helpers\multitype;

use common\models\db\Comment;

/**
 * Description of CommentMultitypeModelViewHelper
 *
 */
class CommentMultitypeModelViewHelper extends AbstractMultitypeModelViewHelper
{	
	public static function getModelLabels()
	{
		return [
			Comment::OBJECT_TYPE_ALL => 'Комментарий',
			Comment::OBJECT_TYPE_POST => 'Комментарий к посту',
		];
	}

	public static function getModelsLabels()
	{
		return [
			Comment::OBJECT_TYPE_ALL => 'Все',
			Comment::OBJECT_TYPE_POST => 'Комментарии к постам',
		];
	}
	
	public static function getModelsLabelsForOneObject()
	{
		return [
			Comment::OBJECT_TYPE_ALL => 'Комментарии',
			Comment::OBJECT_TYPE_POST => 'Комментарии к посту',
		];
	}

	public static function getNewModelLabels()
	{
		return [
			Comment::OBJECT_TYPE_ALL => 'Новый комментарий',
			Comment::OBJECT_TYPE_POST => 'Новый комментарий',
		];
	}
	
	/**
	 * 
	 * @param Comment $model
	 * @return [] URL
	 */
	public static function getObjectViewLink($model)
	{
		if ($model->objectType == Comment::OBJECT_TYPE_ALL) {
			return ['#'];
		} elseif ($model->objectType == Comment::OBJECT_TYPE_POST) {
			return ['/common/post/view', 'id' => $model->objectId];
		}
	}
}
