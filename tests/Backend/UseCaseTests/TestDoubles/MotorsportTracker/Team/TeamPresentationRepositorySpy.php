<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentationIfNotExists\SaveTeamPresentationGateway;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentationIfNotExists\SearchTeamPresentationGateway;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentationIfNotExists\TeamPresentationCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Country\SaveSearchCountryRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property TeamPresentation[] $objects
 *
 * @method TeamPresentation[]    all()
 * @method null|TeamPresentation get(UuidValueObject $id)
 * @method TeamPresentation      safeGet(UuidValueObject $id)
 */
final class TeamPresentationRepositorySpy extends AbstractRepositorySpy implements SaveTeamPresentationGateway, SearchTeamPresentationGateway
{
    public function __construct(
        private readonly SaveSearchCountryRepositorySpy $countryRepositorySpy,
    ) {
    }

    public function save(TeamPresentation $teamPresentation): void
    {
        if (
            false === $this->countryRepositorySpy->has($teamPresentation->country())
            || null !== $this->findByTeamAndSeason($teamPresentation->team(), $teamPresentation->season())
        ) {
            throw new TeamPresentationCreationFailureException();
        }

        $this->objects[$teamPresentation->id()->value()] = $teamPresentation;
    }

    public function findByTeamAndSeason(UuidValueObject $team, UuidValueObject $season): ?UuidValueObject
    {
        foreach ($this->objects as $savedTeamPresentation) {
            if ($savedTeamPresentation->team()->equals($team) || $savedTeamPresentation->season()->equals($season)) {
                return $savedTeamPresentation->id();
            }
        }

        return null;
    }
}
