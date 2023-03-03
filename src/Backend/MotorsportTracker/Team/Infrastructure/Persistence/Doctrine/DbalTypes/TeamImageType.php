<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamImage;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\StringValueObjectType;

final class TeamImageType extends StringValueObjectType
{
    protected function mappedClass(): string
    {
        return TeamImage::class;
    }
}
