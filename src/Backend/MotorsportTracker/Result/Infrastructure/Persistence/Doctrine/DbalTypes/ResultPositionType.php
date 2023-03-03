<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\ResultPosition;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\StringValueObjectType;

final class ResultPositionType extends StringValueObjectType
{
    protected function mappedClass(): string
    {
        return ResultPosition::class;
    }
}
