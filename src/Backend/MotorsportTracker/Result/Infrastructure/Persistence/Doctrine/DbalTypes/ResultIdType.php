<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\UuidValueObjectType;

final class ResultIdType extends UuidValueObjectType
{
    protected function mappedClass(): string
    {
        return ResultId::class;
    }
}
