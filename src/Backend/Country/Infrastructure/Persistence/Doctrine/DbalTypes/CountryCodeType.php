<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractStringType;

final class CountryCodeType extends AbstractStringType
{
    protected function mappedClass(): string
    {
        return CountryCode::class;
    }
}
