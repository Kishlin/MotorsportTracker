<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Gateway\EventStepGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class EventStepRepositoryUsingDoctrine extends CoreRepository implements EventStepGateway
{
    public function save(EventStep $eventStep): void
    {
        parent::persist($eventStep);
    }
}
