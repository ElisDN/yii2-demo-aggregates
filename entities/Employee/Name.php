<?php

namespace app\entities\Employee;

use Assert\Assertion;

class Name
{
    private $last;
    private $first;
    private $middle;

    public function __construct(string $last, string $first, ?string $middle)
    {
        Assertion::notEmpty($last);
        Assertion::notEmpty($first);

        $this->last = $last;
        $this->first = $first;
        $this->middle = $middle;
    }

    public function getFull(): string
    {
        return trim($this->last . ' ' . $this->first . ' ' . $this->middle);
    }

    public function getFirst(): string { return $this->first; }
    public function getMiddle(): ?string { return $this->middle; }
    public function getLast(): string { return $this->last; }
}
