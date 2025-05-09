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
 * @property string $login
 * @property int $status
 * @property int $role
 * @property string $email
 * @property int $isEmailConfirmed
 * @property string $phone
 * @property int $isAlreadyRegistered
 * @property int $avatarFileId
 * @property string $vkUserId
 * @property string $facebookUserId
 * @property string $twitterUserId
 * @property string $instagramUserId
 * @property string $authKey
 * @property int $createdAt
 * @property int $updatedAt
 * @property double $rating
 * @property int $notificationsEnabled
 * @property int $cityId
 * @property int $lastActiveAt
 * @property int $dateOfBirth
 * @property int $isDeleted
 *
 * @property AccessToken[] $accessTokens
 * @property AuthAttempt[] $authAttempts
 * @property ChatMember[] $chatMembers
 * @property ConfirmEmailRequest[] $confirmEmailRequests
 * @property Message[] $messages
 * @property PasswordResetRequest[] $passwordResetRequests
 * @property File $avatarFile
 * @property UserGeoPosition $userGeoPosition
 * @property UserMessage[] $userMessages
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
            [['status', 'role', 'isEmailConfirmed', 'isAlreadyRegistered', 'avatarFileId', 'createdAt', 'updatedAt', 'notificationsEnabled', 'cityId', 'lastActiveAt', 'dateOfBirth', 'isDeleted'], 'integer'],
            [['rating'], 'number'],
            [['name', 'lastname', 'passwordHash', 'login', 'email', 'phone', 'vkUserId', 'facebookUserId', 'twitterUserId', 'instagramUserId'], 'string', 'max' => 255],
            [['authKey'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['phone'], 'unique'],
            [['avatarFileId'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['avatarFileId' => 'fileId']],
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
            'login' => 'Login',
            'status' => 'Status',
            'role' => 'Role',
            'email' => 'Email',
            'isEmailConfirmed' => 'Is Email Confirmed',
            'phone' => 'Phone',
            'isAlreadyRegistered' => 'Is Already Registered',
            'avatarFileId' => 'Avatar File ID',
            'vkUserId' => 'Vk User ID',
            'facebookUserId' => 'Facebook User ID',
            'twitterUserId' => 'Twitter User ID',
            'instagramUserId' => 'Instagram User ID',
            'authKey' => 'Auth Key',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'rating' => 'Rating',
            'notificationsEnabled' => 'Notifications Enabled',
            'cityId' => 'City ID',
            'lastActiveAt' => 'Last Active At',
            'dateOfBirth' => 'Date Of Birth',
            'isDeleted' => 'Is Deleted',
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
    public function getAuthAttempts()
    {
        return $this->hasMany(AuthAttempt::className(), ['userId' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChatMembers()
    {
        return $this->hasMany(ChatMember::className(), ['userId' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfirmEmailRequests()
    {
        return $this->hasMany(ConfirmEmailRequest::className(), ['userId' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['senderUserId' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPasswordResetRequests()
    {
        return $this->hasMany(PasswordResetRequest::className(), ['userId' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvatarFile()
    {
        return $this->hasOne(File::className(), ['fileId' => 'avatarFileId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGeoPosition()
    {
        return $this->hasOne(UserGeoPosition::className(), ['userId' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserMessages()
    {
        return $this->hasMany(UserMessage::className(), ['userId' => 'userId']);
    }
}
