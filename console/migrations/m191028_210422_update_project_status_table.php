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

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191028_210422_update_project_status_table cannot be reverted.\n";

        return false;
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
