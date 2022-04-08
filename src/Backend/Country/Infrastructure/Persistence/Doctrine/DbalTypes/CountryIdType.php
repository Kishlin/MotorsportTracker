<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractUuidType;

final class CountryIdType extends AbstractUuidType
{
    protected function mappedClass(): string
    {
        return CountryId::class;
    }
}
