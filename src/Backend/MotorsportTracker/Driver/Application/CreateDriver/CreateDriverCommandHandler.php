<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Gateway\DriverGateway;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Throwable;

final class CreateDriverCommandHandler implements CommandHandler
{
    public function __construct(
        private DriverGateway $gateway,
        private UuidGenerator $uuidGenerator,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(CreateDriverCommand $command): DriverId
    {
        $driverId = new DriverId($this->uuidGenerator->uuid4());

        $driver = Driver::create($driverId, $command->firstname(), $command->name(), $command->countryId());

        try {
            $this->gateway->save($driver);
        } catch (Throwable $e) {
            throw new DriverCreationFailureException(previous: $e);
        }

        $this->eventDispatcher->dispatch(...$driver->pullDomainEvents());

        return $driverId;
    }
}
