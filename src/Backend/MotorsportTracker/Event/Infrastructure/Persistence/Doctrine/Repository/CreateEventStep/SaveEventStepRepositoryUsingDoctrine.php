<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventStep;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\SaveEventStepGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SaveEventStepRepositoryUsingDoctrine extends CoreRepository implements SaveEventStepGateway
{
    public function save(EventStep $eventStep): void
    {
        parent::persist($eventStep);
    }
}
