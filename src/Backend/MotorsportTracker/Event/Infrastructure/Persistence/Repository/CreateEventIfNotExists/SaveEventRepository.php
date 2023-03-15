<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateEventIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\SaveEventGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveEventRepository extends CoreRepository implements SaveEventGateway
{
    public function save(Event $event): void
    {
        parent::persist($event);
    }
}
