<?php

namespace common\components\validators;


use yii\validators\Validator;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PhoneValidator
 */
class PhoneValidator extends Validator
{
	public function init()
    {
        parent::init();
        $this->message = 'Неправильный формат номера телефона.';
    }

    public function validateValue($value)
    {
        $value = self::normalizePhoneNumber($value);
        if (preg_match('/[^0-9]/', $value)) {
            return [$this->message, []];
        }

        return null;
    }

    /**
	 * Compare $phone with format [+]X XXX XXX XX XX
	 * 
	 * @param \yii\db\ActiveRecord $model
	 * @param string $attribute
	 */
    public function validateAttribute($model, $attribute)
    {	    	
		$availableLength = [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18];
		$phone = $model->$attribute;
		$normalizedPhone = PhoneValidator::normalizePhoneNumber($phone);
		\Yii::trace("Validated phone: {$phone}, normalizedPhone: {$normalizedPhone}");
		
		if (!ctype_digit($normalizedPhone) || !in_array(mb_strlen($normalizedPhone), $availableLength)) {
			$model->addError($attribute, $this->message);
		} else {
			$model->$attribute = self::formatPhone($phone);
		}
    }
    
	/**
	 * Форматирует номер телефона
	 * @param string $phone
	 * @return string
	 */
	public static function formatPhone($phone)
	{
		$phone = self::shortNormalizePhoneNumber($phone);
		$normalizedPhone = self::normalizePhoneNumber($phone);
		if (mb_strlen($normalizedPhone) == 11) {
			if (($phone[0] == 8) && (($phone[1] == 9) || ($phone[1] == 3) || ($phone[1] == 8))) {
				$phone[0] = 7;
			}
		}
		if ($phone[0] != "+") {
			\Yii::trace("Adding + for phone: {$phone}");
			$phone = "+" . $phone;
		}
		\Yii::trace("Formatted number: {$phone}");
		return $phone;
	}
    
    /**
     * Нормализует номер телефона к одному формату
     * @return string|number
     */
    public static function shortNormalizePhoneNumber($phone) 
    {
    	// во втором случае короткое тире - n-dash - Ctrl+"-"
        return str_replace(['-', '–', ' ', '(', ')'], '', $phone);
    }

    /**
     * Нормализует номер телефона к одному формату
     * @return string|number
     */
    public static function normalizePhoneNumber($phone) 
    {
    	// во втором случае короткое тире - n-dash - Ctrl+"-"
        return str_replace(['+'], '', self::shortNormalizePhoneNumber($phone));
    }
}