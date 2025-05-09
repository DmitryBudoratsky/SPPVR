<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\db\User */
/* @var $passwordResetRequest common\models\db\PasswordResetRequest */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $passwordResetRequest->passwordResetToken]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->name) ?>,</p>

    <p>Для сброса пароля перейдите по ссылке:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
