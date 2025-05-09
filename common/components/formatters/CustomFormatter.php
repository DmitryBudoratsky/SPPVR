<?php

namespace common\components\formatters;

use yii\i18n\Formatter;

class CustomFormatter extends Formatter
{
	public function init()
    {
        parent::init();
        
        $class = new \ReflectionClass("yii\\i18n\\Formatter");
		$property = $class->getProperty("_intlLoaded");
		$property->setAccessible(true);
		$property->setValue($this, false);
    }
}
