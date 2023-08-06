<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists\CreateDriverIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

trait DriverCreatorTrait
{
    /**
     * @param array{name: string, uuid: string, shortCode: string} $driver
     */
    private function createDriverIfNotExists(array $driver): UuidValueObject
    {
        $driverId = $this->commandBus->execute(
            CreateDriverIfNotExistsCommand::fromScalars(
                name: $driver['name'],
                shortCode: $driver['shortCode'],
                ref: $driver['uuid'],
            ),
        );
        assert($driverId instanceof UuidValueObject);

        return $driverId;
    }
}
