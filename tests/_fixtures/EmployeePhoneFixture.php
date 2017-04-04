<?php

namespace tests\_fixtures;

use yii\test\ActiveFixture;

class EmployeePhoneFixture extends ActiveFixture
{
    public $tableName = '{{%doctrine_employee_phones}}';
    public $dataFile = '@tests/_fixtures/data/employee_phones.php';
}