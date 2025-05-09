<?php

namespace common\components\validators;


use yii\validators\Validator;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginValidator
 */
class LoginValidator extends Validator
{
	public function init()
    {
        parent::init();
        $this->message = 'Неправильный формат поля "Логин".';
    }

	/**
	 * @param type $attribute
	 * @param type $params
	 */
    public function validateAttribute($model, $attribute)
    {  	
		if (!preg_match("/^[a-z][-a-z_0-9]*$/i", $model->$attribute)) {
			$model->addError($attribute, $this->message);
		}
    }
}