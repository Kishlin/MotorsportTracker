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

    public static function netherlands(): Country
    {
        return Country::instance(
            new CountryId('32bc1722-2ed6-4a81-b1dd-0cf578027b1f'),
            new CountryCode('nl'),
        );
    }

    public static function austria(): Country
    {
        return Country::instance(
            new CountryId('d5e6f0ce-eb8c-4da0-b071-31466027f32d'),
            new CountryCode('at'),
        );
    }

    public static function mexico(): Country
    {
        return Country::instance(
            new CountryId('dbe42261-243e-4e25-8a80-4aa957006c7e'),
            new CountryCode('mx'),
        );
    }
}
