<?php

namespace common\models\db;

use OpenApi\Annotations as OA;
use yii\bootstrap4\Html;
use yii\db\ActiveQuery;

/**
 * @OA\Schema(schema="Chat", description="Чат", properties={
 *     @OA\Property(property="chatId", type="integer", description="ID чата"),
 *     @OA\Property(property="title", type="string", description="Заголовок чата"),
 *     @OA\Property(property="type", type="integer", description="Тип чата (1 - персональный, 2 - групповой)"),
 *     @OA\Property(property="image", type="string", description="Картинка чата"),
 *     @OA\Property(property="messageCount", type="integer", description="Количество сообщений в чате"),
 *     @OA\Property(property="chatMemberCount", type="integer", description="Количество собеседников в чате"),
 *     @OA\Property(property="unreadMessageCount", type="integer", description="Количество непрочитанных сообщений в чате"),
 *     @OA\Property(property="notificationEnabled", type="integer", description="Индикация включения отправки push уведомления о новом сообщении авторизованному пользователю"),
 *     @OA\Property(property="userRole", ref="#/components/schemas/ChatMemberRole"),
 *     @OA\Property(property="isChatMember", type="integer", description="Является ли пользователь членом этого чата или нет"),
 *     @OA\Property(property="lastMessage", ref="#/components/schemas/Message", description="Последнее сообщение в чате"),
 *     @OA\Property(property="interlocutor", ref="#/components/schemas/User", description="Пользователь - собеседник (только для персонального чата)"),
 *     @OA\Property(property="createdAt", type="integer", description="Дата создания"),
 *     @OA\Property(property="updatedAt", type="integer", description="Дата обновления"),
 * })
 */
class Chat extends BaseChat
{
	const IS_NOT_HIDDEN = 0;
	const IS_HIDDEN = 1;

	const TYPE_PERSONAL_CHAT = 1;
	const TYPE_GROUP_CHAT = 2;

	const IS_NOT_HIDDEN_LABEL = "Не скрыт";
	const IS_HIDDEN_LABEL = "Скрыт";

	const TYPE_PERSONAL_CHAT_LABEL = "Персональный";
	const TYPE_GROUP_CHAT_LABEL = "Групповой";

	const FOLDER_CHAT_IMAGE = "chat-image";

	const IS_PUBLIC = 1;
	const IS_NOT_PUBLIC = 0;

	const IS_PUBLIC_LABEL = "Публичный";
	const IS_NOT_PUBLIC_LABEL = "Не публичный";
    const MIN_CHAT_MEMBERS = 2;

    const SCENARIO_CREATE_GROUP = 'create-group';

    /**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'chatId' => 'ID',
			'isHidden' => 'Скрытый',
			'createdAt' => 'Дата создания',
			'updatedAt' => 'Дата обновления',
			'title' => 'Название',
			'type' => 'Тип',
			'avatarFileId' => 'Аватар',
			'messageCount' => 'Количество сообщений',
			'chatMemberCount' => 'Количество пользователей',
            'isPublic' => 'Публичный',
		];
	}

    public function rules()
    {
        $rules = parent::rules();
        $newRules = [
            [
                ['title'],
                'unique',
                'targetAttribute' => ['title'],
                'message' => 'Чат с таким названием уже существует.'
            ],
            [['title'], 'trim'],
            ['title', 'string', 'length' => [1, 50]],

            [['title'], 'required', 'on' => self::SCENARIO_CREATE_GROUP],
        ];
        return array_merge($rules, $newRules);
    }

    public function afterDelete()
    {
        parent::afterDelete();

        ChatMember::deleteAll(['chatId' => $this->chatId]);
    }

	public static function isHiddenLabels()
	{
		return [
			self::IS_NOT_HIDDEN => self::IS_NOT_HIDDEN_LABEL,
			self::IS_HIDDEN => self::IS_HIDDEN_LABEL
		];
	}

	public static function typeLabels()
	{
		return [
			self::TYPE_PERSONAL_CHAT => self::TYPE_PERSONAL_CHAT_LABEL,
			self::TYPE_GROUP_CHAT => self::TYPE_GROUP_CHAT_LABEL
		];
	}

    public static function isPublicLabels()
    {
        return [
            self::IS_NOT_PUBLIC => self::IS_NOT_PUBLIC_LABEL,
            self::IS_PUBLIC => self::IS_PUBLIC_LABEL,
        ];
    }

	/** Сериализация Chat
	 * @return []
	 */
	public function serializeToArrayShort()
	{
		$chatInfoObj = [];
		$chatInfoObj["chatId"] = $this->chatId;
		return $chatInfoObj;
	}

	/** Сериализация Chat
	 * @param User $user
	 * @return []
	 */
	public function serializeToArray($user = null)
	{
		$chatInfoObj = $this->serializeToArrayShort();

		if (empty($user)) {
            /** @var User $user */
			$user = User::getUser();
		}

        /** @var Message $lastMessage */
		$lastMessage = $this->getLastMessage($user->userId);
		if (!empty($lastMessage)) {
			$chatInfoObj['lastMessage'] = $lastMessage->serializeToArray($user);
		}

        /** @var ActiveQuery $interlocutorsQuery */
		$interlocutorsQuery = $this->findInterlocutors();
		$chatInfoObj["title"] = $this->getTitle();
        $chatInfoObj['image'] = $this->getAvatarUrl();
		if ($this->isPersonalChat()) {
			/**
			 * @var User $interlocutor
			 */
			$interlocutor = $interlocutorsQuery->one();
			if (!empty($interlocutor)) {
				$chatInfoObj["interlocutor"] = $interlocutor->serializeShortToArray();
			}
		}

		$chatInfoObj["messageCount"] = $this->messageCount;
		$chatInfoObj["type"] = $this->type;
		$chatInfoObj["chatMemberCount"] = $this->chatMemberCount;
		$chatInfoObj["unreadMessageCount"] = $this->getUnreadMessageCount();
		$chatInfoObj["updatedAt"] = $this->updatedAt;

		if (!empty($user)) {
            /** @var ChatMember $chatMember */
            $chatMember = $this->getChatMember($user);
			if (!empty($chatMember)) {
				$chatInfoObj["notificationEnabled"] = !empty($chatMember->notificationEnabled);
				$chatInfoObj["userRole"] = $chatMember->chatRole;
			}
			if ($this->isPublicChat() && $this->isGroupChat()) {
            	$chatInfoObj['isChatMember'] = !empty($chatMember);
        	}
		}
		return $chatInfoObj;
	}

    /**
     * @param null $user
     * @return ChatMember
     */
	public function getChatMember($user = null)
    {
        if (empty($user)) {
            /** @var User $user */
            $user = User::getUser();
        }

        /** @var ChatMember $chatMember */
        $chatMember = $this->getChatMembers()
        	->andWhere(['chatMember.userId' => $user->userId])
            ->one();

        return $chatMember;
    }

	/**
	 * Получить название чата
	 * @return string
	 */
	public function getTitle()
	{
		if (!empty($this->title)) {
			return $this->title;
		}
        $names = [];
        /**
         * @var ActiveQuery $interlocutorsQuery
         */
        $interlocutorsQuery = $this->findInterlocutors();
        foreach ($interlocutorsQuery->each() as /** @var User $interlocutorUser */ $interlocutorUser) {
            $names[] = $interlocutorUser->name;
        }

        if (!empty($names)) {
            $title = implode(', ', $names);
        } else {
            $title = "Чат №" . $this->chatId;
        }

		return $title;
	}

    /**
     * @param null $userId
     * @return array|\yii\db\ActiveRecord|null
     */
	public function getLastMessage($userId = null)
	{
		if (empty($userId)) {
            /** @var User $user */
			$user = User::getUser();
			$userId = $user->userId;
		}
		return $this->findMessagesByUser($userId)->one();
	}

	/**
	 * Получение неудаленных сообщений в чате у пользователя
	 * @param integer $userId
	 * @return \yii\db\ActiveQuery
	 */
	public function findMessagesByUser($userId)
	{
		return $this->findLastMessages()
			->joinWith('userMessages', false)
			->andWhere(['userMessage.userId' => $userId])
			->andWhere(['!=', 'userMessage.status', UserMessage::STATUS_DELETED]);
	}

	/**
	 * Найти последнее сообщения в чате
	 * @return ActiveQuery
	 */
	public function findLastMessages()
	{
		return $this->getMessages()
			->orderBy(['message.messageId' => SORT_DESC])
            ->andWhere(['message.isSystem' => false]);
	}

	/**
	 * @return integer
	 */
	public function getUnreadMessageCount()
	{
		/**
		 * @var User $user
		 */
		$user = User::getUser();
		if (empty($user)) {
			return 0;
		}

		$count = (int) Message::find()
            ->joinWith('userMessages')
            ->andWhere([
                'message.chatId' => $this->chatId,
                'message.isSystem' => 0,
                'message.status' => Message::STATUS_ACTIVE,
                'userMessage.userId' => $user->userId,
                'userMessage.status' => UserMessage::STATUS_SENDED,
            ])
            ->andWhere(['!=', 'message.senderUserId', $user->userId])
            ->groupBy(['message.messageId'])
            ->count();

		return $count;
	}

	/**
	 * @return ActiveQuery
	 */
	public function findInterlocutors()
	{
        /** @var User $user */
		$user = User::getUser();

		/** @var ActiveQuery $query */
		$query = User::find()
			->joinWith('chatMembers')
			->andWhere(['chatMember.chatId' => $this->chatId]);
		if (!empty($user)) {
			$query->andWhere(['!=', 'chatMember.userId', $user->userId]);
		}
		return $query;
	}

	/** Помечание всех сообщений в чате удаленными
	 * @param User $user
	 * @return boolean
	 */
	public function clearChatForUser($user)
	{
	    /** @var ActiveQuery $query */
		$query = UserMessage::find()
            ->andWhere(['!=','userMessage.status', UserMessage::STATUS_DELETED])
			->joinWith('message', false)
			->andWhere([
				'message.chatId' => $this->chatId,
				'userMessage.userId' => $user->userId,
			]);

        foreach ($query->each() as /** @var UserMessage $userMessage */ $userMessage) {
			$userMessage->status = UserMessage::STATUS_DELETED;
			if (!$userMessage->save()) {
				$this->addErrors($userMessage->getErrors());
				$this->addError('userMessage', 'Не удалось отметить сообщение в чате удаленным');
				return false;
			}
		}

		return true;
	}

	/**
	 * Добавление членов чата.
	 * @param User $user
	 * @param User $anotherUser
	 * @return Chat
	 */
	public static function createChatForUsers($user, $anotherUser)
	{
		$chat = new Chat();
		$chat->isHidden = Chat::IS_NOT_HIDDEN;
        $chat->type = Chat::TYPE_PERSONAL_CHAT;
		$chat->save();

		ChatMember::getChatMember($user->userId, $chat->chatId);
		ChatMember::getChatMember($anotherUser->userId, $chat->chatId);

		return $chat;
	}

	/**
	 * Чат является персональным.
	 * @return boolean
	 */
	public function isPersonalChat()
	{
		return ($this->type === self::TYPE_PERSONAL_CHAT);
	}

	/**
	 * Чат является групповым.
	 * @return boolean
	 */
	public function isGroupChat()
	{
		return ($this->type === self::TYPE_GROUP_CHAT);
	}

    /**
     * Чат является публичным.
     * @return boolean
     */
    public function isPublicChat()
    {
        return ($this->isPublic === self::IS_PUBLIC);
    }

    /**
     * Чат является Скрытым.
     * @return boolean
     */
    public function isHiddenChat()
    {
        return ($this->isHidden === self::IS_HIDDEN);
    }

	/** Получить абсолютный путь до аватарки чата.
	 * @return string|NULL
	 */
	public function getAvatarUrl()
	{
	    if ($this->isGroupChat()) {
            if (!empty($this->avatarFileId)) {
                /**
                 * @var ImageFile $imageFile
                 */
                $imageFile = ImageFile::find()->where(['fileId' => $this->avatarFileId])->one();
                if (!empty($imageFile)) {
                    return $imageFile->getAbsolutePreviewImageUrl();
                }
            }
        } else {
            /**
             * @var User $interlocutorUser
             */
            $interlocutorUser = $this->findInterlocutors()->one();
            if (!empty($interlocutorUser)) {
                return $interlocutorUser->getImageUrl();
            }
        }

	    return null;
	}

	/**
	 * Сохранить аватар чата.
	 *
	 * @param file $file
	 * @return boolean
	 */
	public function saveAvatar($file)
	{
		/**
		 * @var File $fileModel
		 */
		$fileModel = $this->avatarFile;
		if (!empty($fileModel)) {
			$fileModel->delete();
		}

		/**
		 * @var File $fileModel
		 */
		$fileModel = new File();

		$fileModel->upload($file, self::FOLDER_CHAT_IMAGE);
		if (!$fileModel->save()) {
			return false;
		}

		$this->avatarFileId = $fileModel->fileId;

		return true;
	}

    /**
     * Обновление количества пользователей чата.
     * @return bool
     */
	public function updateMemberCount()
	{
		$this->chatMemberCount = (int)$this->getChatMembers()->count();

		if (!$this->save()) {
            return false;
        }

		\Yii::trace("Chat, updateMemberCount(), save chat, errors:" . var_export($this->getErrors(), true));
		return true;
	}

    /**
     * Обновление количества сообщений в чате
     */
	public function updateMessageCount()
    {
        $this->messageCount = (int)$this->getMessages()
            ->andWhere([
                'message.status' => Message::STATUS_ACTIVE,
                'message.isSystem' => 0
            ])->count();

        $this->save();
        \Yii::trace("Chat, updateMessageCount(), save chat, errors:" . var_export($this->getErrors(), true));

        return $this->messageCount;
    }

    /**
     * Получить доступные чаты
     * @param $type
     * @param $limit
     * @param int $offset
     * @return ActiveQuery
     */
    public static function findChats($type = null, $offset = 0, $limit = null)
    {
        if (empty($offset)) {
            $offset = 0;
        }
        if (empty($limit)) {
            $limit = \Yii::$app->params['limitRecordsOnPage'];
        }

        $query = self::find();

        if (!empty($type)) {
            $query->andWhere(['chat.type' => $type]);
        }

        $query->joinWith('chatMembers', false)
            ->andWhere(['chatMember.userId' => User::getUser()->userId])
            //защита, чтобы не показывались приватные чаты, в которых только текущий юзер
            ->andWhere(['or',
                ['chat.type' => self::TYPE_GROUP_CHAT],
                ['and',
                    ['chat.type' => self::TYPE_PERSONAL_CHAT],
                    ['>=', 'chat.chatMemberCount', self::MIN_CHAT_MEMBERS],
                    ['>', 'chat.messageCount', 0]
                ]
            ])
            ->limit($limit)
            ->offset($offset);
        $query->orderBy([
            'chat.updatedAt' => SORT_DESC,
            'chat.chatId' => SORT_DESC,
        ])->groupBy('chat.chatId');

        return $query;
    }

    /** Получить абсолютный путь до аватарки чата
     * @return string|NULL
     */
    public function getImageUrl()
    {
        /**
         * @var ImageFile $imageFile
         */
        $imageFile = ImageFile::find()->where(['fileId' => $this->avatarFileId])->one();
        if (!empty($imageFile)) {
            return $imageFile->getAbsolutePreviewImageUrl();
        }

        return null;
    }

    /**
     * Отправляет системные сообщения в чате
     * Например при добавлении/выходе пользователя из чата
     * @param $text
     * @return bool
     */
    public function sendSystemMessage($text)
    {
        if ($this->isPersonalChat()) {
            $this->addError('message', 'Чат не групповой.');
            return false;
        }

        /**
         * @var Message $message
         */
        $message = new Message();
        $message->chatId = $this->chatId;
        $message->text = $text;
        $message->isSystem = 1;
        $message->save();
        if ($message->hasErrors()) {
            $this->addErrors($message->getErrors());
            $this->addError('message', 'Не удалось сохранить информацию о сообщении.');
            return false;
        }
        $message->updateUserMessages();
        return true;
    }

    public function getLastMessageText()
    {
        /**
         * @var Message $lastMessage
         */
        $lastMessage = $this->findLastMessages()->one();
        $lastMessageText = '';
        $lastMessageImg = '';
        if (!empty($lastMessage)) {
            $lastMessageText = '';
            if (!empty($lastMessage->senderUser->name)) {
                $lastMessageText .= $lastMessage->senderUser->name . ': ';
            }
            $lastMessageText .= $lastMessage->text;

            $lastMessageImg = (!empty($lastMessage->file)) ? Html::img($lastMessage->file->getAbsoluteFileUrl(),
                ['alt' => 'icon', 'class' => 'previewImage']) : '';
        }

        return (!empty($lastMessageText)) ? $lastMessageText : $lastMessageImg;
    }
}
