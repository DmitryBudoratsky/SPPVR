<?php

namespace common\components\widgets;

use common\components\helpers\FileHelper;
use common\models\db\ChatMember;
use common\models\db\File;
use kop\y2sp\ScrollPager;
use Yii;
use yii\base\Widget;
use yii\bootstrap4\ButtonDropdown;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use yii\helpers\Html;
use common\models\db\Message;
use common\components\helpers\FileTypeHelper;

class MessageGridViewWidget extends Widget
{
    /**
     * @var ActiveDataProvider $dataProvider
     */
    public $dataProvider;


    public function run()
    {

        return \yii\widgets\ListView::widget([
            'dataProvider'  => $this->dataProvider,
            'summary'       => 'Total {totalCount} items.',
            'layout'        => "{items}\n<div class=\"row\"><div class=\"col-md-12\">\n{pager}</div></div>",
            'itemOptions'   => ['class' => 'item'],
            'itemView'      => '_messageItem',
            'pager' => [
                'class'     => ScrollPager::className(),
                'triggerOffset'=>5,
            ],
        ]);




//        return
//            GridView::widget([
//                'dataProvider' => $this->dataProvider,
//                'pager' => [
//                    'class' => \kop\y2sp\ScrollPager::className(),
//                    'container' => '.grid-view tbody',
//                    'item' => 'tr',
//                    'paginationSelector' => '.grid-view .pagination',
//                    'triggerTemplate' => '<tr class="ias-trigger"><td colspan="100%" style="text-align: center"><a style="cursor: pointer">{text}</a></td></tr>',
//                ],
//                'columns' => [
//                    'messageId',
//                    [
//                    	'attribute' => 'senderUserId',
//                        'format' => 'raw',
//                        'value' => function (/** @var Message $message */ $message) {
//                            return (!empty($message->senderUser)) ? Html::a($message->senderUser->name, ['common/user/view', 'id' => $message->senderUserId]) : null;
//                        }
//                    ],
//
//                    [
//                        'label' => 'Аватар',
//                        'format' => 'raw',
//                        'value' => function ($message)
//                        {
//                            $senderUser = (!empty($message->senderUser)) ? $message->senderUser : null;
//                            $avatar = (!empty($senderUser)) ? $senderUser->avatarFile : null;
//                            return (!empty($avatar)) ? Html::img($avatar->getAbsoluteFileUrl(),
//                                ['alt' => 'icon', 'class' => 'previewImage']) : null;
//                        }
//                    ],
//
//                    [
//                        'label' => 'Сообщение',
//                        'format' => 'raw',
//                        'value' => function ($model) {
//                            //Показать текст
//                            if (!empty($model->text)) {
//                                return $model->text;
//                            }
//                            // Или показать картинку, видео или аудио файл
//                            /**
//                             * @var File $file
//                             */
//                            $file = $model->file;
//                            return (!empty($file)) ? FileHelper::prepareFileContent($file) : null;
//                        }
//                    ],
//
//                    'createdAt:datetime',
//
//                    [
//                        'class' => 'yii\grid\ActionColumn',
//                        'template' => '{all}',
//                        'buttons' => [
//                            'all' => function ($url, /** @var $model Message */ $model, $key) {
//
//                                $chatId = $model->chatId;
//                                $userId = $model->senderUserId;
//
//                                if (!empty($model->isSystem)) {
//                                    return null;
//                                }
//
//                                $items = [
//                                    [
//                                        'label' => 'Удалить сообщение',
//                                        'url' => ['common/message/delete', 'id' => $key, 'chatId' => $model->chatId],
//                                        'linkOptions' => [
//                                            'data' => [
//                                                'method' => 'post',
//                                                'confirm' => 'Вы действительно хотите удалить эту запись?',
//                                            ],
//                                        ],
//                                    ],
//                                ];
//
//                                if (ChatMember::isBlocked($model->chatId, $model->senderUserId)) {
//                                    $items[] = [
//                                        'label' => 'Разблокировать',
//                                        'url' => ['common/chat/unblock-member', 'chatId' => $chatId, 'userId' => $userId],
//                                        'linkOptions' => [
//                                            'data' => [
//                                                'confirm' => 'Вы действительно хотите разблокировать?',
//                                            ],
//                                        ],
//                                    ];
//                                } else {
//                                    foreach (json_decode(Settings::getSettings()->userChatBlocking) as $blockingItem) {
//                                        $items[] = [
//                                            'label' => $blockingItem['userChatBlockingTitle'],
//                                            'url' => ['common/message/block-user-in-chat', 'chatId' => $chatId, 'userId' => $userId, 'blockingDuration' => $blockingItem['duration']],
//                                            'linkOptions' => [
//                                                'data' => [
//                                                    'confirm' => 'Вы действительно хотите заблокировать?',
//                                                ],
//                                            ],
//                                        ];
//                                    }
//                                }
//
//                                return ButtonDropdown::widget([
//                                    'encodeLabel' => false,
//                                    'label' => 'Действия',
//                                    'dropdown' => [
//                                        'encodeLabels' => false,
//                                        'items' => $items,
//                                        'options' => [
//                                            'class' => 'dropdown-menu-right',
//                                        ],
//                                    ],
//                                    'options' => [
//                                        'class' => 'btn-default',
//                                    ],
//                                ]);
//                            },
//                        ],
//                    ],
//                ]
//            ]);
    }
}
?>
