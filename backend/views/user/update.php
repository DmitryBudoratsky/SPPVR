<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\User */
/* @var $fileModel common\models\db\File */

$this->title = 'Обновить пользователя: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Все пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->userId]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="base-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'fileModel' => $fileModel,
    ]) ?>

</div>
