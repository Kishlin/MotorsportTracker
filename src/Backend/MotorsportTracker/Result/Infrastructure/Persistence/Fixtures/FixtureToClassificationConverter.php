<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Classification;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableBoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableFloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveFloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToClassificationConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return Classification::instance(
            new UuidValueObject($fixture->identifier()),
            new UuidValueObject($fixture->getString('entry')),
            new PositiveIntValueObject($fixture->getInt('finishPosition')),
            new NullableIntValueObject($fixture->getInt('gridPosition')),
            new PositiveIntValueObject($fixture->getInt('laps')),
            new PositiveFloatValueObject($fixture->getFloat('points')),
            new PositiveFloatValueObject($fixture->getFloat('time')),
            new NullableStringValueObject($fixture->getString('classifiedStatus')),
            new PositiveFloatValueObject($fixture->getFloat('averageLapSpeed')),
            new NullableFloatValueObject($fixture->getFloat('fastestLapTime')),
            new PositiveFloatValueObject($fixture->getFloat('gapTimeToLead')),
            new PositiveFloatValueObject($fixture->getFloat('gapTimeToNext')),
            new PositiveIntValueObject($fixture->getInt('gapLapsToLead')),
            new PositiveIntValueObject($fixture->getInt('gapLapsToNext')),
            new NullableIntValueObject($fixture->getInt('bestLap')),
            new NullableFloatValueObject($fixture->getFloat('bestTime')),
            new NullableBoolValueObject($fixture->getBool('bestIsFastest')),
            new NullableFloatValueObject($fixture->getFloat('bestSpeed')),
        );
    }
}
