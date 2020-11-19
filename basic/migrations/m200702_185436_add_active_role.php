<?php

use yii\db\Migration;

/**
 * Class m200702_185436_add_active_role
 */
class m200702_185436_add_active_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $role = Yii::$app->authManager->createRole('active');
        $role->description = 'Активрованный пользователь';
        Yii::$app->authManager->add($role);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->authManager->removeAll();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200702_185436_add_active_role cannot be reverted.\n";

        return false;
    }
    */
}
