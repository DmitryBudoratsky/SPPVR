<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\Incident */

$this->title = 'Изменение случая №' . $model->incidentId;
$this->params['breadcrumbs'][] = ['label' => 'Список случаев', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Случай №' . $model->incidentId, 'url' => ['view', 'id' => $model->incidentId]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="incident-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
