<?php

use yii\db\Migration;

class m161129_155755_menu_tables extends Migration
{
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `menu` (
                `menu_id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(45) NOT NULL,
                `slug` VARCHAR(45) NOT NULL,
                `description` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`menu_id`),
            INDEX `slug` (`slug` ASC))
        ENGINE = InnoDB");
        
        $this->execute(
                "CREATE TABLE IF NOT EXISTS `menu_location` (
                    `menu_location_id` INT NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(45) NOT NULL,
                    `description` VARCHAR(255) NOT NULL,
                    `slug` VARCHAR(55) NOT NULL,
                    `menu_id` INT NULL,
                PRIMARY KEY (`menu_location_id`),
                INDEX `fk_menu_location_menu1_idx` (`menu_id` ASC),
                CONSTRAINT `fk_menu_location_menu1`
                 FOREIGN KEY (`menu_id`)
                 REFERENCES `menu` (`menu_id`)
                 ON DELETE NO ACTION
                 ON UPDATE NO ACTION)
                ENGINE = InnoDB");
        
        $this->execute(
                "CREATE TABLE IF NOT EXISTS `menu_item` (
                    `menu_item_id` INT NOT NULL AUTO_INCREMENT,
                    `label` VARCHAR(45) NOT NULL,
                    `url` VARCHAR(255) NOT NULL,
                    `class` VARCHAR(255) NOT NULL,
                    `left` INT  NULL DEFAULT NULL,
                    `rigth` INT NULL DEFAULT NULL,
                    `menu_id` INT NOT NULL,
                    `parent_id` INT NULL DEFAULT NULL,
                PRIMARY KEY (`menu_item_id`),
                INDEX `fk_menu_item_menu1_idx` (`menu_id` ASC),
                INDEX `fk_menu_item_menu_item1_idx` (`parent_id` ASC),
                CONSTRAINT `fk_menu_item_menu1`
                    FOREIGN KEY (`menu_id`)
                    REFERENCES `menu` (`menu_id`)
                     ON DELETE NO ACTION
                    ON UPDATE NO ACTION,
                CONSTRAINT `fk_menu_item_menu_item1`
                    FOREIGN KEY (`parent_id`)
                    REFERENCES `menu_item` (`menu_item_id`)
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION)
                ENGINE = InnoDB");
    }

    public function down()
    {
        echo "m161129_155755_menu_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
