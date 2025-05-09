<?php
namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Handles adding isSystem to table `message`.
 */
class m181224_084434_add_isSystem_column_to_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('message', 'isSystem', $this->boolean()->defaultValue(false)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('message', 'isSystem');
    }
}
