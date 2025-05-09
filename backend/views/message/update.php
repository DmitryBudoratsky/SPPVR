<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\Message */

$this->title = 'Update Message: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->messageId, 'url' => ['view', 'id' => $model->messageId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="message-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
