<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Application\CreateCountryIfNotExists;

use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CreateCountryIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchCountryGateway $searchGateway,
        private SaveCountryGateway $saveGateway,
        private UuidGenerator $uuidGenerator,
    ) {}

    public function __invoke(CreateCountryIfNotExistsCommand $command): UuidValueObject
    {
        $id = $this->searchGateway->searchForName($command->name());

        if (null !== $id) {
            return $id;
        }

        $newId   = new UuidValueObject($this->uuidGenerator->uuid4());
        $country = Country::create($newId, $command->code(), $command->name(), $command->ref());

        $this->saveGateway->save($country);

        return $country->id();
    }
}
