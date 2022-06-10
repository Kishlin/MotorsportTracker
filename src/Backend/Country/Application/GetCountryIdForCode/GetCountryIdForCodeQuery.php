<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Application\GetCountryIdForCode;

use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Shared\Domain\Bus\Query\Query;

final class GetCountryIdForCodeQuery implements Query
{
    private function __construct(
        private string $code
    ) {
    }

    public function code(): CountryCode
    {
        return new CountryCode($this->code);
    }

    public static function fromScalars(string $code): self
    {
        return new self($code);
    }
}
