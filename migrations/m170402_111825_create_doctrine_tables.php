<?php

use yii\db\Migration;

class m170402_111825_create_doctrine_tables extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%doctrine_employees}}', [
            'id' => $this->char(36)->notNull(),
            'create_date' => $this->dateTime(),
            'name_last' => $this->string(),
            'name_first' => $this->string(),
            'name_middle' => $this->string(),
            'address_country' => $this->string(),
            'address_region' => $this->string(),
            'address_city' => $this->string(),
            'address_street' => $this->string(),
            'address_house' => $this->string(),
            'current_status' => $this->string(32)->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-doctrine_employees', '{{%doctrine_employees}}', 'id');

        $this->createTable('{{%doctrine_employee_phones}}', [
            'id' => $this->primaryKey(),
            'employee_id' => $this->char(36)->notNull(),
            'country' => $this->integer()->notNull(),
            'code' => $this->string()->notNull(),
            'number' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-doctrine_employee_phones-employee_id', '{{%doctrine_employee_phones}}', 'employee_id');
        $this->addForeignKey('fk-doctrine_employee_phones-employee', '{{%doctrine_employee_phones}}', 'employee_id', '{{%doctrine_employees}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%doctrine_employee_statuses}}', [
            'id' => $this->primaryKey(),
            'employee_id' => $this->char(36)->notNull(),
            'value' => $this->string(32)->notNull(),
            'date' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-doctrine_employee_statuses-employee_id', '{{%doctrine_employee_statuses}}', 'employee_id');
        $this->addForeignKey('fk-doctrine_employee_statuses-employee', '{{%doctrine_employee_statuses}}', 'employee_id', '{{%doctrine_employees}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%doctrine_employee_statuses}}');
        $this->dropTable('{{%doctrine_employee_phones}}');
        $this->dropTable('{{%doctrine_employees}}');
    }
}
