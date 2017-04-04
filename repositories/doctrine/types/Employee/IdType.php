<?php

namespace app\repositories\doctrine\types\Employee;

use app\entities\Employee\Id;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class IdType extends GuidType
{
    const NAME = 'employee_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        /** @var Id $value */
        return $value->getId();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Id($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
