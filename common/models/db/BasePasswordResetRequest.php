<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "passwordResetRequest".
 *
 * @property int $passwordResetRequestId
 * @property int $userId
 * @property string $passwordResetToken
 * @property int $isUsed
 * @property int $expirationDate
 * @property int $createdAt
 * @property int $updatedAt
 *
 * @property User $user
 */
class BasePasswordResetRequest extends \common\models\db\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'passwordResetRequest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId'], 'required'],
            [['userId', 'isUsed', 'expirationDate', 'createdAt', 'updatedAt'], 'integer'],
            [['passwordResetToken'], 'string', 'max' => 255],
            [['passwordResetToken'], 'unique'],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'userId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'passwordResetRequestId' => 'Password Reset Request ID',
            'userId' => 'User ID',
            'passwordResetToken' => 'Password Reset Token',
            'isUsed' => 'Is Used',
            'expirationDate' => 'Expiration Date',
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
