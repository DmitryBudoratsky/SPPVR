<?php

namespace common\models\db;

use OpenApi\Annotations as OA;
use yii\web\IdentityInterface;
use yii;
use common\components\validators\PhoneValidator;
use common\components\validators\PasswordValidator;
use yii\db\ActiveQuery;

/**
 * @OA\Schema(schema="User", description="Пользователь", properties={
 *     @OA\Property(property="userId", type="integer", description="ID пользователя"),
 *     @OA\Property(property="name", type="string", description="Имя"),
 *     @OA\Property(property="avatar", type="string", description="Url аватара"),
 *     @OA\Property(property="toRelationStatus", description="Подписан ли я на этого пользователя", ref="#/components/schemas/UserRelationStatus"),
 *     @OA\Property(property="fromRelationStatus", description="Подписан ли этот пользователь на меня", ref="#/components/schemas/UserRelationStatus"),
 * })
 *
 * @OA\Schema(schema="FullUser", description="Пользователь", allOf={
 *     @OA\Schema(ref="#/components/schemas/User"),
 *     @OA\Schema(
 *         @OA\Property(property="lastname", type="string", description="Фамилия пользователя"),
 *         @OA\Property(property="dateOfBirth", type="integer", description="Дата рождения"),
 *         @OA\Property(property="rating", type="number", description="Рейтинг пользователя"),
 *         @OA\Property(property="email", type="string", description="Электронная почта пользователя"),
 *         @OA\Property(property="login", type="string", description="Логин пользователя"),
 *         @OA\Property(property="phone", type="string", description="Телефон пользователя"),
 *         @OA\Property(property="haveProAccount", type="integer", description="Индикация pro аккаунта"),
 *         @OA\Property(property="city", ref="#/components/schemas/City"),
 *         @OA\Property(property="country", ref="#/components/schemas/Country"),
 *     )
 * })
 */
class User extends BaseUser implements IdentityInterface
{
	const STATUS_DELETED = 0;
	const STATUS_BLOCKED = 1;
	const STATUS_ACTIVE = 10;

	const ROLE_DEFAULT_USER = 1;
	const ROLE_ADMIN = 10;

	const IS_NOW_REGISTERED = 0;
	const IS_ALREADY_REGISTERED = 1;

	const IS_NOT_EMAIL_CONFIRMED = 0;
	const IS_EMAIL_CONFIRMED = 1;

	const STATUS_DELETED_LABEL = "Удаленный";
	const STATUS_BLOCKED_LABEL = "Заблокированный";
	const STATUS_ACTIVE_LABEL = "Активный";

	const ROLE_DEFAULT_USER_LABEL = "Обычный";
	const ROLE_ADMIN_LABEL = "Администратор";

	const IS_NOW_REGISTERED_LABEL = "Зарегистрирован только что";
	const IS_ALREADY_REGISTERED_LABEL = "Уже был зарегистрирован";

	const IS_NOT_EMAIL_CONFIRMED_LABEL = "Не подтверждена";
	const IS_EMAIL_CONFIRMED_LABEL = "Подтверждена";

	const AVATAR_FOLDER = "avatars";

	const SCENARIO_CREATE_BY_ADMIN_PANEL = 'createByAdminPanel';

	public $password;


	public function init()
	{
		parent::init();

		$this->status = static::STATUS_ACTIVE;
		$this->role = static::ROLE_DEFAULT_USER;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return array_merge(parent::rules(), [
			['email', 'email'],
			// phone is validated by validatePhone()
			['phone', PhoneValidator::className()],
			['phone', 'string', 'min' => 6, 'max' => 18,
				'tooShort' => 'Значение "{attribute}" должно содержать не менее {min} символов',
				'tooLong' => 'Значение "{attribute}" должно содержать не более {max} символов'
			],
			['password', 'string', 'min' => 6,
				'tooShort' => 'Значение "{attribute}" должно содержать не менее {min} символов',
			],
			['password', PasswordValidator::className()],
			[['password', 'name', 'lastname'], 'required', 'on' => static::SCENARIO_CREATE_BY_ADMIN_PANEL],

            ['notificationsEnabled', 'default', 'value' => 1]
		]);
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'ID',
            'name' => 'Имя',
            'lastname' => 'Фамилия',
            'passwordHash' => 'Хэш пароля',
            'login' => 'Логин',
            'status' => 'Статус',
            'role' => 'Роль',
        	'rating' => 'Рейтинг',
            'email' => 'Электронная почта',
        	'isEmailConfirmed' => 'Подтверждена ли почта',
            'phone' => 'Телефон',
        	'isAlreadyRegistered' => 'Зарегистрирован ли был уже',
            'avatarFileId' => 'Аватар',
            'vkUserId' => 'Аккаунт Вконтакте',
            'facebookUserId' => 'Аккаунт Facebook',
            'twitterUserId' => 'Аккаунт Twitter',
            'instagramUserId' => 'Аккаунт Instagram',
        	'authKey' => 'Auth Key',
        	'createdAt' => 'Дата создания',
        	'updatedAt' => 'Дата обновления',
            'cityId' => 'ID города',
            'countryId' => 'ID страны',
        	'password' => 'Пароль',
            'notificationsEnabled' => 'Уведомления включены',
            'lastActiveAt' => 'Дата последнего запроса пользователя к API'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRelations()
    {
    	return $this->hasMany(UserRelation::className(), ['fromUserId' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReverseUserRelations()
    {
    	return $this->hasMany(UserRelation::className(), ['toUserId' => 'userId']);
    }

    public static function statusLabels()
    {
    	return [
    		self::STATUS_DELETED => self::STATUS_DELETED_LABEL,
    		self::STATUS_BLOCKED => self::STATUS_BLOCKED_LABEL,
    		self::STATUS_ACTIVE => self::STATUS_ACTIVE_LABEL
    	];
    }

    public static function roleLabels()
    {
    	return [
    		self::ROLE_DEFAULT_USER => self::ROLE_DEFAULT_USER_LABEL,
    		self::ROLE_ADMIN => self::ROLE_ADMIN_LABEL
    	];
    }

    public static function isAlreadyRegisteredLabels()
    {
    	return [
    		self::IS_NOW_REGISTERED => self::IS_NOW_REGISTERED_LABEL,
    		self::IS_ALREADY_REGISTERED => self::IS_ALREADY_REGISTERED_LABEL
    	];
    }

    public static function isEmailConfirmedLabels()
    {
    	return [
    		self::IS_NOT_EMAIL_CONFIRMED => self::IS_NOT_EMAIL_CONFIRMED_LABEL,
    		self::IS_EMAIL_CONFIRMED => self::IS_EMAIL_CONFIRMED_LABEL
    	];
    }


	public function load($data, $formName = null)
	{
		$result = parent::load($data, $formName);
		if (isset($data['User']['password'])) {
			if (!empty($data['User']['password'])) {
				$this->setPassword($data['User']['password']);
			}
		}
		return $result;
	}

    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert)
    {
    	if (parent::beforeSave($insert)) {
    		$this->updatedAt = time();
			if (empty(trim($this->phone))) {
				$this->phone = null;
			}
			if (empty(trim($this->email))) {
				$this->email = null;
			}

            $currentUser = User::getUser();
            if (!$insert && $this->oldAttributes['role'] == self::ROLE_ADMIN) {
                if ($this->isRoleOrPasswordChanged() && $currentUser->userId != $this->userId) {
                    $this->dropActiveSessions();
                }
            }

    		return true;
    	} else {
    		return false;
    	}
    }


    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeDelete()
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        if ($this->role == self::ROLE_ADMIN && $this->userId == User::getUser()->userId) {
            $this->addError('', 'Вы не можете удалить собственный аккаунт администратора');
            return false;
        }

        /**
         * @var File $file
         */
        $file = $this->avatarFile;
        if (!empty($file)) {
            $file->delete();
        }

        if (!empty($this->events)) {
            foreach ($this->getEvents()->each() as /** @var Event $event */$event) {
                $event->delete();
            }
        }

        if (!empty($this->posts)) {
            foreach ($this->getPosts()->each() as /** @var Post $post */ $post) {
                $post->delete();
            }
        }

        // Удаление пользователя из чатов и обновление количества участников в чатах
        if (!empty($this->chatMembers)) {

            /** @var ChatMember $chatMember */
            foreach ($this->getChatMembers()->each() as $chatMember) {
                try {
                    $chatMember->delete();
                } catch (yii\db\StaleObjectException $e) {
                    $this->addError('', $e);
                    return false;
                } catch (\Throwable $e) {
                    $this->addError('', $e);
                    return false;
                }
            }
        }

        return true;
    }

    /** Получить сериализованный User
     * @return number[]|string[]
     */
    public function serializeShortToArray()
    {
    	$userInfoObj = [];
    	$userInfoObj["userId"] = $this->userId;
    	$userInfoObj["name"] = $this->name;
    	$userInfoObj["avatar"] = $this->getImageUrl();

    	/** @var User $me */
    	$me = User::getUser();
    	if (!empty($me) && ($me->userId != $this->userId)) {
            $userInfoObj['toRelationStatus'] = UserRelation::isUserRelationExists($me->userId, $this->userId); // Подписан ли я на этого пользователя
            $userInfoObj['fromRelationStatus'] = UserRelation::isUserRelationExists($this->userId, $me->userId); // Подписан ли этот пользователь на меня
        }

    	return $userInfoObj;
    }

    /** Получить сериализованный User
     * @return number[]|string[]
     */
    public function serializeToArray()
    {
    	$userInfoObj = $this->serializeShortToArray();
    	$userInfoObj["lastname"] = $this->lastname;
    	$userInfoObj["dateOfBirth"] = (int)$this->dateOfBirth;
        $userInfoObj["rating"] = $this->rating;
        $userInfoObj["email"] = $this->email;
        if (!empty($this->city)) {
            $userInfoObj["city"] = $this->city->serializeToArray();
        }
        if (!empty($this->country)) {
            $userInfoObj["country"] = $this->country->serializeToArray();
        }
        /** @var User $me */
        $me = User::getUser();
        if (!empty($me) && ($me->userId == $this->userId)) {
            $userInfoObj["login"] = $this->login;
        	$userInfoObj["phone"] = $this->phone;
            $userInfoObj["haveProAccount"] = $this->hasActiveSubscription();
        }
    	return $userInfoObj;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($name)
    {
    	return static::findOne(['name' => $name, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
    	return \Yii::$app->security->validatePassword($password, $this->passwordHash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
    	$this->passwordHash = Yii::$app->security->generatePasswordHash($password);
    }

	/**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function getPassword()
    {
    	return '';
    }

    public function isRoleOrPasswordChanged()
    {
        return !empty($this->password) || $this->oldAttributes['role'] != $this->role;
    }

    public function dropActiveSessions()
    {
        \Yii::$app
            ->db
            ->createCommand()
            ->delete('userSession', ['user_id' => $this->userId])
            ->execute();
    }


    // 5 методов для IdentityInterface:
    // - findIdentity($id)
    // - findIdentityByAccessToken($token, $type = null)
    // - getId()
    // - getAuthKey()
    // - validateAuthKey($authKey)

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
    	return User::findOne(['userId' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param string $token
     * @param int $type
     * @return User
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
    	/**
    	 * @var AccessToken $accessTokenModel
    	 */
    	$accessTokenModel = AccessToken::find()->where(['token' => $token])->one();
    	if (empty($accessTokenModel)) {
    		return null;
    	}

    	/**
    	 * @var User $user
    	 */
    	$user = self::findIdentity($accessTokenModel->userId);
    	return $user;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
    	return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
    	return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
    	return $this->getAuthKey() === $authKey;
    }

    /** Регистрация пользователя по email и password
     * @param unknown $email
     * @param unknown $password
     * @return BaseUser
     */
    public static function registrateByEmail($email, $password)
    {
    	/**
    	 * @var BaseUser
    	 */
    	$newUser = new self();
    	$newUser->email = $email;
    	if (!empty($password)) {
            $newUser->setPassword($password);
        }
    	$newUser->role = self::ROLE_DEFAULT_USER;
    	$newUser->status = self::STATUS_ACTIVE;
    	$newUser->isAlreadyRegistered = self::IS_NOW_REGISTERED;
    	$newUser->save();
    	return $newUser;
    }

    /**
     * @param string $phone
     * @return User
     */
    public static function registrateByPhone($phone)
    {
    	/**
    	 * @var User $newUser
    	 */
    	$newUser = new User();
    	$newUser->phone = $phone;
    	$newUser->role = User::ROLE_DEFAULT_USER;
    	$newUser->status = User::STATUS_ACTIVE;
    	$newUser->save();
    	return $newUser;
    }

    /** Получение или создание accessToken для пользователя
     * @return string
     */
    public function getAccessToken()
    {
    	/**
    	 * @var BaseAccessToken $accessToken
    	 */
    	$accessToken = $this->getAccessTokens()->one();
    	if (empty($accessToken)) {
    		/**
    		 * @var BaseAccessToken $accessToken
    		 */
    		$accessToken = new BaseAccessToken();
    		$accessToken->userId = $this->userId;
    		$accessToken->token = \Yii::$app->security->generateRandomString();
    		$accessToken->save();
    	}

    	return $accessToken->token;
    }

    /** Получить пользователя по номеру телефона
     * @param string $phone
     * @return User
     */
    public static function findByPhone($phone)
    {
    	$phone = PhoneValidator::formatPhone($phone);
    	return User::find()->where(['phone' => $phone])->one();
    }

    /** Получить пользователя по email
     * @param string $email
     * @return \yii\db\ActiveRecord[]
     */
    public static function findUserByEmail($email)
    {
    	return self::find()->where(['email' => $email])->one();
    }

    /**
     * Получить пользователя
     * @return User|null
     */
    public static function getUser()
    {
    	return !empty(\Yii::$app->user) ? \Yii::$app->user->getIdentity() : null;
    }

    /** Получить абсолютный путь до аватарки пользователя
     * @return string|NULL
     */
    public function getImageUrl()
    {
        /** @var File $file */
        $file = File::findOne($this->avatarFileId);
        if (!empty($file)) {
            return $file->getAbsoluteFileUrl();
        }

        return null;
    }

    /** Получить абсолютный путь до аватарки пользователя
     * @return string|NULL
     */
    public function getRelativeImageUrl()
    {
        /** @var File $file */
        $file = File::findOne($this->avatarFileId);
        if (!empty($file)) {
            return $file->url;
        }

        return null;
    }

    public function getPreviewImageUrl()
    {
        /** @var File $file */
        $file = File::findOne($this->avatarFileId);
        if (!empty($file)) {
            return $file->getPreviewImageUrl();
        }

        return null;
    }

    /** Сохранить аватарку пользователя
     * @param yii\web\UploadedFile $file
     * @return boolean
     */
    public function saveAvatar($uploadedFile)
    {
        return $this->saveModelFile($uploadedFile, 'avatarFile', self::AVATAR_FOLDER);
    }

    /**
     * Имя с фамилией пользователя
     * @return string
     */
    public function getFullUserName()
    {
    	return $this->name . ' ' . $this->lastname;
    }

	public function getDescription()
    {
		$description = '';
		if (!empty($this->lastname)) {
			$description .= $this->lastname . ' ';
		}
		if (!empty($this->name)) {
			$description .= $this->name . ' ';
		}
		if (!empty($this->login)) {
			$description .= '(' . $this->login . ')';
		}
    	return $description;
    }

    /**
     * Проверка, что пользователь активный
     * @return boolean
     */
    public function isUserActive()
    {
    	return ($this->status == self::STATUS_ACTIVE);
    }

    /**
     * Проверка, что пользователь заблокированный
     * @return boolean
     */
    public function isUserBlocked()
    {
    	return ($this->status == self::STATUS_BLOCKED);
    }

    /**
     * Проверка на обычного пользователя
     */
    public function isUserDefault()
    {
    	return ($this->role == self::ROLE_DEFAULT_USER);
    }

    /**
     * Проверка, что пользователь - администратор
     */
    public function isUserAdmin()
    {
    	return ($this->role == self::ROLE_ADMIN);
    }

    /**
     * Нахождение пользователя по id vk
     * @param integer $socialUserId
     * @return User
     */
    public static function getUserByVkId($socialUserId)
    {
    	return User::find()->where(['vkUserId' => $socialUserId])->one();
    }

    /**
     * Нахождение пользователя по id fb
     * @param integer $socialUserId
     * @return User
     */
    public static function getUserByFbId($socialUserId)
    {
    	return User::find()->where(['facebookUserId' => $socialUserId])->one();
    }

    /**
     * Нахождение пользователя по id instagram
     * @param int $socialUserId     instagramId
     * @return array|User|yii\db\ActiveRecord|null
     */
    public static function getUserByInstagramId($socialUserId)
    {
        return User::find()->where(['instagramUserId' => $socialUserId])->one();
    }

    /**
     * Создание/обновление гео позиции пользователя
     * @param float $latitude
     * @param float $longitude
     * @return UserGeoPosition
     */
    public function saveUserGeoPosition($latitude, $longitude)
    {
        /** @var UserGeoPosition $userGeoPosition */
    	$userGeoPosition = $this->userGeoPosition;
    	if (empty($userGeoPosition)) {
    		$userGeoPosition = new UserGeoPosition();
    		$userGeoPosition->userId = $this->userId;
    	}

    	$userGeoPosition->latitude = $latitude;
    	$userGeoPosition->longitude = $longitude;
    	$userGeoPosition->save();

    	return $userGeoPosition;
    }

    /**
     * Получить ids чатов пользователя
     * @return []
     */
    public function getMyChatIds()
    {
    	$ids = $this->getChatMembers()->select('chatId')->asArray()->column();
    	return $ids;
    }

    /**
     * Получение чата с пользователем
     * @param $anotherUser
     * @return array|yii\db\ActiveRecord|null
     */
    public function getChatWithUser($anotherUser)
    {
        $chat = Chat::find()
            ->joinWith('chatMembers cm1', false)
            ->joinWith('chatMembers cm2', false)
            ->andWhere(['cm1.userId' => $this->userId])
            ->andWhere(['cm2.userId' => $anotherUser])
            ->andWhere(['chat.type' => Chat::TYPE_PERSONAL_CHAT])
            ->one();

        if (!empty($chat)) {
    		return $chat;
    	}

    	return null;
    }

    /**
     * Получение подписки пользователя
     * @return UserSubscription|null
     */
    public function getActiveUserSubscription()
    {
    	$time = time();

    	/**
    	 * @var UserSubscription $userSubscription
    	 */
    	$userSubscription = $this->getUserSubscriptions()
	    	->andWhere(['and',
	    		['<=', 'userSubscription.fromDate', $time],
	    		['>=', 'userSubscription.toDate', $time]
	    	])
	    	->one();

    	return $userSubscription;
    }

    /**
     * Проверить, что у пользователя есть активная подписка
     * @return boolean
     */
    public function hasActiveSubscription()
    {
    	/**
    	 * @var UserSubscription $userSubscription
    	 */
    	$userSubscription = $this->getActiveUserSubscription();
		return !empty($userSubscription);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwnerRequests()
    {
    	return $this->hasMany(Request::className(), ['ownerUserId' => 'userId']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedUserRequests()
    {
    	return $this->hasMany(Request::className(), ['assignedUserId' => 'userId']);
    }
    
    /**
     * @return ActiveQuery
     */
    public static function findActiveUsers()
    {
    	return User::find()->where(['user.status' => User::STATUS_ACTIVE]);
    }


    /**
     * @return array|null|yii\db\ActiveRecord
     */
    public static function getRandomUser()
    {
        $user = self::find()
            ->andWhere(['!=', 'user.userId', self::getUser()->userId])
            ->offset(mt_rand(0, self::find()->count()))
            ->one();
        return $user;
    }

    public function fakeDelete()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        $this->isDeleted = 1;
        AccessToken::deleteAll(['userId' => $this->primaryKey]);

        if ($this->save()) {
            $transaction->commit();
            return true;
        }

        $transaction->rollback();
        return false;
    }
}
