<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace common\components\helpers;


class StripTagsHelper
{
	public static function removeTags($text)
	{
		$search = array (
			"'<script[^>]*?>.*?</script>'si",  // Вырезает javaScript 
		    "'<[\/\!]*?[^<>]*?>'si",           // Вырезает HTML-теги 
		    "'([\r\n])[\s]+'",                 // Вырезает пробельные символы 
		    "'&#(\d+);'");                     // интерпретировать как php-код 
		 
		$replace = array (
			"", 
			"", 
		    "", 	  
		    "chr(\\1)"
		); 
		
		$formattedText = $text;
		
		for ($i = 0;$i < count($search);$i++) {
			$formattedText = preg_replace_callback($search[$i], function() use ($replace, $i) {
				return $replace[$i];
			}, $formattedText);
		}
			
		$formattedText = strip_tags($formattedText);
		$formattedText = html_entity_decode($formattedText);
		
		return $formattedText;
	}
}