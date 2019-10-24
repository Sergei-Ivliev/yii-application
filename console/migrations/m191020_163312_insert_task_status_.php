<?php

use yii\db\Migration;

/**
 * Class m191020_163312_insert_task_status_
 */
class m191020_163312_insert_task_status_ extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('task_status', ['name' => 'NEW']);
        $this->insert('task_status', ['name' => 'IN_PROGRESS']);
        $this->insert('task_status', ['name' => 'ON_TESTING']);
        $this->insert('task_status', ['name' => 'READY']);
        $this->insert('task_status', ['name' => 'ARCHIVE']);

        $this->insert('task_priority', ['name' => 'LOW']);
        $this->insert('task_priority', ['name' => 'NORMAL']);
        $this->insert('task_priority', ['name' => 'HIGH']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('task_status', ['name' => 'NEW']);
        $this->delete('task_status', ['name' => 'IN_PROGRESS']);
        $this->delete('task_status', ['name' => 'ON_TESTING']);
        $this->delete('task_status', ['name' => 'READY']);
        $this->delete('task_status', ['name' => 'ARCHIVE']);

        $this->delete('task_priority', ['name' => 'LOW']);
        $this->delete('task_priority', ['name' => 'NORMAL']);
        $this->delete('task_priority', ['name' => 'HIGH']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191020_163312_insert_task_status_ cannot be reverted.\n";

        return false;
    }
    */
}
