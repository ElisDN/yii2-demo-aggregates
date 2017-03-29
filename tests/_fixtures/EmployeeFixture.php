<?php

namespace app\tests\_fixtures;

use yii\test\ActiveFixture;

class EmployeeFixture extends ActiveFixture
{
    public $modelClass = 'app\entities\Employee\Employee';
    public $dataFile = '@tests/_fixtures/data/employee.php';
}