<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Provider\Country;

use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;

final class CountryProvider
{
    public static function country(): Country
    {
        return Country::instance(
            new CountryId('59055fe0-ad79-40f9-a556-fd26dadedc2f'),
            new CountryCode('fr'),
        );
    }
}
