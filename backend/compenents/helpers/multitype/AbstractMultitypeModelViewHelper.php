<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\compenents\helpers\multitype;

/**
 * Description of AbstractMultitypeModelViewHelper
 *
 */
abstract class AbstractMultitypeModelViewHelper
{
	abstract static public function getNewModelLabels();
	abstract static public function getModelLabels();
	abstract static public function getModelsLabels();
	abstract static public function getModelsLabelsForOneObject();

	/**
	 * 
	 * @param int $type
	 * @return string
	 */
	static public function getModelLabel($type)
	{
		if (array_key_exists($type, static::getModelLabels())) {
			return static::getModelLabels()[$type];
		}
		return '';
	}
	
	/**
	 * 
	 * @param int $type
	 * @return string
	 */
	static public function getModelsLabelForOneObject($type)
	{
		if (array_key_exists($type, static::getModelsLabelsForOneObject())) {
			return static::getModelsLabelsForOneObject()[$type];
		}
		return '';
	}
	
	/**
	 * 
	 * @param int $type
	 * @return string
	 */
	static public function getModelsLabel($type)
	{
		if (array_key_exists($type, static::getModelsLabels())) {
			return static::getModelsLabels()[$type];
		}
		return '';
	}
	
	/**
	 * 
	 * @param int $type
	 * @return string
	 */
	static public function getNewModelLabel($type)
	{
		if (array_key_exists($type, static::getNewModelLabels())) {
			return static::getNewModelLabels()[$type];
		}
		return '';
	}
}
