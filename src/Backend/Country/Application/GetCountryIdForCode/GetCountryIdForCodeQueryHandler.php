<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Application\GetCountryIdForCode;

use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CountryIdForCodeGateway;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class GetCountryIdForCodeQueryHandler implements QueryHandler
{
    public function __construct(
        private CountryIdForCodeGateway $countryIdForCodeGateway,
    ) {
    }

    public function __invoke(GetCountryIdForCodeQuery $query): GetCountryIdForCodeResponse
    {
        $countryId = $this->countryIdForCodeGateway->idForCode($query->code());

        if (null === $countryId) {
            throw new CountryNotFoundException();
        }

        return GetCountryIdForCodeResponse::create($countryId);
    }
}
