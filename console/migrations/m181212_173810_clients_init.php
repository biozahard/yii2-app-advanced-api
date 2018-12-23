<?php

use yii\db\Migration;

/**
 * Class m181212_173810_clients_init
 */
class m181212_173810_clients_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /*
        $this->execute("CREATE TABLE `medisparkcrm`.`client`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NULL,
    `phone` VARCHAR(120) NULL,
    `status` INT NOT NULL,
    `name` VARCHAR(100) NULL,
    `last_name` VARCHAR(100) NULL,
    `middle_name` VARCHAR(100) NULL,
    `created_at` INT NOT NULL,
    `updated_at` INT NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE = InnoDB CHARSET = utf8 COLLATE utf8_general_ci");

        $this->execute("CREATE TABLE `medisparkcrm`.`client_property`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `alias` VARCHAR(128) NOT NULL,
    `description` VARCHAR(500) NOT NULL DEFAULT '',
    `type` VARCHAR(128) NOT NULL DEFAULT 'text_line',
    `field_data` VARCHAR(1024) NOT NULL,
    `created_at` INT NOT NULL,
    `updated_at` INT NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE = InnoDB CHARSET = utf8 COLLATE utf8_general_ci;");
        */

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181212_173810_clients_init cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181212_173810_clients_init cannot be reverted.\n";

        return false;
    }
    */
}
