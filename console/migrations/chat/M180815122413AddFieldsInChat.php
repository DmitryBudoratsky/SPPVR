<?php

namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Class M180815122413AddFieldsInChat
 */
class M180815122413AddFieldsInChat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('chat', 'title', $this->string());
		$this->addColumn('chat', 'type', $this->integer()->notNull()->defaultValue(1));
		$this->addColumn('chat', 'avatarFileId', $this->integer());
		
		$this->addForeignKey(
			'fk-chat-file-1',
			'chat',
			'avatarFileId',
			'file',
			'fileId',
			'SET NULL',
			'SET NULL'
		);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('chat', 'title');
		$this->dropColumn('chat', 'type');
		$this->dropForeignKey('fk-chat-file-1','chat');
		$this->dropColumn('chat', 'avatarFileId');
    }
}
