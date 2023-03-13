<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateEventSessionIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventSessionIfNotExists\SaveEventSessionGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveEventSessionRepositoryUsingDoctrine extends CoreRepository implements SaveEventSessionGateway
{
    public function save(EventSession $eventSession): void
    {
        parent::persist($eventSession);
    }
}
