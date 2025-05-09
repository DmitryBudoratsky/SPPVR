<?php

namespace common\components\helpers;

class SiteHelper
{   
    /**
     * Преобразование из пуникода
     * @param string $siteName
     * @return string
     */
    public static function punycodeDecode($siteName)
    {
    	/**
    	 * @var \idna_convert idn
    	 */
    	$idn = new \idna_convert(['idn_version' => 2008]);
    	$decodeSiteName = $idn->decode($siteName);
    	$protocolCount = substr_count($decodeSiteName, 'http');
    	if ($protocolCount == 0) {
    		$decodeSiteName = 'http://' . $decodeSiteName;
    	}
    	 
    	return $decodeSiteName;
    }
}
