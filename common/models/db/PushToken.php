<?php

namespace common\models\db;

class PushToken extends BasePushToken
{
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'pushTokenId' => 'ID',
			'userId' => 'ID пользователя',
			'createdAt' => 'Дата создания',
			'updatedAt' => 'Дата обновления'
		];
	}

	/**
	 * @param int $userId
	 * @return bool
	 */
	public static function userHasPushTokens($userId)
	{
		$query = self::find()
			->andWhere(["pushToken.userId" => $userId]);
		return $query->exists();
	}
}
