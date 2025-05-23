<?php

use common\components\helpers\FancyboxHelper;
use common\models\db\Message;
use common\models\db\User;
use kartik\grid\GridView;
use kartik\widgets\FileInput;
use yii\bootstrap4\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\db\Chat */
/* @var $message Message */

\backend\assets\common\SocketNotificationAsset::register($this);

$user = User::getUser();
if (!empty($user)) {
    $accessToken = urlencode(User::getUser()->getAccessToken());
    $webSocketAddress = Yii::$app->params['webSocketPublicAddress'];
}

if (!isset($message)) {
    $message = new Message();
}
?>
<div class="chat-view">

    <h1>Чат</h1>

    <div class="card border-default">
        <?= Html::tag('div', Html::tag('h5', 'Список сообщений <i class="fa fa-comments"></i>', ['class' => 'card-header'])); ?>

        <div class="card-body">
            <?= FancyboxHelper::renderFancybox(); ?>
            <?php Pjax::begin(['id' => 'message-grid-view']); ?>
            <?= GridView::widget([
                'dataProvider' => new ActiveDataProvider([
                    'query' => $model->getMessages()
                        ->orderBy(['messageId' => SORT_DESC]),
                    'sort' => false,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]),
                'columns' => [
                    [
                        'label' => 'Имя отправителя',
                        'format' => 'html',
                        'value' => static function (Message $model) {
                            if (!empty($model->userId)) {
                                return Html::a($model->user->name . ' ' . $model->user->lastname, ['user/view', 'id' => $model->userId]);
                            }

                            return null;
                        }
                    ],

                    [
                        'label' => 'Сообщение',
                        'format' => 'raw',
                        'options' => ['class' => 'col-sm-8'],
                        'value' => static function (/** @var Message $model */ $model) {
                            /*if (!empty($model->file)) {
                                switch ($model->getMessageType()) {
                                    case Message::MESSAGE_TYPE_IMAGE:
                                    {
                                        return Html::a(Html::img($model->file->getPreviewImageUrl(), ['class' => 'previewImage']), $model->file->getAbsoluteFileUrl(), ['rel' => 'fancybox']);
                                    }
                                    case Message::MESSAGE_TYPE_DOCUMENT:
                                    {
                                        $fileName = $model->file->originalName;
                                        return '<a href="#" class="download-link" data-path="' . str_replace('uploads', '', $model->file->url) . '" data-file-name="' . $fileName . '">' . $fileName . '</a>';
                                    }
                                    case Message::MESSAGE_TYPE_VIDEO:
                                    {
                                        if (!empty($model->file) && $model->file->type == 'video') {
                                            $url = $model->file->getAbsoluteFileUrl();
                                            return '<video width="280" height="156" controls><source src="' . $url . '"></video><br>';
                                        }
                                    }
                                }
                            }*/
                            return $model->text;
                        }
                    ],

                    'createdAt:datetime',
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>


        <?= Html::tag('div', Html::tag('h5', 'Отправить сообщение в чат <i class="fa fa-comment"></i>', ['class' => 'card-header card-footer'])); ?>
        <div class="card-body">
            <?php $form = ActiveForm::begin(['id' => 'form-send-message']); ?>
            <?= $form->errorSummary($message); ?>

            <?= $form->field($message, 'file')->widget(FileInput::className(), [
                'language' => 'ru',
                'options' => [
                    'multiple' => false,
                ],
                'pluginOptions' => [
                    'uploadAsync' => false,
                    'showPreview' => false,
                    'showCaption' => true,
                    'showRemove' => false,
                    'showUpload' => false,

                    'browseClass' => 'btn btn-primary',
                    'browseLabel' => '',
                    'browseIcon' => '<i class="fa fa-paperclip" aria-hidden="true"></i>',
                ]
            ])->label(false); ?>

            <?= $form->field($message, 'text')->textInput(['placeholder' => 'Напишите сообщение...'])->label(false); ?>

            <div class="btn-group mr-2 float-right">
                <?= Html::submitButton('Отправить <i class="fa fa-paper-plane" aria-hidden="true"></i>', ['class' => 'btn btn-success']) ?>
                <?= Html::resetButton('Отмена <i class="fa fa-ban" aria-hidden="true"></i>', ['class' => 'btn btn-secondary btn-default']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

<?php
$downloadFileUrl = Url::to(['chat/download-file']);

$socketAddress = Yii::$app->params['webSocketPublicAddress'];
$socketUrl = $socketAddress . '/accessToken=' . urlencode($user->getAccessToken());
$socketType = Message::SOCKET_TYPE;

$js = <<<JS
    $(document).on("click", ".download-link", function(e) {
        e.preventDefault();
        let path = $(this).data("path");
        let fileName = $(this).data("fileName");
        let url = "{$downloadFileUrl}?path=" + path;
        let xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.responseType = "blob";
        
        xhr.onload = function() {
            if (this.status === 200) {
                let contentType = xhr.getResponseHeader("Content-Type");
                let blob = new Blob([this.response], { type: contentType });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = fileName;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        };
        
        xhr.send();
    });

    $(document).ready(function () {
        let chatId = Number("{$model->chatId}");
        let chat = new reWebSocket("{$socketUrl}", function (response) {
            if (response && response.data.chatId === chatId) {
                if (response.socketType === "{$socketType}") {
                    $.pjax.reload({container: '#message-grid-view', async: false});
                }
            }
        });
    });
JS;
$this->registerJs($js, \yii\web\View::POS_HEAD);



