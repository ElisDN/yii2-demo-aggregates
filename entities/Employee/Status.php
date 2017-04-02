<?php

namespace app\entities\Employee;

use Assert\Assertion;

class Status
{
    const ACTIVE = 'active';
    const ARCHIVED = 'archived';

    private $value;
    private $date;

    public function __construct(string $value, \DateTimeImmutable $date)
    {
        Assertion::inArray($value, [
            self::ACTIVE,
            self::ARCHIVED
        ]);

        $this->value = $value;
        $this->date = $date;
    }

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function isArchived(): bool
    {
        return $this->value === self::ARCHIVED;
    }

    public function getValue(): string { return $this->value; }
    public function getDate(): \DateTimeImmutable { return $this->date; }
}