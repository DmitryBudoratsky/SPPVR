<?php

namespace backend\controllers;

use common\models\db\Chat;
use setasign\Fpdi\FpdfTpl;
use setasign\Fpdi\PdfParser\Type\PdfArray;
use Yii;
use common\models\db\Incident;
use backend\models\IncidentSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IncidentController implements the CRUD actions for Incident model.
 */
class IncidentController extends PrivateController
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
     * Lists all Incident models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IncidentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Incident model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Incident model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Incident();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->incidentId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Incident model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, Incident::STATUS_CREATED);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->incidentId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Вынесение вердикта
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionWriteVerdict($id)
    {
        $model = $this->findModel($id, Incident::STATUS_CREATED);

        $model->status = Incident::STATUS_FINISHED;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->incidentId]);
        }

        return $this->render('verdict-form', [
            'model' => $model,
        ]);
    }

    public function actionDownloadAsFile($id, $extension = 'pdf')
    {
        if (!in_array($extension, ['csv', 'xls', 'pdf', 'json'])) {
            throw new BadRequestHttpException("Файл не может быть скачан в этом расширении");
        }

        $model = $this->findModel($id, Incident::STATUS_FINISHED);
        $fileName = "Случай №$model->incidentId." . $extension;

        if ($extension == 'pdf') {
            $pdf = new \kartik\mpdf\Pdf();
            $pdf->cssInline = 'table {overflow: wrap;} table th {width: 20%;}';

            return $pdf->output(
                $this->getAsHtmlContent($model),
                $fileName,
                'php://output'
            );
        }

        $content = '';
        switch ($extension) {
            case ('xls'):
                $content = $this->getAsXlsContent($model);
                break;
            case ('csv'):
                $content = $this->getAsCsvContent($model);
                break;
            case ('json'):
                $content = $this->getAsJsonContent($model);
                break;
        }

        return \Yii::$app->response->sendContentAsFile($content, $fileName);
    }

    /**
     * Deletes an existing Incident model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Incident model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Incident the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $status = null)
    {
        /** @var Incident $model */
        $model = Incident::find()
            ->andWhere(['incidentId' => $id])
            ->andFilterWhere(['status' => $status])
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getAsHtmlContent(Incident $model): string
    {
        $incidentName = "Случай №$model->incidentId";

        $content = "<h1>$incidentName</h1>";
        $content .= $this->renderPartial('_detail', ['model' => $model]);
        $content .= '© ' . \Yii::$app->name . ' ' . \Yii::$app->formatter->asDate(time(), 'php:Y');

        return $content;
    }

    private function getAsXlsContent(Incident $model): string
    {
        $data = $model->serialize();

        array_walk($data, function (&$val) {
            $val = (string)$val;

            $val = preg_replace("/\t/", "\\t", $val);
            $val = preg_replace("/\r?\n/", "\\n", $val);

            if (str_contains($val, '"')) {
                $val = '"' . str_replace('"', '""', $val) . '"';
            }
        });

        $content = '';
        foreach ($data as $key => $value) {
            $row = !isset($model->attributeLabels()[$key])
                ? [$value]
                : [$model->getAttributeLabel($key), $value];

            $content .= implode("\t", $row) . "\n";;
        }

        return $content;
    }

    public function actionStartChat($id)
    {
        $model = $this->findModel($id);

        if (!empty($model->chatId)) {
            return $this->redirect(['view', 'id' => $model->incidentId]);
        }

        $chat = new Chat();
        if (!$chat->save()) {
            \Yii::$app->session->setFlash('error', Html::errorSummary($chat));
            return $this->redirect(['view', 'id' => $model->incidentId]);
        }

        $model->chatId = $chat->chatId;
        if (!$chat->sendToNeuroStartMessage($model)) {
            \Yii::$app->session->setFlash('error', 'Не удалось создать чат');
            return $this->redirect(['view', 'id' => $model->incidentId]);
        }
        if (!$model->save()) {
            \Yii::$app->session->setFlash('error', Html::errorSummary($chat));
            return $this->redirect(['view', 'id' => $model->incidentId]);
        }

        return $this->redirect(['view', 'id' => $model->incidentId]);
    }

    private function getAsCsvContent(Incident $model): string
    {
        $data = $model->serialize();

        array_walk($data, function (&$val) {
            $val = (string)$val;

            if (str_contains($val, '"')) {
                $val = '"' . str_replace('"', '""', $val) . '"';
            }
        });

        $content = '';
        foreach ($data as $key => $value) {
            $row = !isset($model->attributeLabels()[$key])
                ? [$value]
                : [$model->getAttributeLabel($key), $value];

            $content .= implode(",", $row) . PHP_EOL;
        }

        return $content;
    }

    private function getAsJsonContent(Incident $model)
    {
        $data = $model->serialize();
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
