<?php

use yii\db\Migration;

/**
 * Class m191028_072125_rename_user_id_to_author_id_in_project_table
 */
class m191028_072125_rename_user_id_to_author_id_in_project_table extends Migration
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
        echo "m191028_072125_rename_user_id_to_author_id_in_project_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191028_072125_rename_user_id_to_author_id_in_project_table cannot be reverted.\n";

        return false;
    }
    */
}
