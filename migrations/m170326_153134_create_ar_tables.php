<?php

use yii\db\Migration;

class m170326_153134_create_ar_tables extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%ar_employees}}', [
            'employee_id' => $this->char(36)->notNull(),
            'employee_create_date' => $this->dateTime(),
            'employee_name_last' => $this->string(),
            'employee_name_first' => $this->string(),
            'employee_name_middle' => $this->string(),
            'employee_address_country' => $this->string(),
            'employee_address_region' => $this->string(),
            'employee_address_city' => $this->string(),
            'employee_address_street' => $this->string(),
            'employee_address_house' => $this->string(),
            'employee_current_status' => $this->string(16)->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-ar_employees', '{{%ar_employees}}', 'employee_id');

        $this->createTable('{{%ar_employee_phones}}', [
            'phone_id' => $this->primaryKey(),
            'phone_employee_id' => $this->char(36)->notNull(),
            'phone_country' => $this->integer()->notNull(),
            'phone_code' => $this->string()->notNull(),
            'phone_number' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-ar_employee_phones-employee_id', '{{%ar_employee_phones}}', 'phone_employee_id');
        $this->addForeignKey('fk-ar_employee_phones-employee', '{{%ar_employee_phones}}', 'phone_employee_id', '{{%ar_employees}}', 'employee_id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%ar_employee_statuses}}', [
            'status_id' => $this->primaryKey(),
            'status_employee_id' => $this->char(36)->notNull(),
            'status_value' => $this->string(32)->notNull(),
            'status_date' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-ar_employee_statuses-employee_id', '{{%ar_employee_statuses}}', 'status_employee_id');
        $this->addForeignKey('fk-ar_employee_statuses-employee', '{{%ar_employee_statuses}}', 'status_employee_id', '{{%ar_employees}}', 'employee_id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%ar_employee_statuses}}');
        $this->dropTable('{{%ar_employee_phones}}');
        $this->dropTable('{{%ar_employees}}');
    }
}
