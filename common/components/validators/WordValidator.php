<?php

namespace common\components\validators;


use yii\validators\Validator;
use common\models\db\StopWord;
use yii\db\ActiveQuery;

/**
 * Description of WordValidator
 */
class WordValidator extends Validator
{
	public $validationType;
	
	/**
	 * Проверка разных типов валидации.
	 * 
	 * @param \yii\db\ActiveRecord $model
	 * @param string $attribute
	 */
    public function validateAttribute($model, $attribute)
    {		
        $checkedAttributeName = null;
    	if (!empty($model->attributeLabels()[$attribute])) {
    		$checkedAttributeName = $model->attributeLabels()[$attribute];
    	}
    	  	
    	if ($this->validationType == StopWord::VALIDATION_TYPE) { 		 
    		$stopWordsString = $this->getStopWordString($model, $attribute);	
    		if ($stopWordsString != '') {
    			$errorText = (!empty($checkedAttributeName)) 
    				? 'В поле ' .  '"' . $checkedAttributeName . '"' . ' используются запрещенные слова: ' . $stopWordsString
    				: "Используются запрещенные слова: ' . $stopWordsString";
    			
    			$model->addError($attribute, $errorText);
    		}	
    	}  
    }
    
    /**
     * Получить строку со стоп-словами.
     * @return string
     */
    private function getStopWordString($model, $attribute)
    {
    	// only letters
    	$lettersString = mb_ereg_replace("[^A-Za-zА-Яа-я]", "", $model->$attribute);	     	
    	$lowerLettersString = mb_strtolower($lettersString);
    	
    	/**
    	 * @var ActiveQuery $stopWordsQuery
    	 */
    	$stopWordsQuery = StopWord::find();
    	$stopWordsString = '';
    	foreach (StopWord::find()->each() as /** @var StopWord $stopWord*/ $stopWord) {
    		$lowerStopWord = mb_strtolower($stopWord->word);
    		$coincidencesCount = mb_substr_count($lowerLettersString, $lowerStopWord);
    		if ($coincidencesCount > 0) {
    			$stopWordsString  .= '"' . $stopWord->word . '";';
    		}
    	}
    	
    	return $stopWordsString;
    }
}