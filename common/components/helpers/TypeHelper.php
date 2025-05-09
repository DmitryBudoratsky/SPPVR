<?php

namespace common\components\helpers;

use yii\db\ActiveRecord;

class TypeHelper
{    
    /**
     * @param ActiveRecord $model
     * @param string $fieldName
     * @return string
     */
	public static function getTypeLabelByModel($model, $fieldName)
	{
		if (empty($model)) {
			return '';
		}
		return self::getTypeLabel($model, $model->{$fieldName}, $fieldName);
	}
	
    /**
     * @param ActiveRecord $model
     * @param integer $typeValue
     * @param string $fieldName
     * @return string
     */
    public static function getTypeLabel($model, $typeValue, $fieldName = null)
    {
    	$getLabelsMethod = $fieldName . 'Labels';
        $types = $model::$getLabelsMethod();
        return isset($types[$typeValue]) ? $types[$typeValue] : null;
    }
    
    /**
     * @param array $labelsArray
     * @return array
     */
    public static function prepareTypeArray($labelsArray)
    {
    	$typeArray = [];
    	foreach ($labelsArray as $value => $label) {
    		$row = [];
    		$row['value'] = $value;
    		$row['label'] = $label;
    		$typeArray[] = $row;
    	}
    	
    	return $typeArray;
    }
}
