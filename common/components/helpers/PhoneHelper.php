<?php

namespace common\components\helpers;


class PhoneHelper
{
	/**
	 * Проверить, является ли номер телефона российским
	 * @param string $phone
	 * @return boolean
	 */
    public static function checkPhoneToRusNumber($phone)
    {
        \Yii::trace("Checked phone: {$phone}");
        if ($phone < 6) {
            return false;
        }
        $isRusNumber = false;
        if (($phone[0] == "+") && ($phone[1] == "7") && ($phone[2] == "9")) {
            $isRusNumber = true;
            \Yii::trace("Starts from +79..");
        } else if (($phone[0] == "8") && ($phone[1] == "9")) {
            $isRusNumber = true;
            \Yii::trace("Starts from 89..");
        }

    	\Yii::trace("{$phone} is russian: " . var_export($isRusNumber, true));

        return $isRusNumber;
    }
}
