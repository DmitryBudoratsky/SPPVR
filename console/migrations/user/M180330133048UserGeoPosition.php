<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Class M180330133048UserGeoPosition
 */
class M180330133048UserGeoPosition extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->createTable('userGeoPosition', [
    		'userGeoPositionId' => $this->primaryKey(),
    		'userId' => $this->integer()->notNull(),
    		'latitude' => $this->decimal(13,8)->notNull(),
    		'longitude' => $this->decimal(13,8)->notNull(),
    		'createdAt' => $this->bigInteger(),
    		'updatedAt' => $this->bigInteger()
    	]);
    	
    	$this->addForeignKey(
    		"fk-userGeoPosition-user-1",
    		"userGeoPosition",
    		"userId",
    		"user",
    		"userId",
    		'CASCADE',
    		'CASCADE'
    	);
    	
    	$this->createIndex('UI-userGeoPosition-1', 'userGeoPosition', ['userId'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropForeignKey('fk-userGeoPosition-user-1', 'userGeoPosition');
		$this->dropTable('userGeoPosition');
    }
}
