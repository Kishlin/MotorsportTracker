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
        $countryCode = $this->getCountryCode($country['picture']);

        $command   = CreateCountryIfNotExistsCommand::fromScalars($countryCode, $country['name'], $country['uuid']);
        $countryId = $this->commandBus->execute($command);

        assert($countryId instanceof UuidValueObject);

        return $countryId;
    }

    private function getCountryCode(?string $picture): ?string
    {
        if (null === $picture) {
            return null;
        }

        if ('/' === $picture[-6]) {
            throw new RuntimeException("Unexpected Country Picture format: {$picture}");
        }

        return substr($picture, -6, 2);
    }
}
