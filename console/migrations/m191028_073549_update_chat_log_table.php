<?php

use yii\db\Migration;

/**
 * Class m191028_073549_update_chat_log_table
 */
class m191028_073549_update_chat_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('chat_log', 'project_id', $this->integer()->after('message'));
        $this->addColumn('chat_log', 'task_id', $this->integer()->after('project_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('chat_log', 'project_id');
        $this->dropColumn('chat_log', 'task_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191028_073549_update_chat_log_table cannot be reverted.\n";

        return false;
    }
    */
}
