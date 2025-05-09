<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23.10.18
 * Time: 15:59
 */

namespace common\components\helpers;


use common\models\db\Chat;

class ChatHelper
{

    /**
     * Форматирует красивое имя для заголовка страницы чата
     * @param Chat $chat
     * @return string
     */
    public static function formatChatName($chat)
    {
        if (empty($chat)) {
            return false;
        }

        $result = "Чат №{$chat->chatId}: ";

        if ($chat->type === Chat::TYPE_GROUP_CHAT) {
            $result .= Chat::TYPE_GROUP_CHAT_LABEL;
        } else {
            $result .= Chat::TYPE_PERSONAL_CHAT_LABEL;
        }

        $result .= " чат {$chat->title}";

        return $result;

    }

}