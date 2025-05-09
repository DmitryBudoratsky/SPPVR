<?php
namespace console\migrations\user;

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m191004_094053_add_notificationsEnabled_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'notificationsEnabled', $this->tinyInteger()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'notificationsEnabled');
    }
}
