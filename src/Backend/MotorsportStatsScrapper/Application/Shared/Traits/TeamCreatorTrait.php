<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Traits;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\CreateTeamIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

trait TeamCreatorTrait
{
    private function createTeamIfNotExists(string $season, string $name, ?string $color, string $ref): UuidValueObject
    {
        $teamId = $this->commandBus->execute(
            CreateTeamIfNotExistsCommand::fromScalars($season, $name, $color, $ref),
        );
        assert($teamId instanceof UuidValueObject);

        return $teamId;
    }
}
