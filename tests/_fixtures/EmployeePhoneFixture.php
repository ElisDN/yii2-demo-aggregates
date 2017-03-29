<?php

namespace app\tests\_fixtures;

use yii\test\ActiveFixture;

class EmployeePhoneFixture extends ActiveFixture
{
    public $modelClass = 'app\entities\Employee\Phone';
    public $dataFile = '@tests/_fixtures/data/employee_phone.php';
}