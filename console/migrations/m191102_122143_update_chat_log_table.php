<?php

use yii\db\Migration;

/**
 * Class m191102_122143_update_chat_log_table
 */
class m191102_122143_update_chat_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('chat_log', 'type', $this->integer());
        $this->addForeignKey('fk_chat_log_task_id', 'chat_log', 'task_id', 'task', 'id');
        $this->addForeignKey('fk_chat_log_project_id', 'chat_log', 'project_id', 'project', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_chat_log_task_id', 'chat_log');
        $this->dropForeignKey('fk_chat_log_project_id', 'chat_log');
        $this->dropColumn('chat_log', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191102_122143_update_chat_log_table cannot be reverted.\n";

        return false;
    }
    */
}
