<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\db\User */
/* @var $fileModel common\models\db\File */

$this->title = 'Добавить нового пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Все пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'fileModel' => $fileModel,
    ]) ?>

</div>
