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
     * Lists all Chat models.
     * @return mixed
     */
    public function actionIndex($type)
    {
        $searchModel = new ChatSearch();
        $searchModel->type = $type;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'type' => $type
        ]);
    }

    /**
     * Displays a single Chat model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->enableCsrfValidation = false;
        /** @var Chat $model */
        $model = $this->findModel($id);

        $messageDataProvider = new ActiveDataProvider([
            'query' => $model->getMessages()->orderBy(['messageId' => SORT_DESC])
        ]);
        $chatMembersDataProvider = new ActiveDataProvider([
            'query' => $model->getChatMembers()->orderBy(['chatMemberId' => SORT_DESC])
        ]);
        /**
         * @var MarkMessagesViewedForm $model
         */
        $markMessagesForm = new MarkMessagesViewedForm();

        $messageIds = Message::findChatMessages($model->chatId)
            ->select(['message.messageId'])
            ->column();

        $markMessagesForm->messageIds = Json::encode($messageIds);
        $markMessagesForm->markMessagesViewed();

        // Форма для отправки сообщений
        $sendMessageModel = new SendMessageForm();
        $sendMessageModel->scenario = $sendMessageModel::SCENARIO_SEND_MESSAGE;
        $sendMessageModel->chatId = $id;

        $modelLoaded = $sendMessageModel->load(Yii::$app->request->post());
        $sendMessageModel->file = UploadedFile::getInstance($sendMessageModel, 'file');

        if ($modelLoaded && $sendMessageModel->validate()) {
            if (!empty($sendMessageModel->file) && !in_array($sendMessageModel->file->type, FileTypeHelper::imageMimeTypes())) {
                Yii::$app->session->setFlash('error', 'Загрузить можно только картинку (png, jpg, jpeg, pjpeg)');
            } elseif ($sendMessageModel->sendMessage(true)){
                \Yii::$app->session->setFlash('success', 'Сообщение отправлено');

                Yii::debug('redirect to:' . "view?id={$id}#messages");
                // Force redirect
                // https://stackoverflow.com/questions/26944336/yii-framework-redirect-didnt-refresh-my-page-data-loaded-from-cache
                return $this->redirect(['chat/view', 'id' => $id, '#' => 'messages', '_'  => time()])->send();
            }
        }
        if ($sendMessageModel->hasErrors()) {
            \Yii::warning($sendMessageModel->getErrors());
        }

        return $this->render('view', [
            'model' => $model,
            'messageDataProvider' => $messageDataProvider,
            'chatMembersDataProvider' => $chatMembersDataProvider,
            'sendMessageModel' => $sendMessageModel
        ]);
    }

    /**
     * Creates a new Chat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Chat();
        $model->type = Chat::TYPE_GROUP_CHAT;
		return $this->saveModel($model, 'create');
    }

    /**
     * Updates an existing Chat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->isPersonalChat()) {
            \Yii::$app->getSession()->setFlash('error', 'Нельзя редактировать персональные чаты.');
            return $this->redirect(['chat/index', 'type' => 1]);
        }

		return $this->saveModel($model, 'update');
    }

    /**
     * Сохранение модели.
     * @param Chat $model
     * @param string $view
     */
    private function saveModel($model, $view)
    {
        /**
         * @var File $fileModel
         */
        $fileModel = new File();

        $chatMemberForm = null;

        if ($model->isGroupChat()) {
            /**
             * @var ChatMemberForm $chatMemberForm
             */
            $chatMemberForm = new ChatMemberForm();
        }

        if ($view === 'create' && $model->isGroupChat()) {
            $model->scenario = Chat::SCENARIO_CREATE_GROUP;
        }

        if ($view === 'update' && !empty($model) && $model->isGroupChat()) {
            $chatMemberForm->userIds = $model->getChatMembers()->select('chatMember.userId')->asArray()->column();
        }

        if (\Yii::$app->request->isPost) {
            $file = UploadedFile::getInstance($fileModel, 'fileId');
            if (!empty($file) && !$model->saveAvatar($file)) {
                \Yii::$app->getSession()->setFlash('error', 'Не получилось сохранить аватар.');
                return $this->redirect(\Yii::$app->request->referrer);
            }

            if ($model->load(\Yii::$app->request->post())
                && $model->validate() && $model->save()) {

                if ($model->isGroupChat() && !empty(Yii::$app->request->post('ChatMemberForm')['userIds'])) {
                    $chatMemberForm->chatId = $model->chatId;
                    if ($chatMemberForm->load(Yii::$app->request->post()) && $chatMemberForm->validate()) {
                        if (!$chatMemberForm->updateChatMembers()) {
                            \Yii::$app->getSession()->setFlash('error', 'Не получилось сохранить собеседников.');
                            return $this->redirect(\Yii::$app->request->referrer);
                        }
                    }
                }

                return $this->redirect(['view', 'id' => $model->chatId]);
            }
        }

        return $this->render($view, [
            'model' => $model,
            'fileModel' => $fileModel,
            'chatMemberForm' => $chatMemberForm
        ]);
    }

    /**
     * Deletes an existing Chat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
    	$type = $model->type;
        $model->delete();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true];
        }

        return $this->redirect(['index', 'type' => $type]);
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
    
    /*************
     * User List For Select2 ajax
     ************/
    public function actionUserList($chatId, $q = null)
    {
    	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$out = ['results' => ['id' => '', 'text' => '']];
    
    	/**
    	 * @var Chat $chat
    	 */
    	$chat = Chat::find()->where(['chatId' => $chatId])->one();
    	if (empty($chat)) {
    		return;
    	}
    
		$notAvailableUserIds = $chat->getChatMembers()->select('chatMember.userId')->asArray()->column();
    	
    	$query = User::find();
    	$query->select("userId AS id, name AS text")
    		->from('user')
    		->andWhere(['not in', 'user.userId', $notAvailableUserIds])
    		->andWhere(['like', "{{name}}", (is_null($q)) ? '' : $q]);
    		
    	$command = $query->createCommand();
    	$data = $command->queryAll();
    	$out['results'] = array_values($data);
    	return $out;
    }
}
