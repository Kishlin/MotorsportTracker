<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Application\GetCountryIdForCode;

use Kishlin\Backend\Shared\Domain\Exception\DomainException;

final class CountryNotFoundException extends DomainException
{
}
