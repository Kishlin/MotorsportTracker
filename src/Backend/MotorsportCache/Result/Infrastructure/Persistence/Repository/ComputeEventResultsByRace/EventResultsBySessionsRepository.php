<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace;

use Kishlin\Backend\MotorsportCache\Result\Application\ComputeEventResultsBySessions\Gateway\EventResultsBySessionsGateway;
use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class EventResultsBySessionsRepository extends CacheRepository implements EventResultsBySessionsGateway
{
    public function save(EventResultsByRace $eventResultsByRace): void
    {
        $this->persist($eventResultsByRace);
    }
}
