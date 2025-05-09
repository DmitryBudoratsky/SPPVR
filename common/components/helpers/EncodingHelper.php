<?php

namespace common\components\helpers;

class EncodingHelper
{
    /**
     * Изменить кодировку строки с CP1251 на UTF-8
     * @param $str
     * @return string
     */
    public static function encodingFromCP1251ToUTF8($str)
    {
        return iconv('CP1251', 'UTF-8', $str);
    }

    public static function convertToUtf8($content)
    {
        $charset = null;
        $encodings = [
            'UTF-8',
            'Windows-1251',
            'ISO-8859-5'
        ];
        foreach ($encodings as $checkedEcndoding) {
            if (mb_check_encoding($content, $checkedEcndoding)) {
                $charset = $checkedEcndoding;
                break;
            }
        }
        if (empty($charset)) {
            return $content;
        }
        $charset = trim(strtolower($charset));
        \Yii::info('Charset: ' . $charset);
        if ($charset == 'utf-8') {
            return $content;
        }
        $content = @mb_convert_encoding($content, 'utf-8', $charset);
        return $content;
    }

    private static function isKoi8R($content)
    {
        $encodedContent = @mb_convert_encoding(strip_tags($content), 'utf-8', 'Windows-1251');
        $lowercaseCharPattern = '/[а-я]{1}/';
        $allCharPattern = '/[а-яА-Я]{1}/';
        $matches = [];
        $lowercaseCharCount = preg_match_all($lowercaseCharPattern, $encodedContent, $matches);
        $allCharCount = preg_match_all($allCharPattern, $encodedContent, $matches);
        return (($allCharCount >= 100) ? ($lowercaseCharCount / $allCharCount) <= 0.75 : false);
    }
}