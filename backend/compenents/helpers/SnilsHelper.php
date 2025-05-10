<?php

namespace backend\compenents\helpers;

use yii\web\View;

class SnilsHelper
{
    const HTML_INPUT_CLASS = 'snils-mask';
    const MASK = '999-999-999 99';

    public static function registerJs(View $view)
    {
        $class = self::HTML_INPUT_CLASS;
        $mask = self::MASK;

        $view->registerJs("
            $('.$class').mask('$mask', {autoclear: false});
        ");
    }
}
