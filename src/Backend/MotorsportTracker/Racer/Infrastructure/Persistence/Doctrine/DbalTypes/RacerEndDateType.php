<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerEndDate;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractDatetimeType;

final class RacerEndDateType extends AbstractDatetimeType
{
    protected function mappedClass(): string
    {
        return RacerEndDate::class;
    }
}
