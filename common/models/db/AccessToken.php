<?php

namespace common\models\db;

class AccessToken extends BaseAccessToken
{
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'accessTokenId' => 'ID',
            'userId' => 'ID пользователя',
            'token' => 'Токен',
        	'createdAt' => 'Дата создания',
        	'updatedAt' => 'Дата обновления'
        ];
    }
}
