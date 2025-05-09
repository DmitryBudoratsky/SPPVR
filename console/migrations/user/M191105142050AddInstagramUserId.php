<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Class M191105142050AddInstagramUserId
 */
class M191105142050AddInstagramUserId extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'instagramUserId', $this->string()->after('twitterUserId'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'instagramUserId');
    }
}
