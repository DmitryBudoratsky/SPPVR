<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Messages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Message', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

        <?php Pjax::begin(['id' => 'my_pjax']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'messageId',
            'chatId',
            'senderUserId',
            'text:ntext',
            'isAutoMessage',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 4%;'],
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash-alt"></i>', false, [
                            'class' => 'pjax-delete-link',
                            'delete-url' => $url,
                            'pjax-container' => 'my_pjax',
                            'title' => Yii::t('yii', 'Delete')
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
