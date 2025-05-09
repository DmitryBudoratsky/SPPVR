<?php

namespace common\components\validators;


use yii\validators\Validator;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PasswordValidator
 */
class PasswordValidator extends Validator
{
	public function init()
    {
        parent::init();
        $this->message = 'Пароль не может содержать имя учетной записи пользователя.';
    }

	/**
	 * @param type $attribute
	 * @param type $params
	 */
    public function validateAttribute($model, $attribute)
    { 	
    	if (!empty($model->login)) {
    		if(stristr($model->$attribute, $model->login) !== FALSE) {
    			$model->addError($attribute, $this->message);
    		}
    	}
    }
}