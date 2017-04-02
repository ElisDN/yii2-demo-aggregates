<?php

use yii\db\Migration;

class m170402_083539_add_sql_json_statuses_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%sql_employees}}', 'statuses', 'JSON');
    }

    public function down()
    {
        $this->dropColumn('{{%sql_employees}}', 'statuses');
    }
}
