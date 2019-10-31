<?php

use yii\db\Migration;

/**
 * Class m191028_210422_update_project_status_table
 */
class m191028_210422_update_project_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('project_status', ['name' => 'В работе']);
        $this->insert('project_status', ['name' => 'Планируется']);
        $this->insert('project_status', ['name' => 'Завершён']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('project_status', ['name' => 'Завершён']);
        $this->delete('project_status', ['name' => 'Планируется']);
        $this->delete('project_status', ['name' => 'В работе']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191028_210422_update_project_status_table cannot be reverted.\n";

        return false;
    }
    */
}
