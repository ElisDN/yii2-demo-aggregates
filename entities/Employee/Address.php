<?php

namespace app\entities\Employee;

use Assert\Assertion;

class Address
{
    private $country;
    private $region;
    private $city;
    private $street;
    private $house;

    public function __construct(string $country, string $region, string $city, string $street, string $house)
    {
        Assertion::notEmpty($country);
        Assertion::notEmpty($city);

        $this->country = $country;
        $this->region = $region;
        $this->city = $city;
        $this->street = $street;
        $this->house = $house;
    }

    public function getCountry(): string { return $this->country; }
    public function getRegion(): string { return $this->region; }
    public function getCity(): string { return $this->city; }
    public function getStreet(): string { return $this->street; }
    public function getHouse(): string { return $this->house; }
}
