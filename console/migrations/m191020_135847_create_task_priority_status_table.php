<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_priority_status}}`.
 */
class m191020_135847_create_task_priority_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_priority_status}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task_priority_status}}');
    }
}
