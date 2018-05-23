<?php

use yii\db\Migration;

/**
 * Class m180523_181143_multisite_id_columns
 */
class m180523_181143_multisite_id_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('menu_location', 'site_id', 'INT(11) NULL');
        $this->addColumn('menu', 'site_id', 'INT(11) NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('menu_location', 'site_id');
        $this->dropColumn('menu', 'site_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180523_181143_multisite_id_columns cannot be reverted.\n";

        return false;
    }
    */
}
