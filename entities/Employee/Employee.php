<?php

namespace app\entities\Employee;

use app\entities\AggregateRoot;
use app\entities\Employee\Events;
use app\entities\EventTrait;

class Employee implements AggregateRoot
{
    use EventTrait;

    /**
     * @var Id
     */
    private $id;
    /**
     * @var Name
     */
    private $name;
    /**
     * @var Address
     */
    private $address;
    /**
     * @var Phones
     */
    private $phones;
    /**
     * @var \DateTimeImmutable
     */
    private $createDate;
    /**
     * @var Status[]
     */
    private $statuses = [];

    public function __construct(Id $id, \DateTimeImmutable $date, Name $name, Address $address, array $phones)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->phones = new Phones($phones);
        $this->createDate = $date;
        $this->addStatus(Status::ACTIVE, $date);
        $this->recordEvent(new Events\EmployeeCreated($this->id));
    }

    public function rename(Name $name): void
    {
        $this->name = $name;
        $this->recordEvent(new Events\EmployeeRenamed($this->id, $name));
    }

    public function changeAddress(Address $address): void
    {
        $this->address = $address;
        $this->recordEvent(new Events\EmployeeAddressChanged($this->id, $address));
    }

    public function addPhone(Phone $phone): void
    {
        $this->phones->add($phone);
        $this->recordEvent(new Events\EmployeePhoneAdded($this->id, $phone));
    }

    public function removePhone($index): void
    {
        $phone = $this->phones->remove($index);
        $this->recordEvent(new Events\EmployeePhoneRemoved($this->id, $phone));
    }

    public function archive(\DateTimeImmutable $date): void
    {
        if ($this->isArchived()) {
            throw new \DomainException('Employee is already archived.');
        }
        $this->addStatus(Status::ARCHIVED, $date);
        $this->recordEvent(new Events\EmployeeArchived($this->id, $date));
    }

    public function reinstate(\DateTimeImmutable $date): void
    {
        if (!$this->isArchived()) {
            throw new \DomainException('Employee is not archived.');
        }
        $this->addStatus(Status::ACTIVE, $date);
        $this->recordEvent(new Events\EmployeeReinstated($this->id, $date));
    }

    public function remove(): void
    {
        if (!$this->isArchived()) {
            throw new \DomainException('Cannot remove active employee.');
        }
        $this->recordEvent(new Events\EmployeeRemoved($this->id));
    }

    public function isActive(): bool
    {
        return $this->getCurrentStatus()->isActive();
    }

    public function isArchived(): bool
    {
        return $this->getCurrentStatus()->isArchived();
    }

    private function getCurrentStatus(): Status
    {
        return end($this->statuses);
    }

    private function addStatus($value, \DateTimeImmutable $date): void
    {
        $this->statuses[] = new Status($value, $date);
    }

    public function getId(): Id { return $this->id; }
    public function getName(): Name { return $this->name; }
    public function getPhones(): array { return $this->phones->getAll(); }
    public function getAddress(): Address { return $this->address; }
    public function getCreateDate(): \DateTimeImmutable { return $this->createDate; }
    public function getStatuses(): array { return $this->statuses; }
}
