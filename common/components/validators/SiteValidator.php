<?php

namespace common\components\validators;


use yii\validators\Validator;


/**
 * Description of SiteValidator
 */
class SiteValidator extends Validator
{
	public function init()
    {
        parent::init();
        $this->message = 'Вы ввели неправильное название сайта.';
    }

	/**
	 * @param type $attribute
	 * @param type $params
	 */
    public function validateAttribute($model, $attribute)
    {    	
    	$result = stristr($model->$attribute, '.');
    	if ($result == "") {
    		$model->addError($attribute, $this->message);
    	}
    }
}