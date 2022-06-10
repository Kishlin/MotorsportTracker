<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Application\GetCountryIdForCode;

use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class GetCountryIdForCodeResponse implements Response
{
    private function __construct(
        private CountryId $countryId,
    ) {
    }

    public function countryId(): CountryId
    {
        return $this->countryId;
    }

    public static function create(CountryId $countryId): self
    {
        return new self($countryId);
    }
}
