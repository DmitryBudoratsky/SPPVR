<?php

use backend\compenents\helpers\SnilsHelper;
use common\models\db\Incident;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\IncidentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список случаев';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="incident-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать случай', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
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
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' => Incident::statusLabels(),
                    'options' => [
                        'placeholder' => 'Статус'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
            ],

            'patientName',

            [
                'attribute' => 'birthDate',
                'format' => ['date', 'php:d.m.Y'],
                'options' => !empty($searchModel->birthDateRange) ? ['style' => 'min-width: 275px;'] : [],
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'birthDateRange',
                    'presetDropdown' => true,
                    'readonly' => true,
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'opens' => 'right',
                        'locale' => [
                            'cancelLabel' => 'Закрыть',
                            'format' => 'd.m.Y',
                        ]
                    ],
                ]),
            ],

            'policy',
            'snils',

            [
                'attribute' => 'createdAt',
                'format' => ['date', 'php:d.m.Y H:i'],
                'options' => !empty($searchModel->createdAtRange) ? ['style' => 'min-width: 275px;'] : [],
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'createdAtRange',
                    'presetDropdown' => true,
                    'readonly' => true,
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'opens' => 'right',
                        'locale' => [
                            'cancelLabel' => 'Закрыть',
                            'format' => 'd.m.Y',
                        ]
                    ],
                ]),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
    ]); ?>
</div>
