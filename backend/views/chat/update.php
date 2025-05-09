<?php

use common\components\helpers\ChatHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\Chat */

$this->title = 'Обновить переписку №' . $model->chatId;
$this->params['breadcrumbs'][] = ['label' => 'Переписки', 'url' => ['index', 'type' => $model->type]];
$this->params['breadcrumbs'][] = ['label' => ChatHelper::formatChatName($model), 'url' => ['chat/view', 'id' => $model->chatId]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="chat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    	'fileModel' => $fileModel,
    	'chatMemberForm' => $chatMemberForm
    ]) ?>

</div>
