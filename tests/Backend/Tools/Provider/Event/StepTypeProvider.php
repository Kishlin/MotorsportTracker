<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider\Event;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeLabel;

final class StepTypeProvider
{
    public static function race(): StepType
    {
        return StepType::instance(
            new StepTypeId('461231da-0c8c-43e9-adbf-dadca3e0d65d'),
            new StepTypeLabel('race'),
        );
    }
}
