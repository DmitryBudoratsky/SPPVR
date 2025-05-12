<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\Incident */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Вынесение вердикта';
$this->params['breadcrumbs'][] = ['label' => 'Список случаев', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Случай №' . $model->incidentId, 'url' => ['view', 'id' => $model->incidentId]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="incident-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <p>
        <?= Html::textarea('verdict', $model->verdict, ['rows' => 6, 'class' => 'form-control']) ?>
    </p>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
