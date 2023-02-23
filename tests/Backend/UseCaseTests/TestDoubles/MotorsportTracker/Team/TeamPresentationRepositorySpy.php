<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Team;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentation\SaveTeamPresentationGateway;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property TeamPresentation[] $objects
 *
 * @method TeamPresentation[]    all()
 * @method null|TeamPresentation get(UuidValueObject $id)
 * @method TeamPresentation      safeGet(UuidValueObject $id)
 */
final class TeamPresentationRepositorySpy extends AbstractRepositorySpy implements SaveTeamPresentationGateway
{
    public function save(TeamPresentation $teamPresentation): void
    {
        $this->add($teamPresentation);
    }

    public function latest(UuidValueObject $teamId): ?TeamPresentation
    {
        /** @var ?TeamPresentation $latest */
        $latest = null;

        foreach ($this->objects as $teamPresentation) {
            if (false === $teamId->equals($teamPresentation->teamId())) {
                continue;
            }

            if (null === $latest) {
                $latest = $teamPresentation;

                continue;
            }

            if ($teamPresentation->createdOn()->value() > $latest->createdOn()->value()) {
                $latest = $teamPresentation;
            }
        }

        return $latest;
    }
}
