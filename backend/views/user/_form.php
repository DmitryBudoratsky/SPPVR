<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use common\models\db\User;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\db\User */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $fileModel common\models\db\File */
?>

<div class="base-user-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->errorSummary($model); ?>
	
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'status')->widget(Select2::classname(), [
				'data' => User::statusLabels(),
				'options' => [
					'id'=>'status',
					'placeholder' => 'Выберите статус...'
				],
				'pluginOptions' => [
					'allowClear' => true,

				]
	]);?>
	
	<?= $form->field($model, 'role')->widget(Select2::classname(), [
				'data' => User::roleLabels(),
				'options' => [
					'id'=>'role',
					'placeholder' => 'Выберите роль...'
				],
				'pluginOptions' => [
					'allowClear' => true,

				]
	]);?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
	
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
