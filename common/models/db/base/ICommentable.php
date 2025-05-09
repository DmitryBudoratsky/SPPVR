<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\db\base;

/**
 *
 */
interface ICommentable
{
	/**
	 * Возвращает тип объекта
	 * 
	 * @return string Object name
	 */
	static public function getObjectType();
	
	/**
	 * Возвращает имя объекта
	 * 
	 * @return string Object name
	 */
	public function getObjectName();
	
	/**
	 * Вызывается при любом изменении комментариев
	 */
    public function onCommentsUpdated();
	
	/**
	 * Возвращает список комментариев
	 * 
	 * @return \common\models\db\Comment[] Комментарии
	 */
	public function getComments();
	
	/**
	 * Возвращает название поля названия
	 * 
	 * @return \common\models\db\Comment[] Комментарии
	 */
	static public function getTitleFieldName();
	
	/**
	 * Сериализация сущности
	 * @return []
	 */
	public function serializeToArray();
}
