<?php

namespace app\tests\_fixtures;

use yii\test\ActiveFixture;

class EmployeeStatusFixture extends ActiveFixture
{
    public $modelClass = 'app\entities\Employee\Status';
    public $dataFile = '@tests/_fixtures/data/employee_status.php';
}