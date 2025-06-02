<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\db\Incident;

/* @var $this yii\web\View */
/* @var $model Incident */

$this->title = 'Случай №' . $model->incidentId;
$this->params['breadcrumbs'][] = ['label' => 'Список случаев', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="incident-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($model->status == Incident::STATUS_CREATED): ?>

            <?= Html::a('Редактировать', ['update', 'id' => $model->incidentId], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Вынести вердикт', ['write-verdict', 'id' => $model->incidentId], ['class' => 'btn btn-info']) ?>

        <?php else: ?>

            <?= Html::beginTag('div', [
                'class' => "btn-group dropright",
            ]); ?>

            <?= Html::button('Скачать файлом', [
                'type' => 'button',
                'class' => 'btn btn-info dropdown-toggle',
                'data-toggle' => 'dropdown',
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false'
            ]); ?>

            <?= Html::beginTag('div', [
                'class' => "dropdown-menu",
            ]); ?>
            <?= Html::a('PDF', ['download-as-file', 'id' => $model->incidentId, 'extension' => 'pdf'], ['class' => 'dropdown-item']) ?>
            <?= Html::tag('div', null, ['class' => "dropdown-divider"]); ?>
            <?= Html::a('CSV', ['download-as-file', 'id' => $model->incidentId, 'extension' => 'csv'], ['class' => 'dropdown-item']) ?>
            <?= Html::tag('div', null, ['class' => "dropdown-divider"]); ?>
            <?= Html::a('XLS', ['download-as-file', 'id' => $model->incidentId, 'extension' => 'xls'], ['class' => 'dropdown-item']) ?>
            <?= Html::tag('div', null, ['class' => "dropdown-divider"]); ?>
            <?= Html::a('JSON', ['download-as-file', 'id' => $model->incidentId, 'extension' => 'json'], ['class' => 'dropdown-item']) ?>
            <?= Html::endTag('div'); ?>

            <?= Html::endTag('div'); ?>
        <?php endif; ?>
    </p>

    <?= $this->render('_detail', ['model' => $model]) ?>

    <p>
        <?php if (empty($model->chatId) && $model->status == Incident::STATUS_CREATED): ?>
            <?= Html::a('Начать чат', ['start-chat', 'id' => $model->incidentId], ['class' => 'btn btn-success btn-lg btn-block']) ?>
        <?php else: ?>
            <?= $this->render('/chat/_view', ['model' => $model->chat, 'hideForm' => $model->status == Incident::STATUS_FINISHED]) ?>
        <?php endif; ?>
    </p>
</div>
