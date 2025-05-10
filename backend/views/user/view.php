<?php

use backend\compenents\helpers\widgets\FancyboxHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\helpers\TypeHelper;
use common\models\db\User;
use yii\db\ActiveQuery;
use common\models\db\PushToken;

/* @var $this yii\web\View */
/* @var $model common\models\db\User */


$this->registerJsFile("@web/js/test-deeplink.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$userRealName = $model->name;
$this->title = empty($userRealName) ? 'Пользователь №' . $model->primaryKey : $userRealName;
$this->params['breadcrumbs'][] = ['label' => 'Все пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($model->status != User::STATUS_DELETED) { ?>
            <?= Html::a('Обновить', ['update', 'id' => $model->primaryKey], ['class' => 'btn btn-primary']) ?>

            <?= Html::a('Удалить', ['delete', 'id' => $model->primaryKey], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php } ?>

        <?php if ($model->isUserActive() && $model->isUserDefault()) { ?>
            <?= Html::a('Заблокировать', ['block', 'id' => $model->primaryKey], [
                'class' => 'btn btn-warning',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите заблокировать пользователя?',
                    'method' => 'post'
                ],
            ]);
            ?>
        <?php } ?>

        <?php if ($model->isUserBlocked() && $model->isUserDefault()) { ?>
            <?= Html::a('Разблокировать', ['unblock', 'id' => $model->primaryKey], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите разблокировать пользователя?',
                    'method' => 'post'
                ],
            ])
            ?>
        <?php } ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'userId',
            'name',
            'lastname',

            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return TypeHelper::getTypeLabelByModel($model, 'status');
                },
            ],

            [
                'attribute' => 'role',
                'value' => function ($model) {
                    return TypeHelper::getTypeLabelByModel($model, 'role');
                },
            ],

            'email:email',

            'createdAt:datetime',
            'updatedAt:datetime',
        ],
    ]) ?>

</div>
