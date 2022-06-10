<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Application\CreateCountryIfNotExists;

use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Country\Domain\Gateway\CountryGateway;
use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CountryIdForCodeGateway;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;

final class CreateCountryIfNotExistsCommandHandler
{
    public function __construct(
        private CountryIdForCodeGateway $idForCodeGateway,
        private EventDispatcher $eventDispatcher,
        private CountryGateway $countryGateway,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateCountryIfNotExistsCommand $command): CountryId
    {
        $id = $this->idForCodeGateway->idForCode($command->code());

        if (null !== $id) {
            return $id;
        }

        $newId   = new CountryId($this->uuidGenerator->uuid4());
        $country = Country::create($newId, $command->code());

        $this->countryGateway->save($country);

        $this->eventDispatcher->dispatch(...$country->pullDomainEvents());

        return $country->id();
    }
}
