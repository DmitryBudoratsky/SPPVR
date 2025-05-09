<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace common\components\helpers;

/**
 * Description of StringHelper
 *
 */
class StringHelper
{
	public static function truncate($string, $maxLength)
	{	
		return (mb_strlen($string) > $maxLength) ? mb_substr($string, 0, $maxLength - 3) . '...' : $string;
	}

    /**
     * Generate and return a random characters string
     *
     * Useful for generating passwords or hashes.
     *
     * The default string returned is 8 alphanumeric characters string.
     *
     * The type of string returned can be changed with the "type" parameter.
     * Seven types are - by default - available: basic, alpha, alphanum, num, nozero, unique and md5.
     *
     * @param   string  $type    Type of random string.  basic, alpha, alphanum, num, nozero, unique and md5.
     * @param   integer $length  Length of the string to be generated, Default: 8 characters long.
     * @return  string
     */
    public static function  random_str($type = 'alphanum', $length = 8)
    {
        switch($type)
        {
            case 'basic'    : return mt_rand();
                break;
            case 'alpha'    :
            case 'alphanum' :
            case 'num'      :
            case 'nozero'   :
                $seedings             = array();
                $seedings['alpha']    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $seedings['alphanum'] = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $seedings['num']      = '0123456789';
                $seedings['nozero']   = '123456789';

                $pool = $seedings[$type];

                $str = '';
                for ($i=0; $i < $length; $i++)
                {
                    $str .= substr($pool, mt_rand(0, mb_strlen($pool) -1), 1);
                }
                return $str;
                break;
            case 'unique'   :
            case 'md5'      :
                return md5(uniqid(mt_rand()));
                break;
        }
    }

    /**
     * Замена первого вхождения подстроки в строку
     * @param string $search
     * @param string $replace
     * @param string $text
     * @return mixed
     */
    public static function str_replace_once($search, $replace, $text) {
        return preg_replace('~' . preg_quote($search) . '~', $replace, $text, 1);
    }

    public static function getShortTextWithoutTags(string $text): string
    {
        $noTagsText = StripTagsHelper::removeTags($text);
        return StringHelper::truncate($noTagsText, 150);
    }
}
