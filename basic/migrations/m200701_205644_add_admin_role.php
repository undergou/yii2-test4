<?php

use yii\db\Migration;

/**
 * Class m200701_205644_add_admin_role
 */
class m200701_205644_add_admin_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $role = Yii::$app->authManager->createRole('admin');
        $role->description = 'Администратор';
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
        echo "m200701_205644_add_admin_role cannot be reverted.\n";

        return false;
    }
    */
}
