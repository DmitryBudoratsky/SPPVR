<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Class M171113100702Change_PasswordResetRequest
 */
class M171113100702Change_PasswordResetRequest extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
		$this->alterColumn('passwordResetRequest', 'passwordResetToken', $this->string()->unique());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
		$this->alterColumn('passwordResetRequest', 'passwordResetToken', $this->string()->unique()->notNull());
    }
}
