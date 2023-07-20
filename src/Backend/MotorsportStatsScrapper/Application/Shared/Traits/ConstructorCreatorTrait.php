<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorIfNotExists\CreateConstructorIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

trait ConstructorCreatorTrait
{
    private function createConstructorIfNotExists(string $name, string $ref): UuidValueObject
    {
        $constructorId = $this->commandBus->execute(CreateConstructorIfNotExistsCommand::fromScalars($name, $ref));

        assert($constructorId instanceof UuidValueObject);

        return $constructorId;
    }
}
