<?php

namespace backend\controllers;

use common\components\helpers\FileTypeHelper;
use common\components\helpers\ModelHelper;
use common\models\db\File;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\UserSearch;
use common\models\db\User;
use yii\db\Query;
use backend\controllers\PrivateController;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends PrivateController
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
                ],
            ];
		return $behaviors;
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $fileModel = new File();
        $model->scenario = $model::SCENARIO_CREATE_BY_ADMIN_PANEL;
        if ($model->load(Yii::$app->request->post())) {

            $file = UploadedFile::getInstance($fileModel, 'fileId');

            if (!empty($file) && !in_array($file->type, FileTypeHelper::imageMimeTypes())) {
                Yii::$app->session->setFlash('error', 'Загрузить можно только картинку (png, jpg, jpeg, pjpeg)');
                return $this->redirect(Yii::$app->request->referrer);
            }

            if (!empty($file) && in_array($file->type, FileTypeHelper::imageMimeTypes())) {
                $model->saveAvatar($file);
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->userId]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'fileModel' => $fileModel,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $fileModel = new File();
        if ($model->load(Yii::$app->request->post())) {

            $file = UploadedFile::getInstance($fileModel, 'fileId');
            if (!empty($file) && in_array($file->type, FileTypeHelper::imageMimeTypes())) {
                $model->saveAvatar($file);
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->userId]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'fileModel' => $fileModel,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!$model->delete()) {
            \Yii::$app->session->setFlash('error', $model->getFirstErrors());
            return $this->redirect(Yii::$app->request->referrer);
        }

        //$this->findModel($id)->delete();
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true];
        }

        return $this->redirect(['index']);
    }
    
    /**
     * Заблокировать пользователя.
     * @param integer $id
     */
    public function actionBlock($id)
    {
    	$model = $this->findModel($id);
    	$model->status = User::STATUS_BLOCKED;
        if (!$model->save()) {
            \Yii::$app->session->setFlash('error', ModelHelper::getFirstError($model));
        }
    	
    	return $this->redirect(\Yii::$app->request->referrer);
    }
    
    /**
     * Разблокировать пользователя.
     * @param integer $id
     */
    public function actionUnblock($id)
    {
    	$model = $this->findModel($id);
    	$model->status = User::STATUS_ACTIVE;
        if (!$model->save()) {
            \Yii::$app->session->setFlash('error', ModelHelper::getFirstError($model));
        }
    	
    	return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*************
     * Users List For Select2 ajax
     ************/
    public function actionUserList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (is_null($id)) {
            $query = User::find()
                ->select(["userId AS id", "TRIM(CONCAT_WS(' ',name, lastname)) AS text"])
                ->andFilterHaving(['like', 'text', $q])
                ->limit(40);
            $out['results'] = $query->asArray()->all();
        } if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::findOne($id)->getFullUserName()];
        }
        return $out;
    }


}
