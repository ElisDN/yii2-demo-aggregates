<?php

use yii\db\Migration;

class m170417_192518_add_doctrine_json_statuses_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%doctrine_employees}}', 'statuses', 'JSON');
    }

    public function down()
    {
        $this->dropColumn('{{%doctrine_employees}}', 'statuses');
    }
}
