<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m200426_114204_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(11)->notNull(),
            'username' => $this->string(64)->notNull(),
            'email' => $this->string(64)->notNull(),
            'displayname' => $this->string(64)->notNull(),
            'password' => $this->string(64)->notNull(),
            'resetKey' => $this->string(64)->notNull(),
            'authKey' => $this->string(64)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
