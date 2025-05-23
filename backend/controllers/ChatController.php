<?php

namespace backend\controllers;

use common\components\helpers\FileTypeHelper;
use common\components\helpers\ModelHelper;
use common\models\db\ChatMember;
use common\models\db\Message;
use frontend\modules\api\models\message\MarkMessagesViewedForm;
use frontend\modules\api\models\message\SendMessageForm;
use Yii;
use common\models\db\Chat;
use backend\models\ChatSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\db\File;
use yii\web\UploadedFile;
use backend\models\forms\ChatMemberForm;
use yii\helpers\ArrayHelper;
use common\models\db\User;
use backend\controllers\PrivateController;

/**
 * ChatController implements the CRUD actions for Chat model.
 */
class ChatController extends PrivateController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
		$behaviors = parent::behaviors();
        $behaviors['verbs'] = [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'block-member-until' => ['POST'],
                ],
            ];
		return $behaviors;
    }

    /**
     * Finds the Chat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Chat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Chat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionDownloadFile()
    {
        $path = \Yii::$app->request->get('path');
        $root = \Yii::getAlias('@filesUploads').$path;

        return \Yii::$app->response->sendFile($root);
    }
}
