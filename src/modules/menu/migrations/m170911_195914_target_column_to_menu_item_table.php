<?php

use yii\db\Migration;

class m170911_195914_target_column_to_menu_item_table extends Migration
{
    public function safeUp()
    {
        $this->execute(
            "ALTER TABLE `menu_item` 
            ADD COLUMN `target` VARCHAR(45) NULL DEFAULT NULL AFTER `parent_id`");
    }

    public function safeDown()
    {

        $this->dropColumn('menu_item', 'target');


    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170911_195914_target_column_to_menu_item_table cannot be reverted.\n";

        return false;
    }
    */
}
