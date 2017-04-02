<?php

namespace app\services\dto;

class EmployeeCreateDto
{
    /**
     * @var NameDto
     */
    public $name;
    /**
     * @var AddressDto
     */
    public $address;
    /**
     * @var PhoneDto[]
     */
    public $phones;
}