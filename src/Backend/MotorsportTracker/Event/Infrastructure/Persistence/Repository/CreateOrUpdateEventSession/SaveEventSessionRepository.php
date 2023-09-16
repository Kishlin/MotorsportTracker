<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateOrUpdateEventSession;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession\SaveEventSessionGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveEventSessionRepository extends CoreRepository implements SaveEventSessionGateway
{
    public function save(EventSession $eventSession): void
    {
        parent::persist($eventSession);
    }
}
