<?php

namespace backend\controllers;

use common\models\db\Message;
use common\models\db\User;
use Yii;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends PrivateController
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
                'send' => ['POST'],
            ],
        ];
        return $behaviors;
    }

    public function actionSend()
    {
        $model = new Message(['type' => Message::TYPE_USER, 'userId' => User::getUser()->userId]);

        if ($model->load(Yii::$app->request->post()) && !empty($model->text) && $model->save()) {
            return $this->redirect(\Yii::$app->request->referrer);
        }

        if ($model->hasErrors()) {
            \Yii::$app->session->setFlash('error', Html::errorSummary($model));
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
