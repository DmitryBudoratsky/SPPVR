<?php

namespace common\models\db;

class PasswordResetRequest extends BasePasswordResetRequest
{
	const IS_NOT_USED = 0;
	const IS_USED = 1;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'passwordResetRequestId' => 'ID',
			'userId' => 'ID пользователя',
			'passwordResetToken' => 'Токен',
			'isUsed' => 'Использован или нет',
			'expirationDate' => 'Дата истечения',
			'createdAt' => 'Дата создания',
			'updatedAt' => 'Дата обновления'
		];
	}

	/**
	 * Find PasswordResetRequest by password reset token
	 *
	 * @param string $token password reset token
	 * @return static|null
	 */
	public static function findByPasswordResetToken($token)
	{
		if (!static::isPasswordResetTokenValid($token)) {
			return null;
		}

		return static::findOne(['passwordResetToken' => $token]);
	}

	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token password reset token
	 * @return boolean
	 */
	public static function isPasswordResetTokenValid($token)
	{
		if (empty($token)) {
			return false;
		}

		$timestamp = (int) substr($token, strrpos($token, '_') + 1);
		$expire = \Yii::$app->params['userPasswordResetTokenExpireunityEmail'];
		return $timestamp + $expire >= time();
	}

	/** Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->passwordResetToken = null;
	}
}