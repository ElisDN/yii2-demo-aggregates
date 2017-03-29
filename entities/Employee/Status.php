<?php

namespace app\entities\Employee;

use Assert\Assertion;
use yii\db\ActiveRecord;

class Status extends ActiveRecord
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
        parent::__construct();
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

    ######## INFRASTRUCTURE #########

    public static function tableName(): string
    {
        return '{{%ar_employee_statuses}}';
    }

    public static function instance($refresh = false): self
    {
        static $instance;
        return $refresh || !$instance ? $instance = self::instantiate([]) : $instance;
    }

    public static function instantiate($row): self
    {
        static $prototype;
        if ($prototype === null) {
            $class = \get_called_class();
            $prototype = unserialize(sprintf('O:%d:"%s":0:{}', \strlen($class), $class));
        }
        $object = clone $prototype;
        $object->init();
        return $object;
    }

    public function afterFind(): void
    {
        $this->value = $this->getAttribute('status_value');
        $this->date = new \DateTimeImmutable($this->getAttribute('status_date'));

        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('status_value', $this->value);
        $this->setAttribute('status_date', $this->date->format('Y-m-d H:i:s'));

        return parent::beforeSave($insert);
    }
}