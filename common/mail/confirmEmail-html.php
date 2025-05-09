<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\db\User */
/* @var $confirmEmailRequest common\models\db\ConfirmEmailRequest */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm-email', 'token' => $confirmEmailRequest->confirmEmailToken]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->name) ?>,</p>

    <p>для завершения регистрации перейдите по ссылке:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
