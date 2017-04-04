<?php

namespace tests\_fixtures;

use yii\test\ActiveFixture;

class EmployeeStatusFixture extends ActiveFixture
{
    public $tableName = '{{%doctrine_employee_statuses}}';
    public $dataFile = '@tests/_fixtures/data/employee_statuses.php';
}