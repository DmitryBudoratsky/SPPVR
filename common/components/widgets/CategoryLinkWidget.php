<?php

namespace common\components\widgets;

use yii\base\Widget;
use common\models\db\Category;
use yii\helpers\Html;

class CategoryLinkWidget extends Widget
{	
	/**
	 * @var Category $category
	 */
    public $category;
    public $action;

    public function run()
    {	
    	
   		$categories = [];
		if (!empty($this->category)) {
			$categories = $this->category->getParents();
		}

		$linksObj = [];
        foreach ($categories as $category) {
            /** @var Category $category */  	
        	$linksObj[] = Html::a($category->title, [$this->action, 'id' => $category->categoryId]);
        }
		
		$currentCategoryAction = 'view';
		if ($this->category->hasSubcategories == Category::HAS_SUBCATEGORIES) {
			$currentCategoryAction = 'index';
		}
        $linksObj[] = Html::a($this->category->title, [$currentCategoryAction, 'id' => $this->category->categoryId]);
		
    	return implode(' > ', $linksObj);
    }
}
?>