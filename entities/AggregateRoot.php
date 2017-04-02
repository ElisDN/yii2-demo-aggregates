<?php

namespace app\entities;

interface AggregateRoot
{
    /**
     * @return array
     */
    public function releaseEvents(): array;
}