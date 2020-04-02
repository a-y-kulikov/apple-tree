<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apples}}`.
 */
class m200402_154407_create_apples_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apples}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string(50)->notNull(),
            'created_at' => $this->integer(11)->unsigned()->notNull(),
            'down_at' => $this->integer(11)->unsigned(),
            'status' => $this->string(50)->notNull(),
            'percents' => $this->integer(3)->unsigned()->notNull()
        ]);

        $this->createIndex('apples_status', '{{%apples}}', 'status');
        $this->createIndex('apples_down_at', '{{%apples}}', 'down_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apples}}');
    }
}
