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

    /**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'chatId' => 'ID',
			'createdAt' => 'Дата создания',
			'updatedAt' => 'Дата обновления',
		];
	}
}
