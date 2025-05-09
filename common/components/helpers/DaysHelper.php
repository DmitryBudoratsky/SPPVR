<?php

namespace common\components\helpers;

class DaysHelper
{
	const MONDAY_LABEl 		= "понедельник";
	const TUESDAY_LABEl		= "вторник";
	const WEDNESDAY_LABEl 	= "среда";
	const THURSDAY_LABEl 	= "четверг";
	const FRIDAY_LABEl 		= "пятница";
	const SATURDAY_LABEl 	= "суббота";
	const SUNDAY_LABEl 		= "воскресенье";
	
	public static function getDayLabels()
	{		
		$dayLabelsArr = [
			1 => self::MONDAY_LABEl,
			2 => self::TUESDAY_LABEl,
			3 => self::WEDNESDAY_LABEl,
			4 => self::THURSDAY_LABEl,
			5 => self::FRIDAY_LABEl,
			6 => self::SATURDAY_LABEl,
			7 => self::SUNDAY_LABEl
		];
		
		return $dayLabelsArr;
	}
}    