<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\db\BankCard */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\web\View;
use yii\widgets\MaskedInput;
use frontend\models\BankCardAttachForm;

$this->title = \Yii::t('app', 'Привязка банковской карты');
?>

<div class="attach-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['enableClientValidation' => false]) ?>

            <br>
            <br>


            <div class="row-sm" style="margin-left: 15px">
                <?= $form->field($model, 'cardNumber')->widget(MaskedInput::className(), [
                    'mask' => '9999-9999-9999-9999[-999]',
                ]) ?>
            </div>
            <div class="row-sm">
                <div class="col-xs-6" style="width: 200px">
                    <?= $form->field($model, 'expiredMonth')->widget(\kartik\select2\Select2::className(), [
                        'hideSearch' => true,
                        'data' => BankCardAttachForm::getMontsArray(),
                    ]) ?>
                </div>
                <div class="col-xs-4"  style="width: 200px; margin-top: 5px">
                    <?= $form->field($model, 'expiredYear')->widget(\kartik\select2\Select2::className(), [
                        'hideSearch' => true,
                        'data' => BankCardAttachForm::getYearArray(),
                    ])->label('') ?>
                </div>
            </div>

            <br>
            <br>

        </div>
    </div>
    <div class="form-group" style="margin-left: 15px">
        <?= Html::submitButton(\Yii::t('app', 'Добавить'), ['class' => 'btn btn-lg btn-primary', 'name' => 'request-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>