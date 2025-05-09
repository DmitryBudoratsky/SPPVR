<?php
namespace frontend\models;

use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\db\PasswordResetRequest;
use common\models\db\User;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $rePassword;

    /**
     * @var \common\models\User
     */
    private $_token;

    
    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $this->_token = $token;
        
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'rePassword'], 'required'],
            [['password', 'rePassword'], 'string', 'min' => 6],
            ['rePassword', 'compare', 'compareAttribute' => 'password']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
    	return [
    		'password' => 'Пароль',
    		'rePassword' => 'Повторите пароль',
    	];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
    	/**
    	 * @var PasswordResetRequest $passwordResetRequest
    	 */
    	$passwordResetRequest = PasswordResetRequest::findByPasswordResetToken($this->_token);
    	if (empty($passwordResetRequest)) {
    		return false;
    	}
    	
    	/**
    	 * @var User $user
    	 */
    	$user = User::findIdentity($passwordResetRequest['userId']);
    	if (!$user) {
    		return false;
    	}
    	
        $user->setPassword($this->password);
        $passwordResetRequest->removePasswordResetToken();
        $passwordResetRequest->isUsed = PasswordResetRequest::IS_USED;
              
        if ($user->save() && $passwordResetRequest->save()) {
            return true;
        }
        
        return false;
    }
}