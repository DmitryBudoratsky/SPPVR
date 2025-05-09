<?php

namespace common\components\helpers;

class AttributesHelper
{
	/**
	 * @param array $attributes
	 * @return array
	 */
	public static function getAttributes($attributes)
	{
		$attributesArr = [];
		foreach ($attributes as $attribute => $value) {
			if (!empty($value)) {
				$attributesArr[$attribute] = $value;
			}
		}
		
		return $attributesArr;
	}
}
