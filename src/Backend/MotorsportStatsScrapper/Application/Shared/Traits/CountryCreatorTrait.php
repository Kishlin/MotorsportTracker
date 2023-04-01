<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits;

use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use RuntimeException;

trait CountryCreatorTrait
{
    /**
     * @param array{name: string, uuid: string, picture: string} $country
     */
    private function createCountryIfNotExists(array $country): UuidValueObject
    {
        if ('/' === $country['picture'][-6]) {
            throw new RuntimeException("Unexpected Country Picture format: {$country['picture']}");
        }

        $countryCode = substr($country['picture'], -6, 2);
        $command     = CreateCountryIfNotExistsCommand::fromScalars($countryCode, $country['name'], $country['uuid']);
        $countryId   = $this->commandBus->execute($command);

        assert($countryId instanceof UuidValueObject);

        return $countryId;
    }
}
