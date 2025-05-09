<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MessageSearch */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="message-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'messageId') ?>

    <?= $form->field($model, 'chatId') ?>

    <?= $form->field($model, 'senderUserId') ?>

    <?= $form->field($model, 'text') ?>

    <?= $form->field($model, 'isAutoMessage') ?>
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
