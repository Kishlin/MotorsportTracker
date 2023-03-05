<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\SaveTeamGateway;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\TeamCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\SaveSearchCountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Team[] $objects
 *
 * @method Team[]    all()
 * @method null|Team get(UuidValueObject $id)
 * @method Team      safeGet(UuidValueObject $id)
 */
final class SaveTeamRepositorySpy extends AbstractRepositorySpy implements SaveTeamGateway
{
    public function __construct(
        private readonly SaveSearchCountryRepositorySpy $countryRepositorySpy,
    ) {
    }

    public function save(Team $team): void
    {
        if (false === $this->countryRepositorySpy->has($team->countryId()) || $this->nameIsAlreadyTaken($team)) {
            throw new TeamCreationFailureException();
        }

        $this->objects[$team->id()->value()] = $team;
    }

    private function nameIsAlreadyTaken(Team $team): bool
    {
        foreach ($this->objects as $savedTeam) {
            if ($savedTeam->name()->equals($team->name())) {
                return true;
            }
        }

        return false;
    }
}
