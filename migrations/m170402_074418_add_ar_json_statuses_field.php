<?php

use yii\db\Migration;

class m170402_074418_add_ar_json_statuses_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%ar_employees}}', 'employee_statuses', 'JSON');
    }

    public function down()
    {
        $this->dropColumn('{{%ar_employees}}', 'employee_statuses');
    }
}
