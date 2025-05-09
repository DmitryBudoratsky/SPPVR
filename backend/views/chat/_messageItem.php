<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.02.19
 * Time: 14:45
 */

/** @var \common\models\db\Message $model */

use common\models\db\Settings;
use common\components\helpers\FileHelper;
use common\models\db\ChatMember;
use yii\bootstrap4\ButtonDropdown;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

?>


<?php

$chatId = $model->chatId;
$userId = $model->senderUserId;

if (!empty($model->isSystem)) {
    return null;
}

$key = $model->messageId;

$items = [
    [
        'label' => 'Удалить сообщение',
        'url' => ['message/delete', 'id' => $key, 'chatId' => $model->chatId],
        'linkOptions' => [
            'data' => [
                'method' => 'post',
                'confirm' => 'Вы действительно хотите удалить эту запись?',
            ],
        ],
    ],
];

echo ButtonDropdown::widget([
    'encodeLabel' => false,
    'label' => '<i class="glyphicon glyphicon-option-horizontal"></i>',
    'dropdown' => [
        'encodeLabels' => false,
        'items' => $items,
        'options' => [
            'class' => 'dropdown-menu-right',
        ],
    ],
    'options' => [
        'class' => 'btn-default btn-xs',
    ],
]);

?>

<?= '['. $model->createdAtToString() . '] ' ?>

<?php
if (!empty($model->senderUser)) {
    echo Html::a($model->senderUser->name, ['user/view', 'id' => $model->senderUserId]) . ': ';
}

?>

<?php
//Показать текст
if (!empty($model->text)) {
    echo $model->text;
} else {
    // Или показать картинку, видео или аудио файл
    $file = $model->file;
    echo (!empty($file)) ? FileHelper::prepareFileContent($file) : null;
}

?>


