<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m191017_113717_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'status_id' => $this->integer()->notNull(),
            'description' => $this->text()->notNull(),
            'created_at' => $this->date()->notNull(),
            'updated_at' => $this->date(),
            'author_id' => $this->string()->notNull(),
            'executor_id' => $this->string()->notNull(),
            'priority_id' => $this->Integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task}}');
    }
}
