<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Retirement;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToRetirementConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Retirement::instance(
            new UuidValueObject($fixture->identifier()),
            new UuidValueObject($fixture->getString('entry')),
            new StringValueObject($fixture->getString('reason')),
            new StringValueObject($fixture->getString('type')),
            new BoolValueObject($fixture->getBool('dns')),
            new PositiveIntValueObject($fixture->getInt('lap')),
        );
    }
}
