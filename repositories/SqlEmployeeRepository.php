<?php

namespace app\repositories;

use app\entities\Employee\Address;
use app\entities\Employee\Employee;
use app\entities\Employee\Id;
use app\entities\Employee\Name;
use app\entities\Employee\Phone;
use app\entities\Employee\Phones;
use app\entities\Employee\Status;
use Ramsey\Uuid\Uuid;
use yii\db\Connection;
use yii\db\Query;

class SqlEmployeeRepository implements EmployeeRepository
{
    private $db;
    private $hydrator;

    public function __construct(Connection $db, Hydrator $hydrator)
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
    }

    /**
     * @param Id $id
     * @return Employee|object
     * @throws \Exception
     */
    public function get(Id $id): Employee
    {
        $employee = (new Query())->select('*')
            ->from('{{%sql_employees}}')
            ->andWhere(['id' => $id->getId()])
            ->one($this->db);

        if (!$employee) {
            throw new NotFoundException('Employee not found.');
        }

        $phones = (new Query())->select('*')
            ->from('{{%sql_employee_phones}}')
            ->andWhere(['employee_id' => $id->getId()])
            ->orderBy('id')
            ->all($this->db);

        $statuses = (new Query())->select('*')
            ->from('{{%sql_employee_statuses}}')
            ->andWhere(['employee_id' => $id->getId()])
            ->orderBy('id')
            ->all($this->db);

        return $this->hydrator->hydrate(Employee::class, [
            'id' => new Id($employee['id']),
            'name' => new Name(
                $employee['name_last'],
                $employee['name_first'],
                $employee['name_middle']
            ),
            'address' => new Address(
                $employee['address_country'],
                $employee['address_region'],
                $employee['address_city'],
                $employee['address_street'],
                $employee['address_house']
            ),
            'createDate' => new \DateTimeImmutable($employee['create_date']),
            'phones' => new Phones(array_map(static function ($phone) {
                return new Phone(
                    $phone['country'],
                    $phone['code'],
                    $phone['number']
                );
            }, $phones)),
            'statuses' => array_map(static function ($status) {
                return new Status(
                    $status['value'],
                    new \DateTimeImmutable($status['date'])
                );
            }, $statuses),
        ]);
    }

    public function add(Employee $employee): void
    {
        $this->db->transaction(function () use ($employee) {
            $this->db->createCommand()
                ->insert('{{%sql_employees}}', self::extractEmployeeData($employee))
                ->execute();
            $this->updatePhones($employee);
            $this->updateStatuses($employee);
        });
    }

    public function save(Employee $employee): void
    {
        $this->db->transaction(function () use ($employee) {
            $this->db->createCommand()
                ->update(
                    '{{%sql_employees}}',
                    self::extractEmployeeData($employee),
                    ['id' => $employee->getId()->getId()]
                )->execute();
            $this->updatePhones($employee);
            $this->updateStatuses($employee);
        });
    }

    public function remove(Employee $employee): void
    {
        $this->db->createCommand()
            ->delete('{{%sql_employees}}', ['id' => $employee->getId()->getId()])
            ->execute();
    }

    private static function extractEmployeeData(Employee $employee)
    {
        $statuses = $employee->getStatuses();

        return [
            'id' => $employee->getId()->getId(),
            'create_date' => $employee->getCreateDate()->format('Y-m-d H:i:s'),
            'name_last' => $employee->getName()->getLast(),
            'name_middle' => $employee->getName()->getMiddle(),
            'name_first' => $employee->getName()->getFirst(),
            'address_country' => $employee->getAddress()->getCountry(),
            'address_region' => $employee->getAddress()->getRegion(),
            'address_city' => $employee->getAddress()->getCity(),
            'address_street' => $employee->getAddress()->getStreet(),
            'address_house' => $employee->getAddress()->getHouse(),
            'current_status' => end($statuses)->getValue(),
        ];
    }

    private function updatePhones(Employee $employee)
    {
        $this->db->createCommand()
            ->delete('{{%sql_employee_phones}}', ['employee_id' => $employee->getId()->getId()])
            ->execute();

        if ($employee->getPhones()) {
            $this->db->createCommand()
                ->batchInsert('{{%sql_employee_phones}}', ['employee_id', 'country', 'code', 'number'],
                    array_map(function (Phone $phone) use ($employee) {
                        return [
                            'employee_id' => $employee->getId()->getId(),
                            'country' => $phone->getCountry(),
                            'code' => $phone->getCode(),
                            'number' => $phone->getNumber(),
                        ];
                    }, $employee->getPhones()))
                ->execute();
        }
    }

    private function updateStatuses(Employee $employee)
    {
        $this->db->createCommand()
            ->delete('{{%sql_employee_statuses}}', ['employee_id' => $employee->getId()->getId()])
            ->execute();

        if ($employee->getStatuses()) {
            $this->db->createCommand()
                ->batchInsert('{{%sql_employee_statuses}}', ['employee_id', 'value', 'date'],
                    array_map(function (Status $status) use ($employee) {
                        return [
                            'employee_id' => $employee->getId()->getId(),
                            'value' => $status->getValue(),
                            'date' => $status->getDate()->format('Y-m-d H:i:s'),
                        ];
                    }, $employee->getStatuses()))
                ->execute();
        }
    }
}
