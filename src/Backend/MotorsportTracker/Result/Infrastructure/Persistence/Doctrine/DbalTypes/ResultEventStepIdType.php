<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultEventStepId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class ResultEventStepIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return ResultEventStepId::class;
    }
}
