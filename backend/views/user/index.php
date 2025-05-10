<?php


use backend\compenents\helpers\widgets\FancyboxHelper;
use common\components\helpers\TypeHelper;
use common\models\db\Settings;
use common\models\db\User;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use newerton\fancybox\FancyBox;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

$referrer = Yii::$app->request->referrer;
if (empty($referrer) || !strpos($referrer, '/user/index')) {
    $referrer = Yii::$app->request->getBaseUrl() . '/user/index';
}
?>
<div class="base-user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить нового пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'userId',
                'contentOptions' => ['style' => 'width: 3%;'],
                'format' => 'raw',
                'value' => function (/** @var User $model */ $model) {
                    return Html::a($model->userId, ['user/view', 'id' => $model->userId]);
                },
            ],

            [
                'attribute' => 'name',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 10%;'],
                'value' => function (/** @var User $model */ $model) {
                    return (!empty($model->name)) ? Html::a($model->name, ['user/view', 'id' => $model->userId]) : '';
                },
            ],

            [
                'attribute' => 'lastname',
                'contentOptions' => ['style' => 'width: 10%;'],
                'format' => 'raw',
                'value' => function (/** @var User $model */ $model) {
                    return (!empty($model->lastname)) ? Html::a($model->lastname, ['user/view', 'id' => $model->userId]) : '';
                },
            ],

            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::tag('span',
                        User::statusLabels()[$model->status],
                        [
                            'class' => 'badge badge-' . ($model->isUserActive() ? 'success' : 'danger')
                        ]
                    );
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' => User::statusLabels(),
                    'options' => [
                        'placeholder' => 'Статус'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
            ],

            [
                'attribute' => 'role',
                'value' => function ($model) {
                    return TypeHelper::getTypeLabelByModel($model, 'role');
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'role',
                    'data' => User::roleLabels(),
                    'options' => [
                        'placeholder' => 'Роль',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
            ],

            'email:email',

            [
                'attribute' => 'createdAt',
                'format' => 'datetime',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'createdAtRange',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'opens' => 'right',
                        'locale' => [
                            'cancelLabel' => 'Закрыть',
                            'format' => 'Y-m-d ',
                        ]
                    ],
                ]),
            ],

            [
                'attribute' => 'updatedAt',
                'format' => 'datetime',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'createdAtRange',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'opens' => 'right',
                        'locale' => [
                            'cancelLabel' => 'Закрыть',
                            'format' => 'Y-m-d ',
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