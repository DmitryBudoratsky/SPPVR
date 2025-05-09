<?php

namespace common\components\helpers;


class YouTubeHelper
{  
	const DEFAULT_SIZE = 'default';
	const MQDEFAULT_SIZE = 'mqdefault';
	const HQDEFAULT_SZIE = 'hqdefault';
	
	public static function preview($url, $sizeType)
	{
		if (empty($url)) {
			return;
		}
		
		$pos = strpos($url, '=');
		$id = substr($url, $pos + 1);
		
		$previewUrl = '//img.youtube.com/vi/' . $id . '/' . $sizeType . '.jpg';
		
		return $previewUrl;
	}
}