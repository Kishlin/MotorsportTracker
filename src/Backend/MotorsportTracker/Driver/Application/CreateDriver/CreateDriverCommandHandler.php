<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Throwable;

final class CreateDriverCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SaveDriverGateway $gateway,
        private readonly UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(CreateDriverCommand $command): UuidValueObject
    {
        $driverId = new UuidValueObject($this->uuidGenerator->uuid4());

        $driver = Driver::create($driverId, $command->name(), $command->countryId());

        try {
            $this->gateway->save($driver);
        } catch (Throwable $e) {
            throw new DriverCreationFailureException(previous: $e);
        }

        return $driverId;
    }
}
