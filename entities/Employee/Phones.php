<?php

namespace app\entities\Employee;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Phones
{
    private $employee;
    /**
     * @var Collection|EmployeePhone[]
     */
    private $phones;

    public function __construct(Employee $employee, &$relatedPhones, array $phones)
    {
        if (!$phones) {
            throw new \DomainException('Employee must contain at least one phone.');
        }
        $this->employee = $employee;
        $this->phones = $relatedPhones = new ArrayCollection();
        foreach ($phones as $phone) {
            $this->add($phone);
        }
    }

    public function add(Phone $phone): void
    {
        foreach ($this->phones as $item) {
            if ($item->getPhone()->isEqualTo($phone)) {
                throw new \DomainException('Phone already exists.');
            }
        }
        $this->phones->add(new EmployeePhone($this->employee, $phone));
    }

    public function remove($index): Phone
    {
        if (!isset($this->phones[$index])) {
            throw new \DomainException('Phone is not found.');
        }
        if ($this->phones->count() === 1) {
            throw new \DomainException('Cannot remove the last phone.');
        }
        return $this->phones->remove($index)->getPhone();
    }

    public function getAll(): array
    {
        return $this->phones->map(function (EmployeePhone $row): Phone {
            return $row->getPhone();
        })->toArray();
    }
}
