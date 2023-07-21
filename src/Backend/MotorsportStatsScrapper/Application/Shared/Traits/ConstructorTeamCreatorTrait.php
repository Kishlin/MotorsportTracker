<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorTeamIfNotExists\CreateConstructorTeamIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

trait ConstructorTeamCreatorTrait
{
    private function createConstructorTeamIfNotExists(UuidValueObject $constructor, UuidValueObject $team): UuidValueObject
    {
        $constructorId = $this->commandBus->execute(
            CreateConstructorTeamIfNotExistsCommand::fromScalars($constructor->value(), $team->value()),
        );

        assert($constructorId instanceof UuidValueObject);

        return $constructorId;
    }
}
