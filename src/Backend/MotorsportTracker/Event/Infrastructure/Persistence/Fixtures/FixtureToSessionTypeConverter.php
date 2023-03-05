<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\SessionType;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToSessionTypeConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return SessionType::instance(
            new UuidValueObject($fixture->identifier()),
            new StringValueObject($fixture->getString('label')),
        );
    }
}
