<?php

namespace common\components\helpers;


class ArrayHelper
{  
    /**
     * Проверка атрибутов на изменение (для afterSave)
     * @param string[]
     * @param mixed[]
     * @return boolean
     */
    public static function checkChangedAttributes($arayForCheck, $changedAttributes)
    {       
        $attributes = [];

        foreach ($changedAttributes as $key => $value) {
            $attributes[] = $key;
        }
        
        return self::checkArrayContainsArray($arayForCheck, $attributes);
    }
    
    /**
     * Вхождение одного элемента массива в другой массив
     * @param $arayForCheck[]
     * @param $array[]
     * @return boolean
     */
    public static function checkArrayContainsArray($arayForCheck, $array)
    {
    	foreach ($arayForCheck as $value) {
    		if (in_array($value, $array)) {
    			return true;
    		}
    	}
    	return false;
    }
    
    /**
     * Вхождение всех элементов массива в другой массив
     * @param $arayForCheck[]
     * @param $array[]
     * @return boolean
     */
    public static function checkAllElementsArrayContainsArray($arayForCheck, $array)
    {
    	foreach ($arayForCheck as $value) {
    		if (!in_array($value, $array)) {
    			return false;
    		}
    	}
    	return true;
    }
    
    /**
     * Проверка атрибутов на изменение (для beforeSave)
     * @param string[] $arayForCheck
     * @param mixed[] $oldAttributes
     * @param mixed[] $attributes
     * @return boolean
     */
    public static function checkAttributesForChange($arayForCheck, $oldAttributes, $attributes)
    {
    	foreach ($arayForCheck as $attribute) {    	
			if (isset($attributes[$attribute]) && isset($oldAttributes[$attribute])) {
				if ($attributes[$attribute] != $oldAttributes[$attribute]) {
					return true;
				}	
			}
    	}
    	
    	return false;
    }
}