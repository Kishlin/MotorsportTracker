<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Persistence\Repository;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\EventGraph;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Gateway\EventGraphGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;

final class EventGraphRepository extends CacheRepository implements EventGraphGateway
{
    public function save(EventGraph $eventGraph): void
    {
        $this->persist($eventGraph);
    }
}
