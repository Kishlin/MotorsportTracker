<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateDriverIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SearchDriverGateway $searchGateway,
        private readonly SaveDriverGateway $saveGateway,
        private readonly UuidGenerator $uuidGenerator,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateDriverIfNotExistsCommand $command): UuidValueObject
    {
        $driverId = $this->searchGateway->findBySlug($command->slug()->value());

        if (null !== $driverId) {
            return $driverId;
        }

        $driverId = new UuidValueObject($this->uuidGenerator->uuid4());
        $driver   = Driver::create($driverId, $command->name(), $command->slug(), $command->countryId());

        try {
            $this->saveGateway->save($driver);
        } catch (Throwable $e) {
            throw new DriverCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$driver->pullDomainEvents());

        return $driverId;
    }
}