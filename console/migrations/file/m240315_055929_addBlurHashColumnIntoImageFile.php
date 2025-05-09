<?php

namespace console\migrations\file;

use yii\db\Migration;

/**
 * Class m240315_055929_addBlurHashColumnIntoImageFile
 */
class m240315_055929_addBlurHashColumnIntoImageFile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('imageFile', 'blurHash', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('imageFile', 'blurHash');

        return true;
    }
}
