<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider\Event;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventStep;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepDateTime;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepEventId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepTypeId;

final class EventStepProvider
{
    public static function dutchGrandPrixRace(): EventStep
    {
        return EventStep::instance(
            new EventStepId('090df456-e489-4c6c-94ac-b1a51a973fe6'),
            new EventStepTypeId('461231da-0c8c-43e9-adbf-dadca3e0d65d'),
            new EventStepEventId('84b3e2e0-0f81-4747-be83-bcbf958b7105'),
            new EventStepDateTime(new \DateTimeImmutable('2022-09-04 15:00:00')),
        );
    }
}
