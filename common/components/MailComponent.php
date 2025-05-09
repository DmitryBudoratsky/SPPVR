<?php

namespace common\components;

use common\models\db\Settings;
use yii\base\Component;
use common\models\db\User;
use common\models\db\ConfirmEmailRequest;

class MailComponent extends Component
{
    /**
     * Sends an email with a link, for resetting the password.
     * @param string $email
     * @param PasswordResetRequest $passwordResetRequest
     * @param User $user
     * @return boolean
     */
    public static function sendResetPasswordEmail($email, $passwordResetRequest, $user)
    {          
        return \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html'], 
            ['passwordResetRequest' => $passwordResetRequest, 'user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => \Yii::$app->name . ' робот'])
            ->setTo($email)
            ->setSubject('Восстановление пароля ' . \Yii::$app->name)
            ->send();
    }
    
    /**
     * Sends an email with a link, for confirm the email.
     * @param string $email
     * @param ConfirmEmailRequest $confirmEmailRequest
     * @param User $user
     * @return boolean
     */
    public static function sendConfirmEmail($email, $confirmEmailRequest, $user)
    {
    	return \Yii::$app->mailer->compose(['html' => 'confirmEmail-html'],
    		['confirmEmailRequest' => $confirmEmailRequest, 'user' => $user])
    		->setFrom([Yii::$app->params['supportEmail'] => \Yii::$app->name . ' робот'])
    		->setTo($email)
    		->setSubject('Подтверждение email ' . \Yii::$app->name)
    		->send();
    }
    
    /**
     * Sends an email with a password.
     * @param string $email
     * @param string $password
     * @param User $user
     * @return boolean
     */
    public static function sendPasswordEmail($email, $password, $user)
    {
    	return \Yii::$app->mailer->compose(['html' => 'registerByEmail-html'],
    		['password' => $password, 'user' => $user])
    		->setFrom([Yii::$app->params['supportEmail'] => \Yii::$app->name . ' робот'])
    		->setTo($email)
    		->setSubject('Ваш пароль от учетной записи ' . \Yii::$app->name)
    		->send();
    }

    /**
     * Sends an email with a code, for confirm the auth.
     * @param string $email
     * @param string $code
     * @return boolean
     */
    public static function sendCodeEmail($email, $code)
    {
        return \Yii::$app->mailer->compose(['html' => 'confirmCode-html'], ['code' => $code])
            ->setFrom([Yii::$app->params['supportEmail'] => \Yii::$app->name . ' робот'])
            ->setTo($email)
            ->setSubject('Код авторизации')
            ->send();
    }
}