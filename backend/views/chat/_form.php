<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use common\models\db\Chat;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\db\User;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\db\Chat */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="chat-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'title')->textInput() ?>
	
	<?= $form->field($fileModel, 'fileId')->fileInput(['accept' => 'image/*'])->label('Файл') ?>

    <?= $form->field($model, 'isPublic')->dropDownList(Chat::isPublicLabels()) ?>
    
    <? if ($model->isGroupChat()) : ?>
	    <?
		    // The controller action that will render the list
		    $url = \yii\helpers\Url::toRoute(['user/user-list', 'chatId' => $model->chatId]);
		    echo $form->field($chatMemberForm, 'userIds')->widget(Select2::classname(), [
		    	'data' => ArrayHelper::map(User::find()->all(), 'userId', 'name'),
		    	'options' => [
		    		'placeholder' => 'Выберите пользователей ...',
		    		'multiple' => true
		    	],
		    	'pluginOptions' => [
		    		'allowClear' => true,
		    		'language' => [
		    			'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
		    		],
		    		'ajax' => [
		    			'url' => $url,
		    		],
		    		'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
		    		'templateResult' => new JsExpression('function(user) { return user.text; }'),
		    		'templateSelection' => new JsExpression('function (user) { return user.text; }'),
		    	],
		    	'showToggleAll' => false,
		    ]);
		?>
	<? endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
