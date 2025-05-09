<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\db\Chat */

$this->title = 'Добавить переписку';
$this->params['breadcrumbs'][] = ['label' => 'Переписки', 'url' => ['index', 'type' => $model->type]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    	'fileModel' => $fileModel,
    	'chatMemberForm' => $chatMemberForm
    ]) ?>

</div>
