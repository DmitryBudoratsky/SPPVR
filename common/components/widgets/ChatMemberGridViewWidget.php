<?php

namespace common\components\widgets;

use backend\controllers\common\ChatMemberController;
use common\components\helpers\TypeHelper;
use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use yii\helpers\Html;
use common\models\db\User;
use common\models\db\Message;
use common\models\db\ChatMember;

class ChatMemberGridViewWidget extends Widget
{
    /**
     * @var ActiveDataProvider $dataProvider
     */
    public $dataProvider;


    public function run()
    {

        return
            GridView::widget([
                'dataProvider' => $this->dataProvider,
                'columns' => [
                    'chatMemberId',
                    [
                    	'attribute' => 'userId',
                        'format' => 'raw',
                        'value' => function (/** @var ChatMember $chatMember */ $chatMember) {
                            return (!empty($chatMember->user)) ? Html::a($chatMember->user->name, ['common/user/view', 'id' => $chatMember->userId]) : null;
                        }
                    ],
                    [
                        'attribute' => 'chatRole',
                        'value' => function ($model) {
                            return TypeHelper::getTypeLabelByModel($model, 'chatRole');
                        },
                    ],

                    'blockedUntil:datetime',
                
                    
                    'createdAt:datetime',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'controller' => 'common/chat-member',
                        'contentOptions'=>['style'=>'max-width: 100px;']
                    ],

                ]
            ]);
    }
}

?>
