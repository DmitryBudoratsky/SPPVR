<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $userId
 * @property string $name
 * @property string $lastname
 * @property string $passwordHash
 * @property int $status
 * @property int $role
 * @property string $email
 * @property int $dateOfBirth
 * @property int $createdAt
 * @property int $updatedAt
 * @property string $surname
 *
 * @property AccessToken[] $accessTokens
 * @property Incident[] $incidents
 * @property Message[] $messages
 */
class BaseUser extends \common\models\db\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'role', 'dateOfBirth', 'createdAt', 'updatedAt'], 'integer'],
            [['name', 'lastname', 'passwordHash', 'email', 'surname'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'name' => 'Name',
            'lastname' => 'Lastname',
            'passwordHash' => 'Password Hash',
            'status' => 'Status',
            'role' => 'Role',
            'email' => 'Email',
            'dateOfBirth' => 'Date Of Birth',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'surname' => 'Surname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessTokens()
    {
        return $this->hasMany(AccessToken::className(), ['userId' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIncidents()
    {
        return $this->hasMany(Incident::className(), ['verdictAuthorId' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['userId' => 'userId']);
    }
}
