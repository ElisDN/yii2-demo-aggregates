<?php

namespace tests\_fixtures;

use yii\test\ActiveFixture;

class EmployeeFixture extends ActiveFixture
{
    public $tableName = '{{%doctrine_employees}}';
    public $dataFile = '@tests/_fixtures/data/employees.php';
}