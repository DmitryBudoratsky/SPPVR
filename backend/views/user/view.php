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
        <?= Html::a('Обновить', ['update', 'id' => $model->primaryKey], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->primaryKey], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>

        <?= Html::button(
            "Открыть приложение",
            [
                'id'=> 'openApp',
                'class'=> 'btn btn-success',
            ]
        ) ?>
  
        <? if ($model->isUserActive() && $model->isUserDefault()) { ?>
        	<?= Html::a('Заблокировать', ['block', 'id' => $model->primaryKey], [
        			'class' => 'btn btn-warning',
        			'data' => [
        				'confirm' => 'Вы уверены, что хотите заблокировать пользователя?',
        				'method' => 'post'
        			],     		
        		]); 
        	?>
        <? } ?>
        
		<? if ($model->isUserBlocked() && $model->isUserDefault()) { ?>	 
        	<?= Html::a('Разблокировать', ['unblock', 'id' => $model->primaryKey], [
        			'class' => 'btn btn-success',
        			'data' => [
						'confirm' => 'Вы уверены, что хотите разблокировать пользователя?',
        				'method' => 'post'
        			],
        		]) 
        	?> 
        <? } ?>


        <?= Html::a('Автомобили', ['/vehicle', 'VehicleSearch[userId]' => $model->primaryKey], ['class' => 'btn btn-info']); ?>

	</p>


    <?= FancyboxHelper::renderFancybox(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'userId',
            'name',
            'lastname',
            'login',
        	
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
            
            [
            	'attribute' => 'isEmailConfirmed',
            	'value' => function ($model) {
            		return TypeHelper::getTypeLabelByModel($model, 'isEmailConfirmed');
            	},
    		],
    		
            'phone',
        	
      		[
	        	'attribute' => 'avatarFileId',
	        	'format' => 'raw',
                'value' => function (User $model) {
                    if (empty($model->avatarFile)) {
                        return null;
                    }

                    return Html::a(Html::img($model->avatarFile->getPreviewImageUrl(), ['class' => 'previewImage']), $model->avatarFile->getAbsoluteFileUrl(), ['rel' => 'fancybox', 'class' => 'previewImage']);

                }
        	],

            'vkUserId',
            'facebookUserId',
            'twitterUserId',
            'instagramUserId',

            [
                'label' => 'Push токены',
                'format' => 'html',
                'value' => function(/** @var User $model */ $model) {
                    $pushTokenString = '';
                    $tokenArray = $model->getPushTokens()->select('pushToken.token')->asArray()->column();
                    if (!empty($tokenArray)) {
                        $pushTokenString = implode(',<br />', $tokenArray);
                    }
                    return $pushTokenString;
                }
            ],

            [
                'label' => 'Координаты',
                'value' => function (/** @var User $model */ $model) {
                    return !empty($model->userGeoPosition) ? $model->userGeoPosition->latitude . ' ' . $model->userGeoPosition->longitude : ' ';
                },
            ],

            'notificationsEnabled:boolean',

            'createdAt:datetime',
            'updatedAt:datetime',
        ],
    ]) ?>

</div>
