<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final readonly class CreateDriverIfNotExistsCommandHandler implements CommandHandler
{
    public function __construct(
        private SearchDriverGateway $searchGateway,
        private SaveDriverGateway $saveGateway,
        private UuidGenerator $uuidGenerator,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateDriverIfNotExistsCommand $command): UuidValueObject
    {
        $driverId = $this->searchGateway->findByNameOrRef($command->name(), $command->ref());

        if (null !== $driverId) {
            return $driverId;
        }

        $driverId = new UuidValueObject($this->uuidGenerator->uuid4());
        $driver   = Driver::create(
            $driverId,
            $command->name(),
            $command->shortCode(),
            $command->country(),
            $command->ref(),
        );

        try {
            $this->saveGateway->save($driver);
        } catch (Throwable $e) {
            throw new DriverCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$driver->pullDomainEvents());

        return $driverId;
    }
}
