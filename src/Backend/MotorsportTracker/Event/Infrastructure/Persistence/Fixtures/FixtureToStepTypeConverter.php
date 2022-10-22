<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Fixtures;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeLabel;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\Fixture;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureConverter;

final class FixtureToStepTypeConverter implements FixtureConverter
{
    public function convert(Fixture $fixture): AggregateRoot
    {
        return StepType::instance(
            new StepTypeId($fixture->identifier()),
            new StepTypeLabel($fixture->getString('label')),
        );
    }
}
