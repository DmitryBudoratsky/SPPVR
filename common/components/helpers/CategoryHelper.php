<?php

namespace common\components\helpers;

use common\models\db\Category;
use yii\helpers\Html;

class CategoryHelper
{
    /**
     * @param Category $inputCategory
     * @return array
     */
    public static function getBreadcrumbsLinks($inputCategory, $view)
    {
		$linksObj = [];
		
		$parentCategory = $inputCategory->parentCategory;
		
		while (!empty($parentCategory)) {
			$params = [
				$view,
				'id' => $parentCategory->categoryId,
			]; 				
			array_unshift($linksObj, [
				'label' => $parentCategory->title,
				'url' => $params
			]);
			$parentCategory = $parentCategory->parentCategory;
		}
		
    	return $linksObj;
    }
    
    /**
     * @param Category $category
     * @param string $view
     * @return string
     */
    public static function getRedirectLink($category, $view)
    {
    	$link = [$view, 'id' => $category->parentCategoryId];
    	 
    	return $link;
    }
}
