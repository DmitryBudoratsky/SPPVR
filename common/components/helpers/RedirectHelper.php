<?php

namespace common\components\helpers;

class RedirectHelper
{
	/**
	 * Получение ссылки для редиректа
	 * @param string $action Название action
	 * @param integer $id ID страницы
	 * @param string $pathToAnotherEssence Путь до action
	 */
    public static function getRedirectLink($action, $id = null, $pathToAnotherEssence = false)
    {
    	$link = empty($pathToAnotherEssence) ? [$action, 'id' => $id] : ["$pathToAnotherEssence/$action", 'id' => $id];  	
    	return $link;
    }
}
