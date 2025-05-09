<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Class M180227081547Add_rating
 */
class M180227081547Add_rating extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
		$this->addColumn('user', 'rating', $this->float()->defaultValue(1));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
		$this->dropColumn('user', 'rating');
    }
}
