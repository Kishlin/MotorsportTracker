<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Domain\Gateway;

use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;

interface CountryIdForCodeGateway
{
    public function idForCode(CountryCode $code): ?CountryId;
}
