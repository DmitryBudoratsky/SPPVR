<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\Incident */

$this->title = 'Создание случая';
$this->params['breadcrumbs'][] = ['label' => 'Список случаев', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="incident-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
