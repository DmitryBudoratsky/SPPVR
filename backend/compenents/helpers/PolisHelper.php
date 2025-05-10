<?php

namespace backend\compenents\helpers;

use yii\web\View;

class PolisHelper
{
    const HTML_INPUT_CLASS = 'polis-mask';
    const MASK = '9999999999999999';

    public static function registerJs(View $view)
    {
        $class = self::HTML_INPUT_CLASS;
        $mask = self::MASK;

        $view->registerJs("
            $('.$class').mask('$mask', {autoclear: false});
        ");
    }
}
