<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\TeamCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam\SearchTeamViewer;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Gateway\TeamGateway;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
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
final class TeamRepositorySpy extends AbstractRepositorySpy implements TeamGateway, SearchTeamViewer
{
    public function __construct(
        private SaveSearchCountryRepositorySpy $countryRepositorySpy,
    ) {
    }

    public function save(Team $team): void
    {
        if (false === $this->countryRepositorySpy->has($team->countryId()) || $this->nameIsAlreadyTaken($team)) {
            throw new TeamCreationFailureException();
        }

        $this->objects[$team->id()->value()] = $team;
    }

    public function search(string $keyword): ?TeamId
    {
        foreach ($this->objects as $team) {
            if (str_contains($team->name()->value(), $keyword)) {
                return $team->id();
            }
        }

        return null;
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
