<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\db\User */
/* @var $password string */

?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->name) ?>,</p>

    <p>Ваш пароль от учетной записи: <?= $password ?> </p>

</div>
