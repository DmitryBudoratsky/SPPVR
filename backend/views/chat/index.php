<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\db\Chat;
use kartik\daterange\DateRangePicker;
use common\models\db\Message;
use common\components\helpers\TypeHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ChatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $type integer */

$this->title = 'Переписки';
$this->params['breadcrumbs'][] = $this->title;

$actionColumnTemplate = ($type == Chat::TYPE_GROUP_CHAT) ? '{view} {update} {delete}' : '{view} {delete}';

$referrer = Yii::$app->request->referrer;
if (empty($referrer) || !strpos($referrer, '/chat/index')) {
    $referrer = Yii::$app->request->getBaseUrl() . '/chat/index';
}

?>
<div class="chat-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	<? if ($type == Chat::TYPE_GROUP_CHAT) : ?>
        	<?= Html::a('Создать групповой чат', ['create'], ['class' => 'btn btn-success']) ?>
        <? endif; ?>
    </p>

        <?php Pjax::begin(['id' => 'my_pjax']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'chatId',
                'format' => 'raw',
                'value' => static function (Chat $model) {
                    return Html::a($model->chatId, ['chat/view', 'id' => $model->chatId]);
                }
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => static function (Chat $model) {
                    return Html::a($model->getTitle(), ['chat/view', 'id' => $model->chatId]);
                }
            ],

            [
                'attribute' => 'isPublic',
                'format' => 'boolean'
            ],

			'messageCount',
			'chatMemberCount',

            [
                'label' => 'Последнее сообщение',
                'format' => 'raw',
                'value' => static function (Chat $chat) {
                    return $chat->getLastMessageText();
                }
            ],

        	[
        		'attribute' => 'createdAt',
                'format' => 'datetime',
        		'filter' => DateRangePicker::widget([
        			'model' => $searchModel,
        			'attribute' => 'createdAtRange',
        			'convertFormat' => true,
        			'pluginOptions' => [
        				'opens'=>'right',
        				'locale' => [
        					'cancelLabel' => 'Закрыть',
        					'format' => 'Y-m-d ',
        				]
        		    ],
                ]),
        	],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 4%;'],
                'template' => $actionColumnTemplate,
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
    <?php Pjax::end(); ?>

</div>



