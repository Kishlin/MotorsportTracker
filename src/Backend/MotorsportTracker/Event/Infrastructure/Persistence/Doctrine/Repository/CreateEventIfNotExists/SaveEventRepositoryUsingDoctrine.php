<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\SaveEventGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepositoryLegacy;

final class SaveEventRepositoryUsingDoctrine extends CoreRepositoryLegacy implements SaveEventGateway
{
    public function save(Event $event): void
    {
        parent::persist($event);
    }
}
