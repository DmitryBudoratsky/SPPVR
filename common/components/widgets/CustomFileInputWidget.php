<?php


namespace common\components\widgets;


use kartik\widgets\FileInput;
use yii\base\Model;
use yii\base\Widget;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

class CustomFileInputWidget extends Widget
{
    /**
     * @var ActiveForm $form
     */
    public $form;
    /**
     * @var Model $model
     */
    public $model;


    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $result = '';

//        FileInput::widget(['accept' => 'image/*', 'required' => true])

        $result .= $this->form->field($this->model, 'fileObject')->fileInput(['accept' => 'image/*', 'required' => true])->label('Изображение');
        $result .= $this->form->field($this->model, 'fileObject')->textInput(['type' => 'url']);

        return $result;
    }
}