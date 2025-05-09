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

    <?= FancyboxHelper::renderFancybox(); ?>
    <?php Pjax::begin(['id' => 'my_pjax']); ?>

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
                'label' => 'Аватар',
                'format' => 'raw',
                'value' => function (/** @var User $model */ $model, $key) {
                    if (empty($model->avatarFile)) {
                        return null;
                    }
                    return Html::a(Html::img($model->avatarFile->getPreviewImageUrl(), ['class' => 'previewImage']), $model->avatarFile->getAbsoluteFileUrl(), ['data-fancybox' => true]);

                },
            ],

            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (/** @var User $model */ $model) {
                    return (!empty($model->name)) ? Html::a($model->name, ['user/view', 'id' => $model->userId]) : '';
                },
            ],

            [
                'attribute' => 'lastname',
                'format' => 'raw',
                'value' => function (/** @var User $model */ $model) {
                    return (!empty($model->lastname)) ? Html::a($model->lastname, ['user/view', 'id' => $model->userId]) : '';
                },
            ],

            [
                'attribute' => 'login',
                'format' => 'raw',
                'value' => function (/** @var User $model */ $model) {
                    return (!empty($model->login)) ? Html::a($model->login, ['user/view', 'id' => $model->userId]) : '';
                },
            ],

            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return TypeHelper::getTypeLabelByModel($model, 'status');
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
//						'multiple' => true
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
            ],

            'email:email',

            [
                'attribute' => 'isEmailConfirmed',
                'value' => function ($model) {
                    return TypeHelper::getTypeLabelByModel($model, 'isEmailConfirmed');
                },
                'filter' => User::isEmailConfirmedLabels()
            ],

            [
                'attribute' => 'phone',
                'contentOptions' => ['style' => 'width: 9%;'],
            ],

            [
                'label' => 'Статус профиля',
                'format' => 'raw',
                'value' => function (User $model) {
                    return Html::tag('span',
                        $model->isDeleted ? "Удален" : "Активен",
                        [
                            'class' => 'badge badge-' . ($model->isDeleted ? 'danger' : 'success')
                        ]);
                },
            ],

            [
                'attribute' => 'createdAt',
                'contentOptions' => ['style' => 'width: 8%;'],
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
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash-alt"></i>', false, [
                            'class' => 'pjax-delete-link',
                            'delete-url' => $url,
                            'pjax-container' => 'my_pjax',
                            'title' => Yii::t('yii', 'Delete')
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>