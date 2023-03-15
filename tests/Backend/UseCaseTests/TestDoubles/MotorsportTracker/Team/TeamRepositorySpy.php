<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\SaveTeamGateway;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\SearchTeamGateway;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\TeamCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Team[] $objects
 *
 * @method Team[]    all()
 * @method null|Team get(UuidValueObject $id)
 * @method Team      safeGet(UuidValueObject $id)
 */
final class TeamRepositorySpy extends AbstractRepositorySpy implements SaveTeamGateway, SearchTeamGateway
{
    public function save(Team $team): void
    {
        if (null !== $this->findForRef($team->ref())) {
            throw new TeamCreationFailureException();
        }

        $this->objects[$team->id()->value()] = $team;
    }

    public function findForRef(NullableUuidValueObject $ref): ?UuidValueObject
    {
        foreach ($this->objects as $savedTeam) {
            if ($savedTeam->ref()->equals($ref)) {
                return $savedTeam->id();
            }
        }

        return null;
    }
}
