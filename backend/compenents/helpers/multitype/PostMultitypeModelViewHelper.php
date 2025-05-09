<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\compenents\helpers\multitype;

use common\models\db\Post;

/**
 * Description of PostMultitypeModelViewHelper
 *
 */
class PostMultitypeModelViewHelper extends AbstractMultitypeModelViewHelper
{
	
	public static function getModelLabels()
	{
		return [
			Post::POST_TYPE_ALL => 'Пост',
			Post::POST_TYPE_PROMO => 'Акция',
			Post::POST_TYPE_NEWS => 'Новость',
			Post::POST_TYPE_USER_POST => 'Пользовательский пост',
			Post::POST_TYPE_USER_POST => 'Объявление',
		];
	}

	public static function getModelsLabels()
	{
		return [
			Post::POST_TYPE_ALL => 'Все посты',
			Post::POST_TYPE_PROMO => 'Акции',
			Post::POST_TYPE_NEWS => 'Новости',
			Post::POST_TYPE_USER_POST => 'Пользовательские посты',
			Post::POST_TYPE_USER_AD => 'Объявления'
		];
	}

	public static function getModelsLabelsForOneObject()
	{
		return [
			Post::POST_TYPE_ALL => 'Все',
			Post::POST_TYPE_PROMO => 'Акции',
			Post::POST_TYPE_NEWS => 'Новости',
			Post::POST_TYPE_USER_POST => 'Пользовательские посты',
			Post::POST_TYPE_USER_AD => 'Объявления'
		];
	}

	public static function getNewModelLabels()
	{
		return [
			Post::POST_TYPE_ALL => 'Новый пост',
			Post::POST_TYPE_PROMO => 'Новая акция',
			Post::POST_TYPE_NEWS => 'Новая новость',
			Post::POST_TYPE_USER_POST => 'Новый пост',
			Post::POST_TYPE_USER_AD => 'Новое объявление'
		];
	}

}
