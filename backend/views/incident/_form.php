<?php

use backend\compenents\helpers\PolisHelper;
use backend\compenents\helpers\SnilsHelper;
use common\models\db\Incident;
use common\models\db\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\Incident */
/* @var $form yii\widgets\ActiveForm */
PolisHelper::registerJs($this);
SnilsHelper::registerJs($this);
?>

<div class="incident-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'patientName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sex')->radioList(Incident::sexLabels(), ['inline'=>true])?>

    <?= $form->field($model, 'birthDateString')->widget(\kartik\date\DatePicker::class, [
        'readonly' => true,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy'
        ]
    ]) ?>

    <?= $form->field($model, 'policy')->textInput(['class' => 'form-control ' . PolisHelper::HTML_INPUT_CLASS]) ?>

    <?= $form->field($model, 'snils')->textInput(['class' => 'form-control ' . SnilsHelper::HTML_INPUT_CLASS]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'anamnesis')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
