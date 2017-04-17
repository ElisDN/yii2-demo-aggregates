<?php

namespace app\repositories\doctrine\types\Employee;

use app\entities\Employee\Status;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class StatusesType extends JsonType
{
    const NAME = 'employee_statuses';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return json_encode(array_map(function (Status $status) {
            return [
                'value' => $status->getValue(),
                'date' => $status->getDate()->format(DATE_RFC3339),
            ];
        }, $value));
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return array();
        }

        $value = (is_resource($value)) ? stream_get_contents($value) : $value;

        return array_map(function ($row) {
            return new Status(
                $row['value'],
                new \DateTimeImmutable($row['date'])
            );
        }, json_decode($value, true));
    }

    public function getName()
    {
        return self::NAME;
    }
}
