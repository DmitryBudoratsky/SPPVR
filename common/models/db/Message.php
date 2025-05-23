<?php

namespace common\models\db;

use common\components\Yii;
use OpenApi\Annotations as OA;
use yii\db\ActiveQuery;
use common\components\helpers\FileTypeHelper;
use yii\db\Exception;
use yii\helpers\Json;

/**
 * @OA\Schema(schema="Message", description="Сообщение в чате", properties={
 *     @OA\Property(property="messageId", type="integer", description="ID сообщения"),
 *     @OA\Property(property="senderUserId", type="integer", description="ID отправителя"),
 *     @OA\Property(property="chatId", type="integer", description="ID чата, к которому относится сообщение"),
 *     @OA\Property(property="text", type="string", description="Текст сообщения"),
 *     @OA\Property(property="createdAt", type="integer", description="Дата создания"),
 *     @OA\Property(property="updatedAt", type="integer", description="Дата обновления"),
 *     @OA\Property(property="isIncoming", type="integer", description="Индикация входящего сообщения"),
 *     @OA\Property(property="isSystem", type="integer", description="Индикация системного сообщения"),
 *     @OA\Property(property="status", type="integer", description="Статус сообщения"),
 *     @OA\Property(property="isUpdated", type="integer", description="Обновлено ли сообщение"),
 *     @OA\Property(property="user", ref="#/components/schemas/User", description="Автор сообщения - отправитель"),
 *     @OA\Property(property="file", ref="#/components/schemas/File", description="Файл, отправленный как сообщение в чат (фото, видео и т.д.)."),
 *     @OA\Property(property="quotedMessage", type="object", description="Цитируемое сообщение (Message)"),
 * })
 */
class Message extends BaseMessage
{
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 1;

	const STATUS_DELETED_LABEL = 'Удалено';
	const STATUS_ACTIVE_LABEL = 'Активно';

	const IS_NOT_AUTO_MESSAGE = 0;
	const IS_AUTO_MESSAGE = 1;

	const IS_NOT_UPDATED = 0;
	const IS_UPDATED = 1;
    
    const SOCKET_TYPE = 'message';

    /**
	 * @var array
	 */
	public $cachedSerializedMessage;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'messageId' => 'ID',
			'chatId' => 'ID чат',
			'senderUserId' => 'Отправитель',
			'text' => 'Текст',
			'isAutoMessage' => 'Автоматическое или нет',
			'fileId' => 'ID файла',
			'createdAt' => 'Дата и время отправки',
			'updatedAt' => 'Дата обновления',
            'status' => 'Статус',
            'isSystem' => 'Системное',
            'quotedMessageId' => 'ID цитируемого сообщения',
            'isUpdated' => 'Изменено',
		];
	}


	/**
	 * {@inheritDoc}
	 * @see \yii\db\BaseActiveRecord::beforeDelete()
	 */
	public function beforeDelete()
	{
		if (parent::beforeDelete()) {
			/**
			 * @var File $file
			 */
			$file = $this->file;
			if (!empty($file)) {
				$file->delete();
			}
			return true;
		} else {
			return false;
		}
	}

    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::afterDelete()
     */
	public function afterDelete()
    {

        if (!parent::afterDelete()) {
            return false;
        }

        if (!empty($this->chatId)) {
            $this->chat->updateMessageCount();
        }

        return true;
    }

    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!$insert) {
                $this->isUpdated = static::IS_UPDATED;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
	 * {@inheritDoc}
	 * @see \yii\db\BaseActiveRecord::afterSave()
	 */
	public function afterSave($insert, $changedAttributes)
	{
        parent::afterSave($insert, $changedAttributes);

        if (!empty($this->chat)) {
            Yii::trace('message updateMessageCount()');
            $this->chat->updateMessageCount();
        }
	}

    /**
     * @return bool|void
     */
	public function afterRefresh()
    {
        if (!parent::afterRefresh()) {
            return false;
        }

        if (!empty($this->chatId)) {
            $this->chat->updateMessageCount();
        }

        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotedMessage()
    {
        return $this->hasOne(Message::className(), ['messageId' => 'quotedMessageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseMessages()
    {
        return $this->hasMany(Message::className(), ['quotedMessageId' => 'messageId']);
    }

    /**
	 * @param User $user
	 * @return array
	 */
	public function serializeToArray($user = null)
	{
		if (empty($user)) {
			$user = User::getUser();
		}
		$isUserNotOwner = ($user->userId != $this->senderUserId);
		if ($isUserNotOwner && !empty($this->cachedSerializedMessage)) {
			return $this->cachedSerializedMessage;
		}

		$messageInfoObj = [];
		$messageInfoObj["messageId"] = $this->messageId;
		$messageInfoObj["senderUserId"] = $this->senderUserId;
		$messageInfoObj["chatId"] = (int)$this->chatId;
		$messageInfoObj["text"] = $this->text;
		$messageInfoObj["isAutoMessage"] = !empty($this->isAutoMessage) ? static::IS_AUTO_MESSAGE : static::IS_NOT_AUTO_MESSAGE;
		$messageInfoObj["createdAt"] = $this->createdAt;
		$messageInfoObj["isIncoming"] = ($this->senderUserId != $user->userId) ? 1 : 0;

		$messageInfoObj['isSystem'] = $this->isSystem;

		/** @var UserMessage $userMessage */
		$userMessage = $this->getUserMessages()->andWhere(['userMessage.userId' => $user->userId])->one();
        $messageInfoObj["status"] = !empty($userMessage) ? $userMessage->status : UserMessage::STATUS_SENDED;

        $messageInfoObj["isUpdated"] = !empty($this->isUpdated) ? static::IS_UPDATED : static::IS_NOT_UPDATED;

        if (!empty($this->quotedMessage)) {
            $messageInfoObj["quotedMessage"] = $this->quotedMessage->serializeToArray($user);
        }


        // TODO: [code review] Valery Gorokhov 2021-07-15
        // использовать $this->file
        // удалить условие empty($this->text). В реальных проектах возможен кейс файлов с подписью
		if (empty($this->text) && !empty($this->fileId)) {
			/**
			 * @var File $file
			 */
			$file = $this->getFile()->one();
			if (!empty($file)) {
				$fileInfo = $file->serializeToArray();
				if (!empty($fileInfo)) {
					$messageInfoObj["file"] = $fileInfo;
				}
			}
		}

		/**
		 * @var User $senderUser
		 */

		$senderUser = $this->senderUser;
		if (!empty($senderUser)) {
			$messageInfoObj["user"] = $senderUser->serializeShortToArray();
		}
		if ($isUserNotOwner) {
			$this->cachedSerializedMessage = $messageInfoObj;
		}
		return $messageInfoObj;
	}

	/**
	 * @return boolean
	 */
	public function updateUserMessages($includeSender = false)
	{
	    /** @var Chat $chat */
		$chat = $this->chat;
		if (empty($chat)) {
			$this->addError('chatId', 'Чат не найден');
			return false;
		}

        $userMessageData = [];
        $serializedMessage = null;

        /** @var ActiveQuery $chatMembersQuery */
        $chatMembersQuery = $chat->getChatMembers();
        foreach ($chatMembersQuery->each() as /** @var ChatMember $chatMember */ $chatMember) {
        	$status = UserMessage::STATUS_SENDED;
            if ($chatMember->userId != $this->senderUserId || $includeSender == true) {
                // TODO: [code review] Valery Gorokhov 2021-07-15
                // Убрать оптимизацию, т.к. сериализация должна быть индивидуальной для пользователей
	            if (empty($serializedMessage)) {
	                /**
	                 * @var User $chatMemberUser
	                 */
	                $chatMemberUser = $chatMember->user;
	                $serializedMessage = $this->serializeToArray($chatMemberUser);
	            }
	            UserMessage::notifyMessageCreated($chatMember->userId, $serializedMessage);
        	}

            $userMessageData[] = [$this->messageId, $chatMember->userId, $status, time(), time()];
        }

        try {
            // Массовое заполнение userMessage, т.к. циклом заполнялось бы очень медленно
            \Yii::$app->db->createCommand()
                ->batchInsert('userMessage', ['messageId', 'userId', 'status', 'createdAt', 'updatedAt'], $userMessageData)
                ->execute();
        } catch (Exception $e) {
            $this->addError('userMessage', 'Не удалось создать userMessages: ' . $e);
            return false;
        }

		return true;
	}

	/** Получение всех сообщений чата, у которых статус - не удалены
	 * @param integer $chatId
	 * @return \yii\db\ActiveQuery
	 */
	public static function findChatMessages($chatId)
	{
		/**
		 * @var User $user
		 */
		$user = User::getUser();
		/**
		 * @var ActiveQuery $messagesQuery
		 */
		$messagesQuery = self::find()
			// ->select('message.*')
			->joinWith('userMessages', false)
			->andWhere([
				'message.chatId' => $chatId,
				'userMessage.userId' => $user->userId,
			])
			->andWhere(['!=', 'userMessage.status', UserMessage::STATUS_DELETED])
			->groupBy('message.messageId')
			->orderBy(['message.messageId' => SORT_DESC]);

		return $messagesQuery;
	}

	/**
	 * Sends push notification to the chat members.
	 */
	public function sendPushNotifications()
	{
        $chat = $this->chat;
		if (empty($chat)) {
			return false;
		}
		if ($chat->isHiddenChat()) {
			return false;
		}
		//  Не отправляет пуши если системное сообщение
		if (!empty($this->isSystem)) {
		    return true;
        }

        $senderUser = $this->senderUser;
		$pushText = $this->getPushText();
		$pushTextToSend = $senderUser->name . ": " . $pushText;
		$serializedChat = $this->chat->serializeToArrayShort();
		$pushItemData = Json::encode(['chat' => $serializedChat]);
		$pushData = [];
		foreach ($chat->getChatMembers()->each() as /** @var ChatMember $chatMember */ $chatMember) {
            if (!$chatMember->isNotificationEnabled()) {
                continue;
            }
            if (empty($chatMember->userId)) {
                continue;
            }
            if (($chatMember->userId == $this->senderUserId)) {
                continue;
            }
            $pushData[] = ["У вас новое сообщение", $pushTextToSend, self::tableName(), $chatMember->userId, PushNotification::STATUS_DEFAULT , time(), time(), $pushItemData];
        }
        try {
		    // Массовое заполнение pushNotification, т.к. циклом заполнялось бы очень медленно
            \Yii::$app->db->createCommand()
                ->batchInsert('pushNotification', ['title', 'message', 'type', 'userId', 'status', 'createdAt', 'updatedAt', 'data'], $pushData)
                ->execute();
        } catch (Exception $e) {
            $this->addError('pushNotification', 'Не удалось создать pushNotifications: ' . $e);
            return false;
        }
        return true;
	}

	/**
	 * @return string
	 */
	private function getPushText()
	{
		$pushText = '';

		if (!empty($this->text)) {
			$pushText = mb_strimwidth($this->text, 0, 150) . '...';
		} else {
			/**
			 * @var File $file
			 */
			$file = $this->getFile()->one();
			if (!empty($file)) {
				$fileTitle = FileTypeHelper::getTypeLabel($file->type);
				$pushText = "Вам прислали " . $fileTitle;
			}
		}

		return $pushText;
	}

	/**
	 * Получить количество всех непрочитанных сообщений пользователя во всех чатах
	 * @param integer $userId
	 * @return integer
	 */
	public static function getTotalUnreadedMessageCount($userId)
	{
		$messagesCount = self::find()
			->joinWith('userMessages')
			->andWhere([
                'message.isSystem' => 0,
                'message.status' => self::STATUS_ACTIVE,
                'userMessage.userId' => $userId,
                'userMessage.status' => UserMessage::STATUS_SENDED,
            ])
            ->andWhere(['!=', 'message.senderUserId', $userId])
            ->groupBy(['message.messageId'])
			->count();

		return (int)$messagesCount;
	}

    /**
     * Задать статус сообщения
     * @param $status
     * @return bool
     */
    public function setStatus($status)
    {
        if ($status === self::STATUS_DELETED) {
            $this->status = self::STATUS_DELETED;

            UserMessage::updateAll(
                ['status' => UserMessage::STATUS_DELETED, 'updatedAt' => time()],
                ['messageId' => $this->messageId]);

        } elseif ($status === self::STATUS_ACTIVE) {
            $this->status = self::STATUS_ACTIVE;
        } else {
            $this->addError('', 'Неправильный статус');
            return false;
        }

        if (!$this->save()) {
            $this->addError('', 'Не удалось задать статус');
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isUpdatedMessage()
    {
        return ($this->isUpdated == static::IS_UPDATED);
    }
}
