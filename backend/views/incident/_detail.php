<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\db\Incident;

/* @var $this yii\web\View */
/* @var $model Incident */
?>
<div class="incident-view">
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
                'attribute' => 'verdictAt',
                'format' => ['date', 'php:d.m.Y H:i'],
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
</div>
