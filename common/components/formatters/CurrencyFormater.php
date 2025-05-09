<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components\formatters;

use yii\i18n\Formatter;

/**
 * Description of CurrencyFormater
 *
 */
class CurrencyFormater extends Formatter
{
	public $postfix = false;
	
	public function asCurrency($value, $currency = null, $options = [], $textOptions = [])
    {    	
        if ($value === null) {
            return $this->nullDisplay;
        }
        $value = $this->normalizeNumericValue($value);

		if ($currency === null) {
			if ($this->currencyCode === null) {
				throw new InvalidConfigException('The default currency code for the formatter is not defined and the php intl extension is not installed which could take the default currency from the locale.');
			}
			$currency = $this->currencyCode;
		}

		if ($this->postfix) {
			return trim(trim($this->asDecimal($value, 2, $options, $textOptions)), $this->decimalSeparator) . ' ' . $currency;
		}

		return $currency . ' ' . trim(trim($this->asDecimal($value, 2, $options, $textOptions)), $this->decimalSeparator);
    }
}
