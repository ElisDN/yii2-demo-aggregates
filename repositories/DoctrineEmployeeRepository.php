<?php

namespace app\repositories;

use app\entities\Employee\Employee;
use app\entities\Employee\Id;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DoctrineEmployeeRepository implements EmployeeRepository
{
    private $em;
    private $entityRepository;

    public function __construct(EntityManager $em, EntityRepository $entityRepository)
    {
        $this->em = $em;
        $this->entityRepository = $entityRepository;
    }

    public function get(Id $id): Employee
    {
        /** @var Employee $employee */
        if (!$employee = $this->entityRepository->find($id)) {
            throw new NotFoundException('Employee not found.');
        }
        return $employee;
    }

    public function add(Employee $employee): void
    {
        $this->em->persist($employee);
        $this->em->flush($employee);
    }

    public function save(Employee $employee): void
    {
        $this->em->flush($employee);
    }

    public function remove(Employee $employee): void
    {
        $this->em->remove($employee);
        $this->em->flush($employee);
    }
}
