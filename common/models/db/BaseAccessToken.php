<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "accessToken".
 *
 * @property int $accessTokenId
 * @property int $userId
 * @property string $token
 * @property int $createdAt
 * @property int $updatedAt
 *
 * @property User $user
 */
class BaseAccessToken extends \common\models\db\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accessToken';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'token'], 'required'],
            [['userId', 'createdAt', 'updatedAt'], 'integer'],
            [['token'], 'string', 'max' => 255],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'userId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'accessTokenId' => 'Access Token ID',
            'userId' => 'User ID',
            'token' => 'Token',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['userId' => 'userId']);
    }
}
