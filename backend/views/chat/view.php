<?php

use common\components\widgets\MessageGridViewWidget;
use common\models\db\User;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\helpers\TypeHelper;
use common\models\db\Settings;

/* @var $this yii\web\View */
/* @var $model common\models\db\Chat */

\backend\assets\common\SocketNotificationAsset::register($this);

$this->title = \common\components\helpers\ChatHelper::formatChatName($model);
$this->params['breadcrumbs'][] = ['label' => 'Переписки', 'url' => ['index', 'type' => $model->type]];
$this->params['breadcrumbs'][] = $this->title;

/** @var User $user */
$user = User::getUser();
if (!empty($user)) {
    $accessToken = urlencode(User::getUser()->getAccessToken());
    $webSocketAddress = Yii::$app->params['webSocketPublicAddress'];
}

?>
<div class="chat-view">

    <h1><?= $this->title ?></h1>

    <p>
        <?php
        if ($model->isGroupChat()) {
            echo Html::a('Обновить', ['update', 'id' => $model->chatId], ['class' => 'btn btn-primary']);
        }
        ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->chatId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Участники чата (' . $model->chatMemberCount . ')',
            ['//chat-member', 'id' => $model->chatId],
            ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'chatId',
            'title',
            [
                'attribute' => '	avatarFileId',
                'label' => 'Аватар',
                'format' => 'raw',
                'value' => (!empty($model->getImageUrl())) ? Html::img($model->getImageUrl(), ['alt' => 'user-avatar', 'class' => 'previewImage']) : ''
            ],
        	[
	        	'attribute' => 'type',
	        	'value' => function ($chat) {
	        		return TypeHelper::getTypeLabelByModel($chat, 'type');
	        	}
        	],

            'isHidden:boolean',

            [
                'attribute' => 'isPublic',
                'format' => 'boolean'
            ],

        	'messageCount',
        	'chatMemberCount',   	
            'createdAt:datetime',
            'updatedAt:datetime',
        ],
    ]) ?>
    
    <h2 id="messages">Сообщения (<?= $model->messageCount;?>)</h2>

    <div style="width: 100%; height: 100px;">
    <? if ($model->isGroupChat()) : ?>
        <?php
        $form = ActiveForm::begin([
            'id' => 'form-input-example',
            'options' => [
                'class' => 'form-horizontal col-lg-11',
                'enctype' => 'multipart/form-data'
            ],
        ]);
        ?>
        <?= $form->errorSummary($sendMessageModel) ?>

        <div class="row align-items-center">
            <div class="col-sm-7">
                <?=$form->field($sendMessageModel, 'text')->textarea(['placeholder' => 'Введите текст', 'rows' => 2])->label('Текст сообщения'); ?>
            </div>
            <div class="col-sm-3">
                <?=$form->field($sendMessageModel, 'file')->fileInput(['style' => ['padding' => '5px']])->label('Изображение'); ?>
            </div>
            <div class="col-sm-2">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-send-button']) ?>
            </div>
        </div>
        <? ActiveForm::end(); ?>
    <? endif; ?>
    </div>


    <div >
        <?= MessageGridViewWidget::widget(['dataProvider' => $messageDataProvider]) ?>
    </div>

    <input type="hidden" id="access-token" value=<?= $accessToken ?>>
    <input type="hidden" id="socket-address" value=<?= $webSocketAddress ?>>

</div>


<script>
    var accessToken = $('#access-token').val();
    var socketAddress = $('#socket-address').val();
    var chatId = Number(<?= $model->chatId ?>);

    var socketUrl = socketAddress + '/accessToken=' + accessToken;

    function handler(response) {

        if (response && response.data.chatId == chatId) {
            console.log('check chat');

            if (response.socketType === 'messageCreated') {
                console.log('messageCreated');

                var d = new Date(Number(response.data.createdAt) * 1000);
                var formattedDate = d.getFullYear() + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" +
                    ("0" + d.getDate()).slice(-2) + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2) + ":" + ("0" + d.getSeconds()).slice(-2);


                let messageItemInnerHtml = '[' + formattedDate + '] ';
                if (response.data.isSystem == 1) {
                    messageItemInnerHtml += response.data.text
                } else {

                    messageItemInnerHtml += '<a href="/admin/common/user/view?id=' +response.data.senderUserId+ '">' + response.data.user.name + '</a>: ';
                    if (response.data.text != null) {
                        messageItemInnerHtml += response.data.text;
                    }
                    if (response.data.file != null) {
                        if (response.data.file.type == "image") {
                            messageItemInnerHtml += `<img id='imgjquery' src="` + response.data.file.previewUrl + `">`
                        }
                        if (response.data.file.type == "video") {
                            messageItemInnerHtml += `<video controls><source src="` + response.data.file.url + `"></video>`
                        }
                        if (response.data.file.type == "audio") {
                            messageItemInnerHtml += `<audio controls><source src="` + response.data.file.url + `" </audio>`;
                        }
                    }
                }

                var div = document.createElement('div');
                div.className = "item";
                div.dataset.key = response.data.messageId;
                div.innerHTML = messageItemInnerHtml;

                w1.insertBefore(div, w1.firstChild);
            }
        }
    }

    function getWebSocketUrl(address) {
        return address.replace("tcp","ws")
    }
</script>

<?php
$js = <<<JS
 $('form').on('beforeSubmit', function(){
     var form = $(this);
     var formData = new FormData(form[0]);
     $.ajax({
         url: 'view?id=' + $model->chatId,
         type: 'POST',
            processData: false,
            contentType: false,
         data: formData,
         success: function(res){
         },
     });
    document.getElementById('form-input-example').reset();
    return false;
 });
JS;

$this->registerJs($js);
?>



