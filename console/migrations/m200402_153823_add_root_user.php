<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m200402_153823_add_root_user
 */
class m200402_153823_add_root_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = new User();
        $user->username = 'root';
        $user->setPassword('qwerty123456');
        $user->generateAuthKey();
        $user->email = 'root@root.ru';
        $user->status = User::STATUS_ACTIVE;
        $user->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $user = User::findOne(['username' => 'root']);
        $user->delete();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200402_153823_add_root_user cannot be reverted.\n";

        return false;
    }
    */
}
