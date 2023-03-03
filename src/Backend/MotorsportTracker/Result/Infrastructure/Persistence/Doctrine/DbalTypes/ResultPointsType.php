<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPoints;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\FloatValueObjectType;

final class ResultPointsType extends FloatValueObjectType
{
    protected function mappedClass(): string
    {
        return ResultPoints::class;
    }
}
