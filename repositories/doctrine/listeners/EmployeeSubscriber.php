<?php

namespace app\repositories\doctrine\listeners;

use app\entities\Employee\Employee;
use app\entities\Employee\Phones;
use app\hydrator\Hydrator;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class EmployeeSubscriber implements EventSubscriber
{
    private $hydrator;

    public function __construct(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function getSubscribedEvents(): array
    {
        return array(
            Events::postLoad,
        );
    }

    public function postLoad(LifecycleEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        if ($entity instanceof Employee) {
            $data = $this->hydrator->extract($entity, ['relatedPhones']);

            $this->hydrator->hydrate($entity, [
                'phones' => $this->hydrator->hydrate(Phones::class, [
                     'employee' => $entity,
                     'phones' => $data['relatedPhones'],
                ])
            ]);
        }
    }
}