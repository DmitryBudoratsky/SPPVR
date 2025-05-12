<?php

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
            <?= Html::a('Скачать файл', ['upload-file', 'id' => $model->incidentId], ['class' => 'btn btn-info']) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'incidentId',

            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function (Incident $model) {
                    return Html::tag('h5',
                        Html::tag('span',
                            Incident::statusLabels()[$model->status],
                            [
                                'class' => 'badge badge-' . ($model->status == Incident::STATUS_FINISHED ? 'info' : 'success')
                            ]
                        )
                    );
                }
            ],

            'patientName',

            [
                'attribute' => 'birthDate',
                'format' => ['date', 'php:d.m.Y']
            ],

            'policy',
            'snils',
            'address',
            'anamnesis',

            [
                'attribute' => 'verdict',
                'visible' => $model->status == Incident::STATUS_FINISHED
            ],

            [
                'attribute' => 'createdAt',
                'format' => ['date', 'php:d.m.Y H:i']
            ],

            [
                'attribute' => 'updatedAt',
                'format' => ['date', 'php:d.m.Y H:i']
            ],
        ],
    ]) ?>

    <p>
        <?php if (empty($model->chatId) && $model->status == Incident::STATUS_CREATED): ?>
            <?= Html::a('Начать чат', ['update', 'id' => $model->incidentId], ['class' => 'btn btn-success btn-lg btn-block']) ?>
        <?php else: ?>
            <?= '' ?>
        <?php endif; ?>
    </p>
</div>
